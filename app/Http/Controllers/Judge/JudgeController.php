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
        // Summary stats — using DB counts (extend when models exist)
        $stats = [
            'problems_created'  => 0,   // TODO: Problem::where('created_by', auth()->id())->count()
            'pending_review'    => 0,   // TODO: Submission::where('status','pending')->count()
            'accepted_today'    => 0,   // TODO: Submission::whereDate('created_at', today())->where('verdict','AC')->count()
            'total_contestants' => User::role('Contestant')->where('status','approved')->count(),
        ];

        return view('judge.dashboard', compact('stats'));
    }
}
