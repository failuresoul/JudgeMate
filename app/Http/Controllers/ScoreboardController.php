<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScoreboardController extends Controller
{
    /**
     * Display the scoreboard HTML page.
     */
    public function show(Contest $contest): View
    {
        if (!$contest->is_approved) {
            abort(403, 'This contest is not approved yet.');
        }

        return view('contests.scoreboard', compact('contest'));
    }

    /**
     * Fetch raw scoreboard statistics as JSON (ICPC-style).
     */
    public function data(Contest $contest): JsonResponse
    {
        if (!$contest->is_approved) {
            return response()->json(['error' => 'Contest not approved.'], 403);
        }

        // Eager load participants and problems
        $contest->load(['participants', 'problems']);
        
        $scoreboard = [];
        $startsAt = $contest->starts_at;

        foreach ($contest->participants as $participant) {
            // Get all submissions for this contest by the participant, in chronological order
            $submissions = Submission::where('contest_id', $contest->id)
                ->where('user_id', $participant->id)
                ->orderBy('submitted_at', 'asc')
                ->get();

            $problemsSolved = 0;
            $totalPenalty = 0;
            $problemDetails = [];

            // Group submissions by problem
            $submissionsByProblem = $submissions->groupBy('problem_id');

            foreach ($contest->problems as $problem) {
                $problemSubmissions = $submissionsByProblem->get($problem->id, collect());
                
                // Find the first accepted submission
                $firstAccepted = $problemSubmissions->first(function ($sub) {
                    return $sub->status === 'accepted';
                });

                if ($firstAccepted) {
                    $problemsSolved++;

                    // Count wrong attempts before the first accepted submission
                    // A wrong attempt is any evaluation status other than 'accepted' (and not 'pending')
                    $wrongAttemptsCount = $problemSubmissions->filter(function ($sub) use ($firstAccepted) {
                        return $sub->id !== $firstAccepted->id 
                            && $sub->submitted_at->lt($firstAccepted->submitted_at)
                            && in_array($sub->status, ['wrong_answer', 'time_limit_exceeded', 'compilation_error']);
                    })->count();

                    // Minutes from contest start to the first accepted submission
                    $minutesFromStart = max(0, $startsAt->diffInMinutes($firstAccepted->submitted_at));
                    
                    // Penalty = (wrong attempts * 20) + minutes from start
                    $problemPenalty = ($wrongAttemptsCount * 20) + $minutesFromStart;
                    $totalPenalty += $problemPenalty;

                    $problemDetails[$problem->id] = [
                        'solved' => true,
                        'wrong_attempts' => $wrongAttemptsCount,
                        'minutes' => $minutesFromStart,
                        'label' => $problem->pivot->label ?? '?',
                    ];
                } else {
                    // Count wrong attempts for this unsolved problem
                    $wrongAttemptsCount = $problemSubmissions->filter(function ($sub) {
                        return in_array($sub->status, ['wrong_answer', 'time_limit_exceeded', 'compilation_error']);
                    })->count();

                    $problemDetails[$problem->id] = [
                        'solved' => false,
                        'wrong_attempts' => $wrongAttemptsCount,
                        'label' => $problem->pivot->label ?? '?',
                    ];
                }
            }

            $scoreboard[] = [
                'user_id' => $participant->id,
                'name' => $participant->name,
                'solve_count' => $problemsSolved,
                'total_penalty' => $totalPenalty,
                'problems' => $problemDetails,
            ];
        }

        // Sort results: solve_count descending, then total_penalty ascending, then name alphabetically
        usort($scoreboard, function ($a, $b) {
            if ($a['solve_count'] !== $b['solve_count']) {
                return $b['solve_count'] <=> $a['solve_count'];
            }
            if ($a['total_penalty'] !== $b['total_penalty']) {
                return $a['total_penalty'] <=> $b['total_penalty'];
            }
            return strcmp($a['name'], $b['name']);
        });

        return response()->json([
            'contest' => [
                'id' => $contest->id,
                'title' => $contest->title,
            ],
            'problems' => $contest->problems->map(function ($p) {
                return [
                    'id' => $p->id,
                    'label' => $p->pivot->label ?? '?',
                    'title' => $p->title,
                ];
            }),
            'rows' => $scoreboard,
        ]);
    }
}
