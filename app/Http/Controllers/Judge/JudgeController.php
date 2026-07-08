<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\User;

class JudgeController extends Controller
{
    /**
     * Display the Judge dashboard.
     */
    public function dashboard()
    {
        $judgeId = auth()->id();
        $myProblems = \App\Models\Problem::where('created_by', $judgeId)->latest()->take(5)->get();

        $submissionsQuery = \App\Models\Submission::whereHas('problem', function ($query) use ($judgeId) {
            $query->where('created_by', $judgeId);
        });

        $totalSubmissions = (clone $submissionsQuery)->count();

        $stats = [
            'problems_created'  => \App\Models\Problem::where('created_by', $judgeId)->where('is_published', true)->count(),
            'pending_review'    => (clone $submissionsQuery)->where('status', 'pending')->count(),
            'accepted_today'    => (clone $submissionsQuery)->where('status', 'accepted')->whereDate('submitted_at', today())->count(),
            'total_contestants' => User::role('Contestant')->where('status', 'approved')->count(),
            'total_submissions' => $totalSubmissions,
        ];

        // Fetch recent submissions for judge's problems (limit 5)
        $submissions = (clone $submissionsQuery)
            ->with(['problem', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Verdict distribution counts
        $acCount  = (clone $submissionsQuery)->where('status', 'accepted')->count();
        $waCount  = (clone $submissionsQuery)->where('status', 'wrong_answer')->count();
        $tleCount = (clone $submissionsQuery)->where('status', 'time_limit_exceeded')->count();
        $ceCount  = (clone $submissionsQuery)->where('status', 'compilation_error')->count();

        $totalCountVal = $acCount + $waCount + $tleCount + $ceCount;

        $verdicts = [
            ['label' => 'Accepted', 'short' => 'AC', 'val' => $acCount, 'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.15)', 'pct' => $totalCountVal > 0 ? round(($acCount / $totalCountVal) * 100) : 0],
            ['label' => 'Wrong Answer', 'short' => 'WA', 'val' => $waCount, 'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.12)', 'pct' => $totalCountVal > 0 ? round(($waCount / $totalCountVal) * 100) : 0],
            ['label' => 'Time Limit', 'short' => 'TLE', 'val' => $tleCount, 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.12)', 'pct' => $totalCountVal > 0 ? round(($tleCount / $totalCountVal) * 100) : 0],
            ['label' => 'Compile Error', 'short' => 'CE', 'val' => $ceCount, 'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.12)', 'pct' => $totalCountVal > 0 ? round(($ceCount / $totalCountVal) * 100) : 0],
        ];

        return view('judge.dashboard', compact('stats', 'myProblems', 'submissions', 'verdicts'));
    }
}
