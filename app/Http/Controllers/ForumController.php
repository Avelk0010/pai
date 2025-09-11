<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ForumController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the forum main page with categories.
     */
    public function index(): View
    {
        $categories = ForumCategory::with(['approvedPosts' => function ($query) {
            $query->with('author')->latest()->take(5);
        }])
        ->ordered()
        ->get();

        // Get recent posts across all categories
        $recentPosts = ForumPost::with(['author', 'category'])
            ->approved()
            ->latest()
            ->take(10)
            ->get();

        // Get statistics
        $stats = [
            'total_posts' => ForumPost::approved()->count(),
            'total_comments' => ForumComment::approved()->count(),
            'total_categories' => ForumCategory::count(),
            'pending_posts' => ForumPost::pending()->count(),
        ];

        return view('forum.index', compact('categories', 'recentPosts', 'stats'));
    }

    /**
     * Display posts in a specific category.
     */
    public function category(ForumCategory $category): View
    {
        $posts = $category->approvedPosts()
            ->with(['author', 'comments'])
            ->withCount('approvedComments')
            ->latest()
            ->paginate(15);

        return view('forum.category', compact('category', 'posts'));
    }

    /**
     * Display a specific forum post with comments.
     */
    public function post(ForumPost $post): View
    {
        // Check if post is approved, or if user is admin, or if user is the author (can view their own pending posts)
        if (!$post->is_approved && Auth::user()->role !== 'admin' && Auth::id() !== $post->author_id) {
            abort(404, 'Publicación no encontrada.');
        }

        // Increment view count only for approved posts
        if ($post->is_approved) {
            $post->incrementViews();
        }

        $post->load([
            'author',
            'category',
            'approvedComments' => function ($query) {
                $query->with('author')->oldest();
            }
        ]);

        return view('forum.post', compact('post'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function createPost(ForumCategory $category = null): View
    {
        $categories = ForumCategory::ordered()->get();
        
        return view('forum.create-post', compact('category', 'categories'));
    }

    /**
     * Store a newly created post.
     */
    public function storePost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:forum_categories,id'
        ]);

        $validated['author_id'] = Auth::id();
        $validated['is_approved'] = false; // Posts need approval
        $validated['views'] = 0;

        $post = ForumPost::create($validated);

        return redirect()->route('forum.index')
            ->with('success', 'Publicación creada exitosamente. Está pendiente de aprobación.');
    }

    /**
     * Show the form for editing a post.
     */
    public function editPost(ForumPost $post): View
    {
        // Check permissions - users can edit their own posts, admins can edit any
        if (Auth::id() !== $post->author_id && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para editar esta publicación.');
        }

        // Load the category relationship
        $post->load('category');
        
        $categories = ForumCategory::all();

        return view('forum.edit-post', compact('post', 'categories'));
    }

    /**
     * Update a forum post.
     */
    public function updatePost(Request $request, ForumPost $post): RedirectResponse
    {
        // Check permissions - users can edit their own posts, admins can edit any
        if (Auth::id() !== $post->author_id && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para editar esta publicación.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean'
        ]);

        // Only admins can pin posts
        $validated['is_pinned'] = $request->has('is_pinned') && Auth::user()->role === 'admin';
        
        // When a post is edited, it may need re-approval (except for admins)
        if (Auth::user()->role !== 'admin') {
            $validated['is_approved'] = false;
            $validated['approved_by'] = null;
            $validated['approved_at'] = null;
        }

        $post->update($validated);

        $message = Auth::user()->role === 'admin' 
            ? 'Publicación actualizada exitosamente.'
            : 'Publicación actualizada exitosamente. Está pendiente de aprobación.';

        return redirect()->route('forum.post', $post)
            ->with('success', $message);
    }

    /**
     * Delete a forum post.
     */
    public function deletePost(ForumPost $post): RedirectResponse
    {
        // Check permissions - users can delete their own posts, admins can delete any
        if (Auth::id() !== $post->author_id && Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para eliminar esta publicación.');
        }

        $category = $post->category;
        $postTitle = $post->title;
        
        // Delete all related comments first
        $post->comments()->delete();
        
        // Delete the post
        $post->delete();

        // Redirect based on referer - if coming from user activity, go back there
        $referer = request()->headers->get('referer');
        if ($referer && str_contains($referer, '/my-activity')) {
            return redirect()->route('forum.user-activity')
                ->with('success', "Publicación \"$postTitle\" eliminada exitosamente.");
        }

        return redirect()->route('forum.category', $category)
            ->with('success', "Publicación \"$postTitle\" eliminada exitosamente.");
    }

    /**
     * Store a new comment on a post.
     */
    public function storeComment(Request $request, ForumPost $post): RedirectResponse
    {
        if (!$post->is_approved) {
            abort(404, 'Publicación no encontrada.');
        }

        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $validated['author_id'] = Auth::id();
        $validated['post_id'] = $post->id;
        $validated['is_approved'] = false; // Comments need approval

        ForumComment::create($validated);

        return redirect()->route('forum.post', $post)
            ->with('success', 'Comentario agregado exitosamente. Está pendiente de aprobación.');
    }

    /**
     * Search forum posts.
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'q' => 'required|string|min:3|max:255'
        ]);

        $query = $validated['q'];

        $posts = ForumPost::with(['author', 'category'])
            ->approved()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(10);

        return view('forum.search', compact('posts', 'query'));
    }

    /**
     * Display user's forum activity.
     */
    public function userActivity(): View
    {
        $user = Auth::user();
        
        $posts = ForumPost::with(['category'])
            ->where('author_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'posts_page');

        $comments = ForumComment::with(['post.category'])
            ->where('author_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'comments_page');

        return view('forum.user-activity', compact('posts', 'comments'));
    }

    // ADMIN MODERATION METHODS

    /**
     * Display moderation dashboard (admin only).
     */
    public function moderationDashboard(): View
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para acceder al panel de moderación.');
        }

        $pendingPosts = ForumPost::with(['author', 'category'])
            ->pending()
            ->latest()
            ->paginate(10, ['*'], 'posts_page');

        $pendingComments = ForumComment::with(['author', 'post'])
            ->pending()
            ->latest()
            ->paginate(10, ['*'], 'comments_page');

        $stats = [
            'pending_posts' => ForumPost::pending()->count(),
            'pending_comments' => ForumComment::pending()->count(),
            'approved_posts' => ForumPost::approved()->count(),
            'approved_comments' => ForumComment::approved()->count(),
        ];

        return view('forum.moderation.dashboard', compact('pendingPosts', 'pendingComments', 'stats'));
    }

    /**
     * Approve a forum post (admin only).
     */
    public function approvePost(ForumPost $post): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para moderar contenido.');
        }

        $post->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Publicación aprobada exitosamente.');
    }

    /**
     * Reject a forum post (admin only).
     */
    public function rejectPost(ForumPost $post): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para moderar contenido.');
        }

        $post->delete();

        return redirect()->back()
            ->with('success', 'Publicación rechazada y eliminada.');
    }

    /**
     * Approve a forum comment (admin only).
     */
    public function approveComment(ForumComment $comment): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para moderar contenido.');
        }

        $comment->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Comentario aprobado exitosamente.');
    }

    /**
     * Reject a forum comment (admin only).
     */
    public function rejectComment(ForumComment $comment): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'No tienes permisos para moderar contenido.');
        }

        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comentario rechazado y eliminado.');
    }
}
