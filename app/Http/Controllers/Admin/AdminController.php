<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the Admin Analytics Dashboard.
     *
     * All metrics are computed live from the database via Eloquent aggregates.
     * Route is protected by ['auth', 'approved', 'role:Admin'] middleware
     * (see routes/web.php — the admin prefix group).
     */
    public function dashboard(Request $request): View
    {
        // ── User Metrics ──────────────────────────────────────────────────────
        $totalUsers   = User::count();
        $pendingUsers = User::where('status', 'pending')->count();

        // ── Problem / Contest Metrics ─────────────────────────────────────────
        $totalProblems    = Problem::count();
        $totalContests    = Contest::count();   // every contest row = one contest held

        // ── Submission Totals ─────────────────────────────────────────────────
        $totalSubmissions = Submission::count();

        // ── Verdict Breakdown ─────────────────────────────────────────────────
        // Counts per verdict status in a single GROUP BY query; defaults to 0
        // for any verdict that has no rows yet.
        $verdictRows = Submission::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');   // Collection keyed by verdict string

        $verdictCounts = [
            'accepted'             => (int) ($verdictRows['accepted']             ?? 0),
            'wrong_answer'         => (int) ($verdictRows['wrong_answer']         ?? 0),
            'time_limit_exceeded'  => (int) ($verdictRows['time_limit_exceeded']  ?? 0),
            'compilation_error'    => (int) ($verdictRows['compilation_error']    ?? 0),
        ];

        // ── Top 5 Most-Attempted Problems ─────────────────────────────────────
        // Joins submissions → problems so we can surface the problem title.
        // "Attempted" = any submission regardless of verdict.
        $topProblems = Problem::select('problems.id', 'problems.title', 'problems.slug')
            ->withCount('submissions')          // adds submissions_count column
            ->orderByDesc('submissions_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingUsers',
            'totalProblems',
            'totalContests',
            'totalSubmissions',
            'verdictCounts',
            'topProblems',
        ));
    }
}
