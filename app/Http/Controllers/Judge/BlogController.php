<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BlogController extends Controller
{
    /**
     * Display a listing of the judge's blogs.
     */
    public function index(): View
    {
        $blogs = auth()->user()->blogs()->latest()->get();
        
        return view('judge.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create(): View
    {
        return view('judge.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // up to 2MB for GIFs/Images
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        auth()->user()->blogs()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'is_approved' => false, // Requires admin approval
        ]);

        return redirect()->route('judge.blogs.index')
            ->with('status', 'Blog submitted successfully! It is now pending admin approval.');
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        // Ensure the blog belongs to the current user
        if ($blog->user_id !== auth()->id()) {
            abort(403);
        }

        if ($blog->image_path) {
            Storage::disk('public')->delete($blog->image_path);
        }

        $blog->delete();

        return redirect()->route('judge.blogs.index')
            ->with('status', 'Blog deleted successfully.');
    }
}
