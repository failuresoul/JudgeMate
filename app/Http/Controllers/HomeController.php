<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Total submissions
        $totalSubmissions = $user->submissions()->count();

        // Pending submissions
        $pendingCount = $user->submissions()->where('status', 'pending')->count();

        // Accepted submissions count (unique problems solved)
        $solvedProblemIds = $user->submissions()
            ->where('status', 'accepted')
            ->distinct()
            ->pluck('problem_id');
            
        $solvedCount = $solvedProblemIds->count();

        // Calculate Acceptance Rate
        $acceptanceRate = $totalSubmissions > 0 
            ? round(($user->submissions()->where('status', 'accepted')->count() / $totalSubmissions) * 100, 1) 
            : 0;

        // Recent submissions (last 5)
        $recentSubmissions = $user->submissions()
            ->with('problem')
            ->latest()
            ->take(5)
            ->get();

        // Solved problems by difficulty breakdown
        $solvedEasy = \App\Models\Problem::whereIn('id', $solvedProblemIds)->where('difficulty', 'easy')->count();
        $solvedMedium = \App\Models\Problem::whereIn('id', $solvedProblemIds)->where('difficulty', 'medium')->count();
        $solvedHard = \App\Models\Problem::whereIn('id', $solvedProblemIds)->where('difficulty', 'hard')->count();

        $totalEasy = \App\Models\Problem::where('difficulty', 'easy')->count();
        $totalMedium = \App\Models\Problem::where('difficulty', 'medium')->count();
        $totalHard = \App\Models\Problem::where('difficulty', 'hard')->count();

        // Recommended problems (problems not solved yet, published, limit 4)
        $recommendedProblems = \App\Models\Problem::where('is_published', true)
            ->whereNotIn('id', $solvedProblemIds)
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact(
            'totalSubmissions',
            'pendingCount',
            'solvedCount',
            'acceptanceRate',
            'recentSubmissions',
            'solvedEasy',
            'solvedMedium',
            'solvedHard',
            'totalEasy',
            'totalMedium',
            'totalHard',
            'recommendedProblems'
        ));
    }
}
