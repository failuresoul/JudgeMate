@extends('layouts.judge')

@section('title', 'Manage Test Cases: ' . $problem->title . ' - JudgeMate')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('judge.test-cases.index') }}" 
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back to Test Cases
        </a>
    </div>

    {{-- Problem Header Info --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/10 p-6 shadow-sm dark:shadow-none">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $problem->title }}</h1>
                <p class="text-xs font-mono text-slate-500 mt-1">Slug: {{ $problem->slug }}</p>
            </div>
            @php
                $difficultyColors = [
                    'easy'   => 'text-emerald-700 bg-emerald-100 ring-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:ring-emerald-500/20',
                    'medium' => 'text-amber-700 bg-amber-100 ring-amber-200 dark:text-amber-400 dark:bg-amber-500/10 dark:ring-amber-500/20',
                    'hard'   => 'text-rose-700 bg-rose-100 ring-rose-200 dark:text-rose-400 dark:bg-rose-500/10 dark:ring-rose-500/20',
                ];
                $color = $difficultyColors[$problem->difficulty] ?? 'text-slate-600 bg-slate-100 ring-slate-200 dark:text-slate-400 dark:bg-slate-500/10 dark:ring-slate-500/20';
            @endphp
            <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-bold uppercase tracking-wider ring-1 {{ $color }}">
                {{ $problem->difficulty }}
            </span>
        </div>
    </div>

    {{-- Test Case Management subpanel --}}
    @include('problems.partials.test-cases', ['problem' => $problem])
</div>
@endsection
