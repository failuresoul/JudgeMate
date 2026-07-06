<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    /**
     * List all users grouped by status.
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'pending'); // pending | approved | rejected | all

        $query = User::with('roles')->latest();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $users = $query->get();

        $counts = [
            'pending'  => User::where('status', 'pending')->count(),
            'approved' => User::where('status', 'approved')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
            'all'      => User::count(),
        ];

        return view('admin.users.index', compact('users', 'filter', 'counts'));
    }

    /**
     * Approve a user registration.
     */
    public function approve(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot modify your own status.");
        }

        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot modify Admin status.");
        }

        $user->update([
            'status'          => 'approved',
            'rejected_reason' => null,
        ]);

        return redirect()->route('admin.users.index', ['filter' => 'pending'])
            ->with('success', "✅ {$user->name} has been approved.");
    }

    /**
     * Reject a user registration with an optional reason.
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot modify your own status.");
        }

        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot modify Admin status.");
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'status'          => 'rejected',
            'rejected_reason' => $request->reason,
        ]);

        return redirect()->route('admin.users.index', ['filter' => 'pending'])
            ->with('success', "❌ {$user->name}'s registration has been rejected.");
    }
}
