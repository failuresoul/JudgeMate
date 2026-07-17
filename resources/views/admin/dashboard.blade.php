@extends('layouts.admin')

@section('title', 'Analytics Dashboard — JudgeMate Admin')

@section('content')
<div class="space-y-8">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-slate-900 via-slate-700 to-indigo-600 dark:from-white dark:via-slate-200 dark:to-indigo-400 bg-clip-text text-transparent">
                Analytics Dashboard
            </h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Live platform metrics — computed directly from the database.
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20 self-start sm:self-auto shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
            Super Admin Access
        </span>
    </div>

    {{-- ── Metric Cards Row ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Total Users --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 shadow-sm backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Users</span>
                <div class="rounded-xl bg-indigo-50 dark:bg-indigo-500/10 p-2.5 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.874M9 20H4v-2a4 4 0 015-3.874m6-5.126a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Registered on the platform</p>
            @if($pendingUsers > 0)
                <a href="{{ route('admin.users.index') }}"
                   class="mt-3 inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-500/10 px-2.5 py-0.5
                          text-[11px] font-medium text-amber-700 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-500/20 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    {{ $pendingUsers }} pending approval
                </a>
            @endif
        </div>

        {{-- Total Submissions --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 shadow-sm backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Submissions</span>
                <div class="rounded-xl bg-rose-50 dark:bg-rose-500/10 p-2.5 text-rose-600 dark:text-rose-400 group-hover:bg-rose-100 dark:group-hover:bg-rose-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ number_format($totalSubmissions) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Evaluated solutions</p>
        </div>

        {{-- Active Problems --}}
        <a href="{{ route('problems.index') }}"
           class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 shadow-sm backdrop-blur-xl
                  transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-900/70 group block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Problems</span>
                <div class="rounded-xl bg-violet-50 dark:bg-violet-500/10 p-2.5 text-violet-600 dark:text-violet-400 group-hover:bg-violet-100 dark:group-hover:bg-violet-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ number_format($totalProblems) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">In the problem repository</p>
        </a>

        {{-- Total Contests --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 shadow-sm backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Contests Held</span>
                <div class="rounded-xl bg-cyan-50 dark:bg-cyan-500/10 p-2.5 text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-100 dark:group-hover:bg-cyan-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ number_format($totalContests) }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Scheduled &amp; completed</p>
        </div>

        {{-- Accepted Count --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-6 shadow-sm backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-300 dark:hover:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Accepted</span>
                <div class="rounded-xl bg-emerald-50 dark:bg-emerald-500/10 p-2.5 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ number_format($verdictCounts['accepted']) }}</p>
            @if($totalSubmissions > 0)
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    <span class="font-medium text-emerald-600 dark:text-emerald-400">{{ number_format($verdictCounts['accepted'] / $totalSubmissions * 100, 1) }}%</span> acceptance rate
                </p>
            @else
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">No submissions yet</p>
            @endif
        </div>

    </div>

    {{-- ── Analytics Grid: Doughnut Chart + Top Problems ─────────────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

        {{-- ── Verdict Distribution (Progress Bars) ────────────────────── --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 flex flex-col shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Verdict Distribution</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Breakdown of all {{ number_format($totalSubmissions) }} submissions</p>
                </div>
                <span class="rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2.5 py-1 text-[10px] font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                    Live
                </span>
            </div>

            <div class="mt-6 flex flex-col justify-center flex-1 space-y-5">
                @if($totalSubmissions > 0)
                    @foreach([
                        ['label' => 'Accepted',            'key' => 'accepted',            'color' => 'bg-emerald-500', 'text' => 'text-emerald-700 dark:text-emerald-400', 'bg' => 'bg-emerald-100 dark:bg-emerald-950/30'],
                        ['label' => 'Wrong Answer',        'key' => 'wrong_answer',        'color' => 'bg-rose-500',    'text' => 'text-rose-700 dark:text-rose-400',       'bg' => 'bg-rose-100 dark:bg-rose-950/30'],
                        ['label' => 'Compilation Error',   'key' => 'compilation_error',   'color' => 'bg-violet-500',  'text' => 'text-violet-700 dark:text-violet-400',   'bg' => 'bg-violet-100 dark:bg-violet-950/30'],
                        ['label' => 'Time Limit Exceeded', 'key' => 'time_limit_exceeded', 'color' => 'bg-amber-500',   'text' => 'text-amber-700 dark:text-amber-400',     'bg' => 'bg-amber-100 dark:bg-amber-950/30'],
                    ] as $item)
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $item['label'] }}</span>
                                <span class="text-sm font-semibold {{ $item['text'] }}">{{ number_format($verdictCounts[$item['key']]) }}</span>
                            </div>
                            <div class="w-full {{ $item['bg'] }} rounded-full h-2.5 overflow-hidden border border-white/10">
                                <div class="{{ $item['color'] }} h-full rounded-full transition-all duration-1000" style="width: {{ $verdictCounts[$item['key']] / $totalSubmissions * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <svg class="h-10 w-10 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">No submission data yet</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Top 5 Most-Attempted Problems ────────────────────────────────── --}}
        <div class="lg:col-span-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Top 5 Most-Attempted Problems</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ranked by total submission count</p>
                </div>
                <a href="{{ route('problems.index') }}"
                   class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors flex items-center gap-1 group">
                    View all
                    <svg class="h-3.5 w-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($topProblems->isEmpty())
                <div class="flex-1 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400 py-8">
                    <p class="text-sm">No problems have been attempted yet.</p>
                </div>
            @else
                @php
                    $maxCount = $topProblems->first()->submissions_count ?: 1;
                @endphp
                <ol class="space-y-5 flex-1 flex flex-col justify-center">
                    @foreach($topProblems as $rank => $problem)
                    <li class="group">
                        <div class="flex items-center gap-3">
                            {{-- Rank badge --}}
                            <span class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold
                                {{ $rank === 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300' :
                                   ($rank === 1 ? 'bg-slate-100 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300' :
                                   ($rank === 2 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400')) }}">
                                {{ $rank + 1 }}
                            </span>

                            {{-- Problem title --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('problems.show', $problem->slug) }}"
                                   class="block text-sm font-medium text-slate-900 dark:text-slate-200 truncate hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    {{ $problem->title }}
                                </a>

                                {{-- Progress bar --}}
                                <div class="mt-1.5 h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000
                                        {{ $rank === 0 ? 'bg-indigo-500' :
                                           ($rank === 1 ? 'bg-indigo-500/80' :
                                           ($rank === 2 ? 'bg-indigo-500/60' : 'bg-indigo-500/40')) }}"
                                         style="width: {{ round($problem->submissions_count / $maxCount * 100) }}%">
                                    </div>
                                </div>
                            </div>

                            {{-- Count badge --}}
                            <span class="shrink-0 ml-3 text-sm font-semibold tabular-nums text-slate-700 dark:text-slate-300">
                                {{ number_format($problem->submissions_count) }}
                                <span class="text-[11px] font-normal text-slate-500">sub{{ $problem->submissions_count !== 1 ? 's' : '' }}</span>
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ol>
            @endif
        </div>

    </div>

    {{-- ── Verdict Detail Cards Row ────────────────────────────────────────── --}}
    <div>
        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4">Verdict Breakdown</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            @php
                $verdictDetails = [
                    [
                        'label'   => 'Accepted',
                        'key'     => 'accepted',
                        'icon_path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'icon_bg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                        'icon_fg' => 'text-emerald-600 dark:text-emerald-400',
                    ],
                    [
                        'label'   => 'Wrong Answer',
                        'key'     => 'wrong_answer',
                        'icon_path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'icon_bg' => 'bg-rose-50 dark:bg-rose-500/10',
                        'icon_fg' => 'text-rose-600 dark:text-rose-400',
                    ],
                    [
                        'label'   => 'Time Limit Exceeded',
                        'key'     => 'time_limit_exceeded',
                        'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'icon_bg' => 'bg-amber-50 dark:bg-amber-500/10',
                        'icon_fg' => 'text-amber-600 dark:text-amber-400',
                    ],
                    [
                        'label'   => 'Compilation Error',
                        'key'     => 'compilation_error',
                        'icon_path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        'icon_bg' => 'bg-violet-50 dark:bg-violet-500/10',
                        'icon_fg' => 'text-violet-600 dark:text-violet-400',
                    ],
                ];
            @endphp

            @foreach($verdictDetails as $v)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-5 shadow-sm
                        transition-all duration-200 hover:scale-[1.02] hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md dark:shadow-lg hover:shadow-black/10">
                <div class="{{ $v['icon_bg'] }} rounded-xl w-10 h-10 flex items-center justify-center {{ $v['icon_fg'] }} mb-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $v['icon_path'] }}"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    {{ number_format($verdictCounts[$v['key']]) }}
                </p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $v['label'] }}</p>
                @if($totalSubmissions > 0)
                    <p class="text-[10px] text-slate-500 dark:text-slate-500 mt-0.5">
                        {{ number_format($verdictCounts[$v['key']] / $totalSubmissions * 100, 1) }}% of total
                    </p>
                @endif
            </div>
            @endforeach

        </div>
    </div>

    {{-- ── Admin Quick Actions ──────────────────────────────────────────────── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/40 p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Administrative Actions</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50
                      hover:bg-slate-100 dark:hover:bg-slate-800 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all group">
                <span class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Review Pending</span>
                    @if($pendingUsers > 0)
                        <span class="ml-1 rounded-full bg-amber-100 dark:bg-amber-500/20 px-1.5 py-0.5 text-[10px] font-bold text-amber-700 dark:text-amber-400">{{ $pendingUsers }}</span>
                    @endif
                </span>
                <svg class="h-4 w-4 text-slate-400 group-hover:translate-x-1 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('problems.index') }}"
               class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50
                      hover:bg-slate-100 dark:hover:bg-slate-800 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all group">
                <span class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-violet-100 dark:bg-violet-500/20 text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Problem Repository</span>
                </span>
                <svg class="h-4 w-4 text-slate-400 group-hover:translate-x-1 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('contests.index') }}"
               class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50
                      hover:bg-slate-100 dark:hover:bg-slate-800 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all group">
                <span class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="font-medium">Manage Contests</span>
                </span>
                <svg class="h-4 w-4 text-slate-400 group-hover:translate-x-1 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

</div>
@endsection
