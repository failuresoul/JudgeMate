<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Submission;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Display the global leaderboard.
     */
    public function index(Request $request): View
    {
        $users = User::select('users.*')
            ->selectSub(
                Submission::selectRaw('count(distinct problem_id)')
                    ->whereColumn('user_id', 'users.id')
                    ->where('status', 'accepted'),
                'solved_count'
            )
            ->with('badges')
            ->orderByDesc('solved_count')
            ->paginate(20);

        // Fetch ongoing contests
        $ongoingContests = Contest::where('is_approved', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->get();

        // Fetch past/ended contests
        $pastContests = Contest::where('is_approved', true)
            ->where('ends_at', '<', now())
            ->orderBy('ends_at', 'desc')
            ->get();

        return view('leaderboard.index', compact('users', 'ongoingContests', 'pastContests'));
    }
}
