<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    /**
     * Handle an incoming request.
     *
     * Blocks authenticated users whose accounts are not yet approved by an Admin.
     * - pending  → logged out, redirected to pending page with info message
     * - rejected → logged out, redirected to pending page with rejection message + reason
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isRejected()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('auth.pending')
                    ->with('rejected', true)
                    ->with('reason', $user->rejected_reason ?? 'No reason provided.');
            }

            if ($user->isPending()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('auth.pending');
            }
        }

        return $next($request);
    }
}
