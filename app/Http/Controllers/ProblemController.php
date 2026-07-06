<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use Illuminate\Http\Request;

use App\Http\Requests\ProblemRequest;
use Illuminate\Support\Str;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $problems = Problem::with('creator')->latest()->paginate(10);
        return view('problems.index', compact('problems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = \App\Models\Tag::orderBy('name')->get();
        return view('problems.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProblemRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        if (!auth()->user()->hasRole('Admin')) {
            $data['is_published'] = false;
        } else {
            $data['is_published'] = $request->has('is_published');
        }
        
        $data['created_by'] = auth()->id();

        $problem = Problem::create($data);
        $problem->tags()->sync($request->input('tags', []));

        return redirect()->route('problems.index')
            ->with('success', 'Problem created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Problem $problem)
    {
        $problem->load(['testCases', 'tags']);
        return view('problems.show', compact('problem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Problem $problem)
    {
        $tags = \App\Models\Tag::orderBy('name')->get();
        $problem->load('tags');
        return view('problems.edit', compact('problem', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProblemRequest $request, Problem $problem)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        if (!auth()->user()->hasRole('Admin')) {
            $data['is_published'] = $problem->is_published;
        } else {
            $data['is_published'] = $request->has('is_published');
        }

        $problem->update($data);
        $problem->tags()->sync($request->input('tags', []));

        return redirect()->route('problems.index')
            ->with('success', 'Problem updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Problem $problem)
    {
        $problem->delete();

        return redirect()->route('problems.index')
            ->with('success', 'Problem deleted successfully.');
    }
}
