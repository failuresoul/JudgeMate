<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Notifications\BlogApproved;
use Illuminate\Support\Facades\Notification;

class BlogController extends Controller
{
    /**
     * Display a listing of all blogs (pending and approved).
     */
    public function index(): View
    {
        $pendingBlogs = Blog::with('author')->where('is_approved', false)->latest()->get();
        $approvedBlogs = Blog::with('author')->where('is_approved', true)->latest()->get();
        
        return view('admin.blogs.index', compact('pendingBlogs', 'approvedBlogs'));
    }

    /**
     * Update the approval status of a blog.
     */
    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($validated['action'] === 'approve') {
            $blog->update(['is_approved' => true]);
            
            $contestants = User::role('Contestant')->get();
            Notification::send($contestants, new BlogApproved($blog));

            return back()->with('status', 'Blog approved successfully. It is now visible to contestants.');
        } else {
            // Delete the image from storage if it exists
            if ($blog->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($blog->image_path);
            }
            // Delete the blog
            $blog->delete();
            return back()->with('status', 'Blog rejected and deleted successfully.');
        }
    }
}
