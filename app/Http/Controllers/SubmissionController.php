<?php

namespace App\Http\Controllers;

use App\Jobs\JudgeSubmission;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the user's submissions.
     */
    public function index(): View
    {
        $submissions = auth()->user()->submissions()
            ->with('problem')
            ->latest()
            ->paginate(15);

        return view('submissions.index', compact('submissions'));
    }

    /**
     * Show the code submission form for the specified problem.
     */
    public function create(Problem $problem): View
    {
        return view('problems.submit', compact('problem'));
    }

    /**
     * Store a newly created submission in storage.
     */
    public function store(Request $request, Problem $problem): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['required', 'string', 'in:cpp,python,java'],
            'code'     => ['required', 'string'],
        ]);

        $submission = Submission::create([
            'user_id'      => auth()->id(),
            'problem_id'   => $problem->id,
            'code'         => $validated['code'],
            'language'     => $validated['language'],
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        // Dispatch the job carrying the new submission
        JudgeSubmission::dispatch($submission);

        return redirect()->route('problems.show', $problem)
            ->with('success', 'Your code has been submitted successfully and is currently pending evaluation.');
    }

    /**
     * Get the status of a specific submission (JSON endpoint for polling).
     */
    public function status(Submission $submission)
    {
        if ($submission->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status'          => $submission->status,
            'verdict_message' => $submission->verdict_message,
        ]);
    }
}
