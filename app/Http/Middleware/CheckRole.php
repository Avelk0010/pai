<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->status) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        // Check if user has one of the required roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
