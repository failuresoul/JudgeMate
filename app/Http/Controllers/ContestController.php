<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Problem;
use App\Services\ExternalContestService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContestController extends Controller implements HasMiddleware
{
    protected $externalContestService;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('role:ProblemSetter', only: ['create', 'store']),
            new Middleware('role:ProblemSetter|Admin', only: ['edit', 'update', 'destroy']),
        ];
    }

    /**
     * Inject ExternalContestService
     */
    public function __construct(ExternalContestService $externalContestService)
    {
        $this->externalContestService = $externalContestService;
    }

    /**
     * Display a listing of local contests.
     */
    public function index(): View
    {
        $query = Contest::with(['participants:id'])->withCount(['problems', 'participants']);

        // Contestants can only see approved contests.
        // Judges (ProblemSetter) can see approved contests and their own.
        // Admins can see all contests.
        if (auth()->user()->hasRole('Contestant')) {
            $query->where('is_approved', true);
        } elseif (auth()->user()->hasRole('ProblemSetter')) {
            $query->where(function ($q) {
                $q->where('is_approved', true)
                  ->orWhere('created_by', auth()->id());
            });
        }

        $contests = $query->latest()->paginate(10);
        $externalContests = $this->externalContestService->getUpcomingContests();

        return view('contests.index', compact('contests', 'externalContests'));
    }

    /**
     * Show the form for creating a new contest.
     */
    public function create(): View
    {
        $problems = Problem::orderBy('title')->get();
        return view('contests.create', compact('problems'));
    }

    /**
     * Store a newly created contest in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'problems' => ['nullable', 'array'],
            'problems.*' => ['exists:problems,id'],
        ]);

        $contest = Contest::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id(),
            'is_approved' => false, // starts as pending approval
        ]);

        if (!empty($validated['problems'])) {
            $syncData = [];
            foreach ($validated['problems'] as $index => $problemId) {
                $syncData[$problemId] = ['label' => $this->getLabelForIndex($index)];
            }
            $contest->problems()->sync($syncData);
        }

        return redirect()->route('contests.index')
            ->with('success', 'Contest created successfully and is pending admin approval.');
    }

    /**
     * Display the specified contest details.
     */
    public function show(Contest $contest): View
    {
        // Check access for unapproved contests
        if (!$contest->is_approved && !auth()->user()->hasRole('Admin') && auth()->id() !== $contest->created_by) {
            abort(403, 'This contest is pending approval.');
        }

        $contest->load(['problems.creator', 'participants']);
        return view('contests.show', compact('contest'));
    }

    /**
     * Show the form for editing the specified contest.
     */
    public function edit(Contest $contest): View
    {
        if (auth()->id() !== $contest->created_by && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $problems = Problem::orderBy('title')->get();
        $selectedProblems = $contest->problems()->pluck('problems.id')->toArray();
        return view('contests.edit', compact('contest', 'problems', 'selectedProblems'));
    }

    /**
     * Update the specified contest in storage.
     */
    public function update(Request $request, Contest $contest): RedirectResponse
    {
        if (auth()->id() !== $contest->created_by && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
            'problems' => ['nullable', 'array'],
            'problems.*' => ['exists:problems,id'],
        ]);

        $contest->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->has('is_active'),
        ]);

        $syncData = [];
        if (!empty($validated['problems'])) {
            foreach ($validated['problems'] as $index => $problemId) {
                $syncData[$problemId] = ['label' => $this->getLabelForIndex($index)];
            }
        }
        $contest->problems()->sync($syncData);

        return redirect()->route('contests.index')
            ->with('success', 'Contest updated successfully.');
    }

    /**
     * Remove the specified contest from storage.
     */
    public function destroy(Contest $contest): RedirectResponse
    {
        if (auth()->id() !== $contest->created_by && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $contest->delete();
        return redirect()->route('contests.index')
            ->with('success', 'Contest deleted successfully.');
    }

    /**
     * Approve a contest (Admin only).
     */
    public function approve(Request $request, Contest $contest): RedirectResponse
    {
        $contest->update(['is_approved' => true]);
        return redirect()->route('contests.index')
            ->with('success', 'Contest approved successfully.');
    }

    /**
     * Register/join a contest (Contestant only).
     */
    public function register(Request $request, Contest $contest): RedirectResponse
    {
        if (!$contest->is_approved) {
            abort(403, 'This contest is not approved yet.');
        }

        // Attach participant if not already registered
        if (!$contest->participants()->where('user_id', auth()->id())->exists()) {
            $contest->participants()->attach(auth()->id(), [
                'joined_at' => now(),
            ]);
        }

        return redirect()->route('contests.show', $contest)
            ->with('success', 'You have successfully registered for ' . $contest->title);
    }

    /**
     * Convert numeric index into alphabetical label sequence (A, B, C... Z, AA, AB...).
     */
    private function getLabelForIndex(int $index): string
    {
        $label = '';
        while ($index >= 0) {
            $label = chr(($index % 26) + 65) . $label;
            $index = floor($index / 26) - 1;
        }
        return $label;
    }
}
