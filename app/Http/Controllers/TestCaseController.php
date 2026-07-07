<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\TestCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

class TestCaseController extends Controller
{
    /**
     * Display a listing of problems to manage test cases.
     */
    public function index(Request $request): View
    {
        $problems = Problem::where('created_by', auth()->id())->latest()->paginate(10);
        return view('judge.test-cases.index', compact('problems'));
    }

    /**
     * Show the page to manage a problem's test cases.
     */
    public function show(Problem $problem): View
    {
        // Enforce that the judge can only manage test cases for their own problems
        if ($problem->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $problem->load('testCases');
        return view('judge.test-cases.show', compact('problem'));
    }
    /**
     * Store a newly created test case in storage.
     */
    public function store(Request $request, Problem $problem): RedirectResponse
    {
        $validated = $request->validate([
            'input'           => ['required', 'string'],
            'expected_output' => ['required', 'string'],
            'is_hidden'       => ['nullable', 'boolean'],
        ]);

        $validated['is_hidden'] = $request->has('is_hidden');

        $problem->testCases()->create($validated);

        return redirect()->back()->with('success', 'Test case added successfully.');
    }

    /**
     * Remove the specified test case from storage.
     */
    public function destroy(TestCase $testCase): RedirectResponse
    {
        $testCase->delete();

        return redirect()->back()->with('success', 'Test case deleted successfully.');
    }
}
