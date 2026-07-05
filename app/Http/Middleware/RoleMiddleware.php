<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *   ->middleware('role:Admin')
     *   ->middleware('role:Admin,ProblemSetter')   // any one of these roles is accepted
     *
     * @param  string  $roles  Comma-separated list of allowed role names.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Unauthenticated users are sent to the login page
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check whether the user holds at least one of the required roles
        if (! $user->hasAnyRole($roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized – you do not have permission to access this page.');
        }

        return $next($request);
    }
}
