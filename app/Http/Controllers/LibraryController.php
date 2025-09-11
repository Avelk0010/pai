<?php

namespace App\Http\Controllers;

use App\Models\LibraryResource;
use App\Models\LibraryLoan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LibraryController extends Controller
{
    /**
     * Display the library main page with resources.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $resourceType = $request->get('type');

        $resources = LibraryResource::active()
            ->when($search, function ($query, $search) {
                return $query->search($search);
            })
            ->when($resourceType, function ($query, $resourceType) {
                return $query->byType($resourceType);
            })
            ->with(['loans' => function ($query) {
                $query->active();
            }])
            ->orderBy('title')
            ->paginate(20);

        $resourceTypes = ['book', 'magazine', 'digital', 'multimedia'];
        $totalResources = LibraryResource::active()->count();
        $availableResources = LibraryResource::active()->available()->count();

        return view('library.index', compact('resources', 'resourceTypes', 'search', 'resourceType', 'totalResources', 'availableResources'));
    }

    /**
     * Display a specific library resource.
     */
    public function resource(LibraryResource $resource): View
    {
        if (!$resource->status) {
            abort(404, 'Recurso no encontrado.');
        }

        $resource->load(['loans' => function ($query) {
            $query->with('user')->latest();
        }]);
        
        // Check if current user has this resource on loan
        $userLoan = null;
        if (Auth::check()) {
            $userLoan = $resource->activeLoans()
                ->where('user_id', Auth::id())
                ->first();
        }

        // Get similar resources
        $similarResources = LibraryResource::active()
            ->where('resource_type', $resource->resource_type)
            ->where('id', '!=', $resource->id)
            ->limit(4)
            ->get();

        return view('library.resource', compact('resource', 'userLoan', 'similarResources'));
    }

    /**
     * Search library resources.
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:255',
            'type' => 'nullable|string|in:book,magazine,digital,multimedia'
        ]);

        $query = $validated['q'];
        $resourceType = $validated['type'] ?? null;

        $resources = LibraryResource::active()
            ->search($query)
            ->when($resourceType, function ($q, $resourceType) {
                return $q->byType($resourceType);
            })
            ->with(['loans' => function ($query) {
                $query->active();
            }])
            ->orderBy('title')
            ->paginate(20);

        $resourceTypes = ['book', 'magazine', 'digital', 'multimedia'];

        return view('library.search', compact('resources', 'query', 'resourceType', 'resourceTypes'));
    }

    /**
     * Request to borrow a resource.
     */
    public function requestLoan(LibraryResource $resource): RedirectResponse
    {
        if (!$resource->status || !$resource->isAvailable()) {
            return redirect()->back()
                ->with('error', 'El recurso no está disponible para préstamo.');
        }

        // Check if user already has this resource on loan or requested
        $existingLoan = LibraryLoan::where('resource_id', $resource->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['requested', 'approved', 'active'])
            ->first();

        if ($existingLoan) {
            $statusText = [
                'requested' => 'ya has solicitado este recurso y está pendiente de aprobación',
                'approved' => 'ya tienes este recurso aprobado para préstamo',
                'active' => 'ya tienes este recurso en préstamo activo'
            ];
            
            return redirect()->back()
                ->with('error', 'No puedes solicitar este recurso porque ' . $statusText[$existingLoan->status] . '.');
        }

        // Check user's active loan limit (max 5)
        $activeLoanCount = LibraryLoan::where('user_id', Auth::id())
            ->whereIn('status', ['approved', 'active'])
            ->count();

        if ($activeLoanCount >= 5) {
            return redirect()->back()
                ->with('error', 'Has alcanzado el límite máximo de préstamos activos (5).');
        }

        // Create loan request
        LibraryLoan::create([
            'resource_id' => $resource->id,
            'user_id' => Auth::id(),
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Solicitud de préstamo enviada exitosamente. El administrador revisará tu solicitud.');
    }

    /**
     * Return a borrowed resource.
     */
    public function returnLoan(LibraryLoan $loan): RedirectResponse
    {
        // Check if user owns this loan or is admin
        if ($loan->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if ($loan->actual_return_date) {
            return redirect()->back()
                ->with('error', 'Este recurso ya ha sido devuelto.');
        }

        $loan->markAsReturned();

        return redirect()->back()
            ->with('success', 'Recurso devuelto exitosamente.');
    }

    /**
     * Display user's loan history.
     */
    public function myLoans(): View
    {
        $pendingRequests = LibraryLoan::with(['resource'])
            ->where('user_id', Auth::id())
            ->requested()
            ->orderBy('requested_at', 'desc')
            ->get();

        $activeLoans = LibraryLoan::with(['resource'])
            ->where('user_id', Auth::id())
            ->active()
            ->orderBy('return_date', 'asc')
            ->get();

        $loanHistory = LibraryLoan::with(['resource'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['returned', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $overdueLoans = LibraryLoan::with(['resource'])
            ->where('user_id', Auth::id())
            ->overdue()
            ->orderBy('return_date', 'asc')
            ->get();

        return view('library.my-loans', compact('pendingRequests', 'activeLoans', 'loanHistory', 'overdueLoans'));
    }

    /**
     * Renew a loan (extend due date).
     */
    public function renewLoan(LibraryLoan $loan): RedirectResponse
    {
        // Check if user owns this loan
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if ($loan->actual_return_date) {
            return redirect()->back()
                ->with('error', 'No se puede renovar un préstamo ya devuelto.');
        }

        // Check if loan is overdue
        if ($loan->isOverdue()) {
            return redirect()->back()
                ->with('error', 'No se puede renovar un préstamo vencido. Por favor devuelve el recurso.');
        }

        // Extend return date by 7 days
        $loan->update([
            'return_date' => $loan->return_date->addDays(7)
        ]);

        return redirect()->back()
            ->with('success', 'Préstamo renovado exitosamente. Nueva fecha de vencimiento: ' . $loan->return_date->format('d/m/Y'));
    }

    /**
     * Admin: Display all loans.
     */
    public function adminLoans(): View
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $pendingRequests = LibraryLoan::with(['resource', 'user'])
            ->requested()
            ->orderBy('requested_at', 'desc')
            ->paginate(15, ['*'], 'pending_page');

        $activeLoans = LibraryLoan::with(['resource', 'user'])
            ->active()
            ->orderBy('return_date', 'asc')
            ->paginate(20, ['*'], 'active_page');

        $overdueLoans = LibraryLoan::with(['resource', 'user'])
            ->shouldBeOverdue()
            ->orderBy('return_date', 'asc')
            ->get();

        // Update overdue loans status
        foreach ($overdueLoans as $loan) {
            $loan->markAsOverdue();
        }

        $recentReturns = LibraryLoan::with(['resource', 'user'])
            ->returned()
            ->orderBy('actual_return_date', 'desc')
            ->limit(10)
            ->get();

        $statistics = [
            'pending_requests' => LibraryLoan::requested()->count(),
            'total_active' => LibraryLoan::active()->count(),
            'total_overdue' => LibraryLoan::overdue()->count(),
            'total_returned_today' => LibraryLoan::returned()
                ->whereDate('actual_return_date', today())
                ->count(),
            'total_due_today' => LibraryLoan::active()
                ->whereDate('return_date', today())
                ->count(),
        ];

        return view('library.admin-loans', compact('pendingRequests', 'activeLoans', 'overdueLoans', 'recentReturns', 'statistics'));
    }

    /**
     * Admin: Approve a loan request.
     */
    public function approveLoan(LibraryLoan $loan): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if (!$loan->isRequested()) {
            return redirect()->back()
                ->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        // Check if resource is still available
        if (!$loan->resource->isAvailable()) {
            return redirect()->back()
                ->with('error', 'El recurso ya no está disponible.');
        }

        $loan->approve(Auth::id());

        return redirect()->back()
            ->with('success', "Solicitud de préstamo aprobada. El usuario {$loan->user->name} puede retirar el recurso.");
    }

    /**
     * Admin: Reject a loan request.
     */
    public function rejectLoan(Request $request, LibraryLoan $loan): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if (!$loan->isRequested()) {
            return redirect()->back()
                ->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $loan->reject(Auth::id(), $validated['rejection_reason']);

        return redirect()->back()
            ->with('success', "Solicitud de préstamo rechazada.");
    }

    /**
     * Admin: Manage library resources.
     */
    public function adminResources(): View
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $query = LibraryResource::query();

        // Apply filters
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if (request('resource_type')) {
            $query->where('resource_type', request('resource_type'));
        }

        if (request('status')) {
            $status = request('status');
            if ($status === 'available') {
                $query->where('status', true)->where('available_copies', '>', 0);
            } elseif ($status === 'unavailable') {
                $query->where('status', true)->where('available_copies', '=', 0);
            } elseif ($status === 'active') {
                $query->where('status', true);
            } elseif ($status === 'inactive') {
                $query->where('status', false);
            }
        }

        $resources = $query->orderBy('title')->paginate(20);

        $statistics = [
            'total_resources' => LibraryResource::count(),
            'available_resources' => LibraryResource::where('status', true)
                ->where('available_copies', '>', 0)->count(),
            'loaned_resources' => LibraryResource::where('status', true)
                ->whereRaw('available_copies < total_copies')->count(),
            'unavailable_resources' => LibraryResource::where('status', false)->count(),
        ];

        return view('library.admin-resources', compact('resources', 'statistics'));
    }

    /**
     * Admin: Create a new library resource.
     */
    public function createResource(): View
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return view('library.create-resource');
    }

        /**
     * Admin: Store a new library resource.
     */
    public function storeResource(Request $request): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:library_resources,isbn',
            'resource_type' => 'required|in:book,magazine,digital,multimedia',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer|min:0',
            'status' => 'boolean',
        ]);

        // Ensure available copies doesn't exceed total copies
        if ($validated['available_copies'] > $validated['total_copies']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['available_copies' => 'Las copias disponibles no pueden ser mayores que las copias totales.']);
        }

        // Set status to true if checked, false if not
        $validated['status'] = $request->has('status');

        LibraryResource::create($validated);

        return redirect()->route('library.admin.resources')
            ->with('success', 'Recurso agregado exitosamente.');
    }

    /**
     * Admin: Show edit form for a resource.
     */
    public function editResource(LibraryResource $resource): View
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return view('library.edit-resource', compact('resource'));
    }

        /**
     * Admin: Update a library resource.
     */
    public function updateResource(Request $request, LibraryResource $resource): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:library_resources,isbn,' . $resource->id,
            'resource_type' => 'required|in:book,magazine,digital,multimedia',
            'description' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'total_copies' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        // Calculate active loans (approved and active loans)
        $activeLoans = $resource->loans()->whereIn('status', ['active', 'approved'])->count();

        // Auto-calculate available copies based on total copies and active loans
        $validated['available_copies'] = $validated['total_copies'] - $activeLoans;

        // Ensure new total copies can accommodate current loans
        if ($validated['total_copies'] < $activeLoans) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['total_copies' => "No puedes reducir las copias totales por debajo de {$activeLoans} (copias actualmente prestadas)."]);
        }

        // Set status to true if checked, false if not
        $validated['status'] = $request->has('status');

        $resource->update($validated);

        return redirect()->route('library.admin.resources')
            ->with('success', 'Recurso actualizado exitosamente.');
    }

    /**
     * Admin: Delete a library resource.
     */
    public function deleteResource(LibraryResource $resource): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Check if resource has active loans
        if ($resource->loans()->whereIn('status', ['active', 'approved'])->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el recurso porque tiene préstamos activos.');
        }

        $resourceTitle = $resource->title;
        $resource->delete();

        return redirect()->route('library.admin.resources')
            ->with('success', "Recurso \"{$resourceTitle}\" eliminado exitosamente.");
    }
}
