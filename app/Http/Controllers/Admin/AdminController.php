<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function dashboard(Request $request): View
    {
        // Real counts for Users
        $totalUsers = User::count();
        $pendingUsers = User::where('status', 'pending')->count();

        // Database counts
        $totalProblems = \App\Models\Problem::count();
        $totalContests = \App\Models\Contest::count();
        $totalSubmissions = \App\Models\Submission::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingUsers',
            'totalProblems',
            'totalContests',
            'totalSubmissions'
        ));
    }
}
