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
        $myProblems = \App\Models\Problem::where('created_by', auth()->id())->latest()->take(5)->get();

        $stats = [
            'problems_created'  => \App\Models\Problem::where('created_by', auth()->id())->where('is_published', true)->count(),
            'pending_review'    => 0,
            'accepted_today'    => 0,
            'total_contestants' => User::role('Contestant')->where('status','approved')->count(),
        ];

        return view('judge.dashboard', compact('stats', 'myProblems'));
    }
}
