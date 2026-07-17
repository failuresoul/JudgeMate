<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of approved blogs (Inspiration feed).
     */
    public function index(): View
    {
        $blogs = Blog::with('author')
            ->where('is_approved', true)
            ->latest()
            ->get();
            
        return view('blogs.index', compact('blogs'));
    }
}
