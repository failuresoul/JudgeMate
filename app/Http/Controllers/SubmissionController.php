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
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            // Admin sees all submissions
            $submissions = Submission::with(['problem', 'user'])
                ->latest()
                ->paginate(15);
        } elseif ($user->hasRole('ProblemSetter')) {
            // Judge sees submissions of the problems they created
            $submissions = Submission::whereHas('problem', function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->with(['problem', 'user'])
            ->latest()
            ->paginate(15);
        } else {
            // Contestant sees only their own submissions
            $submissions = $user->submissions()
                ->with('problem')
                ->latest()
                ->paginate(15);
        }

        return view('submissions.index', compact('submissions'));
    }

    /**
     * Show the code submission form for the specified problem.
     */
    public function create(Problem $problem): View
    {
        $now = now();
        $contests = $problem->contests;
        if ($contests->isNotEmpty()) {
            $hasStartedContest = $contests->contains(function ($contest) use ($now) {
                return $now->gte($contest->starts_at);
            });
            if (!$hasStartedContest && !auth()->user()->hasRole('Admin') && auth()->id() !== $problem->created_by) {
                abort(403, 'This problem is part of an upcoming contest and cannot be solved yet.');
            }
        }

        return view('problems.submit', compact('problem'));
    }

    /**
     * Store a newly created submission in storage.
     */
    public function store(Request $request, Problem $problem): RedirectResponse
    {
        $validated = $request->validate([
            'language'   => ['required', 'string', 'in:cpp,python,java'],
            'code'       => ['required', 'string'],
            'contest_id' => ['nullable', 'exists:contests,id'],
        ]);

        $contestId = $validated['contest_id'] ?? null;

        // Auto-detect active contest if not explicitly passed
        if (!$contestId) {
            $now = now();
            // Find an active approved contest containing this problem that the user is participating in
            $activeContest = \App\Models\Contest::where('starts_at', '<=', $now)
                ->where('ends_at', '>=', $now)
                ->where('is_approved', true)
                ->whereHas('problems', function ($query) use ($problem) {
                    $query->where('problems.id', $problem->id);
                })
                ->whereHas('participants', function ($query) {
                    $query->where('users.id', auth()->id());
                })
                ->first();

            if ($activeContest) {
                $contestId = $activeContest->id;
            }
        } else {
            // Explicitly passed contest_id validation
            $contest = \App\Models\Contest::findOrFail($contestId);

            // Verify problem is in the contest
            if (!$contest->problems()->where('problems.id', $problem->id)->exists()) {
                return back()->withErrors(['contest_id' => 'This problem is not part of the specified contest.']);
            }

            // Verify the contest window is currently active (falls between starts_at and ends_at)
            $now = now();
            if ($now->lt($contest->starts_at) || $now->gt($contest->ends_at)) {
                return back()->withErrors(['contest_id' => 'The contest is either closed or has not started yet. Submission rejected.']);
            }
        }

        $submission = Submission::create([
            'user_id'      => auth()->id(),
            'problem_id'   => $problem->id,
            'contest_id'   => $contestId,
            'code'         => $validated['code'],
            'language'     => $validated['language'],
            'status'       => 'pending',
            'submitted_at' => now(),
        ]);

        // Dispatch the job carrying the new submission
        JudgeSubmission::dispatch($submission);

        if ($contestId) {
            return redirect()->route('contests.show', $contestId)
                ->with('success', 'Your code has been submitted successfully for the contest and is pending evaluation.');
        }

        return redirect()->route('problems.show', $problem)
            ->with('success', 'Your code has been submitted successfully and is currently pending evaluation.');
    }

    /**
     * Get the status of a specific submission (JSON endpoint for polling).
     */
    public function status(Submission $submission)
    {
        $user = auth()->user();

        $isOwner = $submission->user_id === $user->id;
        $isAdmin = $user->hasRole('Admin');
        $isProblemOwner = $user->hasRole('ProblemSetter') && $submission->problem->created_by === $user->id;

        if (!$isOwner && !$isAdmin && !$isProblemOwner) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status'          => $submission->status,
            'verdict_message' => $submission->verdict_message,
        ]);
    }
}
