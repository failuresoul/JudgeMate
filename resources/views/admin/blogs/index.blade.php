@extends('layouts.admin')

@section('title', 'Review Blogs - JudgeMate')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Review Blogs</h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Review, approve, and manage blog posts written by Judges.</p>
    </div>

    @if (session('status'))
        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-500/10 p-4 border border-emerald-200 dark:border-emerald-500/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Pending Blogs --}}
    <div class="space-y-4">
        <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Pending Approval</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($pendingBlogs as $blog)
                <div class="flex flex-col sm:flex-row gap-6 rounded-2xl bg-white dark:bg-slate-900 border border-amber-200 dark:border-amber-900/50 shadow-sm overflow-hidden p-6 relative">
                    @if($blog->image_path)
                        <div class="sm:w-1/3 shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 h-48 sm:h-auto min-h-[200px]">
                            <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-1 text-xs font-medium text-amber-700 dark:text-amber-400 ring-1 ring-inset ring-amber-600/20 dark:ring-amber-500/20">Pending Review</span>
                            <span class="text-xs text-slate-500">By {{ $blog->author->name }} • {{ $blog->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $blog->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-3">{{ $blog->content }}</p>
                        
                        <div class="mt-auto pt-6 flex gap-3">
                            <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="w-full rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition-colors">
                                    Approve Blog
                                </button>
                            </form>
                            <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="w-full rounded-xl bg-white dark:bg-slate-800 px-3 py-2 text-sm font-semibold text-rose-600 dark:text-rose-400 shadow-sm ring-1 ring-inset ring-rose-300 dark:ring-rose-900/50 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                    Reject Blog
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-8 text-center text-slate-500">
                    No pending blogs to review.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Approved Blogs --}}
    <div class="space-y-4 pt-8 border-t border-slate-200 dark:border-slate-800">
        <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Approved Blogs</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($approvedBlogs as $blog)
                <div class="flex flex-col rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    @if($blog->image_path)
                        <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 7M4 16l4-4a3 3 0 014 0l1 1m4-1l1-1a3 3 0 014 0l.5.5"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-semibold text-slate-500">By {{ $blog->author->name }}</span>
                            <span class="text-slate-300 dark:text-slate-700">•</span>
                            <span class="text-xs text-slate-500">{{ $blog->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white line-clamp-1">{{ $blog->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-3 flex-1">{{ $blog->content }}</p>
                        
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-right">
                            <form action="{{ route('admin.blogs.update', $blog) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="text-sm font-medium text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300">
                                    Unapprove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-8 text-center text-slate-500">
                    No approved blogs found.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
