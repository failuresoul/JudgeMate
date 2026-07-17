@extends('layouts.app')

@section('title', 'Inspiration - JudgeMate')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="text-center space-y-4">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white">
            Daily <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-violet-500">Inspiration</span>
        </h1>
        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
            Discover tips, tricks, and motivations shared by our expert Judges to help you tackle the hardest problems.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pt-8">
        @forelse($blogs as $blog)
            <div class="flex flex-col group rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                @if($blog->image_path)
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    </div>
                @else
                    <div class="relative overflow-hidden h-48 bg-gradient-to-br from-indigo-500/10 to-violet-500/10 flex items-center justify-center">
                        <svg class="w-16 h-16 text-indigo-500/30 dark:text-indigo-400/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                @endif
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ substr($blog->author->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $blog->author->name }}</p>
                            <p class="text-xs text-slate-500">{{ $blog->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">{{ $blog->title }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 whitespace-pre-wrap leading-relaxed flex-1">{{ $blog->content }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-3xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.829 1.508-2.329C17.702 14.331 19.5 12.72 19.5 10.5c0-2.364-1.92-4.29-4.29-4.29-1.98 0-3.692 1.34-4.149 3.197a1.5 1.5 0 01-1.42 1.103H9m-3-1.5h.008v.008H6v-.008z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Check back soon!</h3>
                <p class="text-slate-500 max-w-sm mx-auto">Our judges are brewing up some amazing inspiration for you. New posts will appear here once approved.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
