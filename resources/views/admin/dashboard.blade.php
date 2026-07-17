@extends('layouts.admin')

@section('title', 'Analytics Dashboard — JudgeMate Admin')

@section('content')
<div class="space-y-8">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-200 to-indigo-400 bg-clip-text text-transparent">
                Analytics Dashboard
            </h1>
            <p class="mt-1 text-sm text-slate-400">
                Live platform metrics — computed directly from the database.
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-400 ring-1 ring-indigo-500/20 self-start sm:self-auto">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
            Super Admin Access
        </span>
    </div>

    {{-- ── Metric Cards Row ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-5">

        {{-- Total Users --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-700 hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Users</span>
                <div class="rounded-xl bg-indigo-500/10 p-2.5 text-indigo-400 group-hover:bg-indigo-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-3.874M9 20H4v-2a4 4 0 015-3.874m6-5.126a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-white">{{ number_format($totalUsers) }}</p>
            <p class="mt-1 text-xs text-slate-500">Registered on the platform</p>
            @if($pendingUsers > 0)
                <a href="{{ route('admin.users.index') }}"
                   class="mt-3 inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2.5 py-0.5
                          text-[11px] font-medium text-amber-400 ring-1 ring-amber-500/20 hover:bg-amber-500/20 transition-colors">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    {{ $pendingUsers }} pending approval
                </a>
            @endif
        </div>

        {{-- Total Submissions --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-700 hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Submissions</span>
                <div class="rounded-xl bg-rose-500/10 p-2.5 text-rose-400 group-hover:bg-rose-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-white">{{ number_format($totalSubmissions) }}</p>
            <p class="mt-1 text-xs text-slate-500">Evaluated solutions</p>
        </div>

        {{-- Active Problems --}}
        <a href="{{ route('problems.index') }}"
           class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl
                  transition-all duration-200 hover:border-slate-700 hover:bg-slate-900/70 group block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 group-hover:text-slate-300 transition-colors">Problems</span>
                <div class="rounded-xl bg-violet-500/10 p-2.5 text-violet-400 group-hover:bg-violet-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-white">{{ number_format($totalProblems) }}</p>
            <p class="mt-1 text-xs text-slate-500">In the problem repository</p>
        </a>

        {{-- Total Contests --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl
                    transition-all duration-200 hover:border-slate-700 hover:bg-slate-900/70 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Contests Held</span>
                <div class="rounded-xl bg-emerald-500/10 p-2.5 text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-white">{{ number_format($totalContests) }}</p>
            <p class="mt-1 text-xs text-slate-500">Scheduled &amp; completed</p>
        </div>

        {{-- Accepted Count --}}
        <div class="relative overflow-hidden rounded-2xl border border-emerald-900/40 bg-emerald-950/20 p-6 backdrop-blur-xl
                    transition-all duration-200 hover:border-emerald-800/60 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Accepted</span>
                <div class="rounded-xl bg-emerald-500/10 p-2.5 text-emerald-400 group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-5 text-4xl font-extrabold tracking-tight text-emerald-300">{{ number_format($verdictCounts['accepted']) }}</p>
            @if($totalSubmissions > 0)
                <p class="mt-1 text-xs text-emerald-700">
                    {{ number_format($verdictCounts['accepted'] / $totalSubmissions * 100, 1) }}% acceptance rate
                </p>
            @else
                <p class="mt-1 text-xs text-slate-600">No submissions yet</p>
            @endif
        </div>

    </div>

    {{-- ── Analytics Grid: Doughnut Chart + Top Problems ─────────────────── --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

        {{-- ── Verdict Distribution Doughnut (Chart.js) ────────────────────── --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-slate-900/40 p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-semibold text-white">Verdict Distribution</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Breakdown of all {{ number_format($totalSubmissions) }} submissions</p>
                </div>
                <span class="rounded-lg bg-slate-800 px-2.5 py-1 text-[10px] font-medium text-slate-400 uppercase tracking-wider">
                    Live
                </span>
            </div>

            {{-- Canvas for Chart.js --}}
            <div class="relative flex-1 flex items-center justify-center min-h-[240px]">
                @if($totalSubmissions > 0)
                    <canvas id="verdictChart" class="max-h-64" aria-label="Verdict distribution doughnut chart" role="img"></canvas>
                @else
                    <div class="flex flex-col items-center gap-3 text-slate-600">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">No submission data yet</p>
                    </div>
                @endif
            </div>

            {{-- Legend --}}
            @if($totalSubmissions > 0)
            <ul class="mt-6 grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                @php
                    $legendItems = [
                        ['label' => 'Accepted',            'key' => 'accepted',            'color' => 'bg-emerald-500'],
                        ['label' => 'Wrong Answer',        'key' => 'wrong_answer',        'color' => 'bg-rose-500'],
                        ['label' => 'Time Limit Exceeded', 'key' => 'time_limit_exceeded', 'color' => 'bg-amber-500'],
                        ['label' => 'Compilation Error',   'key' => 'compilation_error',   'color' => 'bg-violet-500'],
                    ];
                @endphp
                @foreach($legendItems as $item)
                <li class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 shrink-0 rounded-sm {{ $item['color'] }}"></span>
                    <span class="text-slate-400 truncate">{{ $item['label'] }}</span>
                    <span class="ml-auto font-semibold text-slate-200">{{ number_format($verdictCounts[$item['key']]) }}</span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- ── Top 5 Most-Attempted Problems ────────────────────────────────── --}}
        <div class="lg:col-span-3 rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-base font-semibold text-white">Top 5 Most-Attempted Problems</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ranked by total submission count</p>
                </div>
                <a href="{{ route('problems.index') }}"
                   class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1 group">
                    View all
                    <svg class="h-3.5 w-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if($topProblems->isEmpty())
                <p class="text-sm text-slate-600 py-8 text-center">No problems have been attempted yet.</p>
            @else
                @php
                    $maxCount = $topProblems->first()->submissions_count ?: 1;
                @endphp
                <ol class="space-y-4">
                    @foreach($topProblems as $rank => $problem)
                    <li class="group">
                        <div class="flex items-center gap-3">
                            {{-- Rank badge --}}
                            <span class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold
                                {{ $rank === 0 ? 'bg-amber-500/20 text-amber-300' :
                                   ($rank === 1 ? 'bg-slate-700/60 text-slate-300' :
                                   ($rank === 2 ? 'bg-orange-900/30 text-orange-400' : 'bg-slate-800 text-slate-500')) }}">
                                {{ $rank + 1 }}
                            </span>

                            {{-- Problem title --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('problems.show', $problem->slug) }}"
                                   class="block text-sm font-medium text-slate-200 truncate hover:text-white transition-colors">
                                    {{ $problem->title }}
                                </a>

                                {{-- Progress bar --}}
                                <div class="mt-1.5 h-1.5 w-full rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700
                                        {{ $rank === 0 ? 'bg-indigo-500' :
                                           ($rank === 1 ? 'bg-indigo-500/70' :
                                           ($rank === 2 ? 'bg-indigo-500/55' : 'bg-indigo-500/40')) }}"
                                         style="width: {{ round($problem->submissions_count / $maxCount * 100) }}%">
                                    </div>
                                </div>
                            </div>

                            {{-- Count badge --}}
                            <span class="shrink-0 ml-2 text-sm font-semibold tabular-nums text-slate-300">
                                {{ number_format($problem->submissions_count) }}
                                <span class="text-xs font-normal text-slate-600">sub{{ $problem->submissions_count !== 1 ? 's' : '' }}</span>
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
        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500 mb-4">Verdict Breakdown</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            @php
                $verdictDetails = [
                    [
                        'label'   => 'Wrong Answer',
                        'key'     => 'wrong_answer',
                        'icon_path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'ring'    => 'ring-rose-900/40',
                        'bg'      => 'bg-rose-950/20',
                        'icon_bg' => 'bg-rose-500/10',
                        'icon_fg' => 'text-rose-400',
                        'num_fg'  => 'text-rose-300',
                    ],
                    [
                        'label'   => 'Time Limit Exceeded',
                        'key'     => 'time_limit_exceeded',
                        'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'ring'    => 'ring-amber-900/40',
                        'bg'      => 'bg-amber-950/20',
                        'icon_bg' => 'bg-amber-500/10',
                        'icon_fg' => 'text-amber-400',
                        'num_fg'  => 'text-amber-300',
                    ],
                    [
                        'label'   => 'Compilation Error',
                        'key'     => 'compilation_error',
                        'icon_path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        'ring'    => 'ring-violet-900/40',
                        'bg'      => 'bg-violet-950/20',
                        'icon_bg' => 'bg-violet-500/10',
                        'icon_fg' => 'text-violet-400',
                        'num_fg'  => 'text-violet-300',
                    ],
                    [
                        'label'   => 'Accepted',
                        'key'     => 'accepted',
                        'icon_path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'ring'    => 'ring-emerald-900/40',
                        'bg'      => 'bg-emerald-950/20',
                        'icon_bg' => 'bg-emerald-500/10',
                        'icon_fg' => 'text-emerald-400',
                        'num_fg'  => 'text-emerald-300',
                    ],
                ];
            @endphp

            @foreach($verdictDetails as $v)
            <div class="rounded-2xl border {{ $v['ring'] }} {{ $v['bg'] }} p-5
                        transition-all duration-200 hover:scale-[1.02] hover:shadow-lg hover:shadow-black/30">
                <div class="{{ $v['icon_bg'] }} rounded-xl w-10 h-10 flex items-center justify-center {{ $v['icon_fg'] }} mb-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $v['icon_path'] }}"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold tracking-tight {{ $v['num_fg'] }}">
                    {{ number_format($verdictCounts[$v['key']]) }}
                </p>
                <p class="mt-1 text-xs text-slate-500">{{ $v['label'] }}</p>
                @if($totalSubmissions > 0)
                    <p class="text-[10px] text-slate-600 mt-0.5">
                        {{ number_format($verdictCounts[$v['key']] / $totalSubmissions * 100, 1) }}% of total
                    </p>
                @endif
            </div>
            @endforeach

        </div>
    </div>

    {{-- ── Admin Quick Actions ──────────────────────────────────────────────── --}}
    <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6">
        <h2 class="text-base font-semibold text-white mb-4">Administrative Actions</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center justify-between rounded-xl bg-slate-900 border border-slate-800
                      hover:border-slate-700 px-4 py-3 text-sm text-slate-300 hover:text-white transition-all group">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Review Pending Registrations
                    @if($pendingUsers > 0)
                        <span class="ml-1 rounded-full bg-amber-500/20 px-1.5 py-0.5 text-[10px] text-amber-400">{{ $pendingUsers }}</span>
                    @endif
                </span>
                <svg class="h-4 w-4 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('problems.index') }}"
               class="flex items-center justify-between rounded-xl bg-slate-900 border border-slate-800
                      hover:border-slate-700 px-4 py-3 text-sm text-slate-300 hover:text-white transition-all group">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Browse Problem Repository
                </span>
                <svg class="h-4 w-4 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('contests.index') }}"
               class="flex items-center justify-between rounded-xl bg-slate-900 border border-slate-800
                      hover:border-slate-700 px-4 py-3 text-sm text-slate-300 hover:text-white transition-all group">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Manage Contests
                </span>
                <svg class="h-4 w-4 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

</div>
@endsection

{{-- ── Chart.js — loaded from CDN, rendered after DOM is ready ───────────── --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"
        integrity="sha256-bC3LCZCwKeehY4f3wFQJMFrpOlAMBM5cKXYKOoUBDGA=" crossorigin="anonymous"></script>
<script>
(function () {
    'use strict';

    // ── Verdict data passed from the controller via Blade ──────────────────
    const verdictData = {
        accepted:            {{ $verdictCounts['accepted'] }},
        wrong_answer:        {{ $verdictCounts['wrong_answer'] }},
        time_limit_exceeded: {{ $verdictCounts['time_limit_exceeded'] }},
        compilation_error:   {{ $verdictCounts['compilation_error'] }},
    };

    const totalSubmissions = {{ $totalSubmissions }};

    // Only attempt to render if submissions exist and canvas is present
    const canvas = document.getElementById('verdictChart');
    if (!canvas || totalSubmissions === 0) return;

    // ── Chart.js colour palette (matches the Blade legend) ────────────────
    const palette = {
        accepted:            { fill: 'rgba(52, 211, 153, 0.90)', border: 'rgba(52, 211, 153, 1)' },
        wrong_answer:        { fill: 'rgba(251, 113, 133, 0.90)', border: 'rgba(251, 113, 133, 1)' },
        time_limit_exceeded: { fill: 'rgba(251, 191, 36,  0.90)', border: 'rgba(251, 191, 36,  1)' },
        compilation_error:   { fill: 'rgba(167, 139, 250, 0.90)', border: 'rgba(167, 139, 250, 1)' },
    };

    const labels = ['Accepted', 'Wrong Answer', 'Time Limit Exceeded', 'Compilation Error'];
    const keys   = ['accepted', 'wrong_answer', 'time_limit_exceeded', 'compilation_error'];

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data:            keys.map(k => verdictData[k]),
                backgroundColor: keys.map(k => palette[k].fill),
                borderColor:     keys.map(k => palette[k].border),
                borderWidth:     2,
                hoverOffset:     8,
                borderRadius:    4,
            }],
        },
        options: {
            responsive:          true,
            maintainAspectRatio: true,
            cutout:              '68%',
            animation: {
                animateRotate: true,
                duration:      900,
                easing:        'easeInOutQuart',
            },
            plugins: {
                legend: { display: false },   // we use the custom HTML legend below
                tooltip: {
                    callbacks: {
                        label(ctx) {
                            const pct = totalSubmissions > 0
                                ? ((ctx.parsed / totalSubmissions) * 100).toFixed(1)
                                : '0.0';
                            return ` ${ctx.label}: ${ctx.parsed.toLocaleString()} (${pct}%)`;
                        },
                    },
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor:      '#e2e8f0',
                    bodyColor:       '#94a3b8',
                    borderColor:     'rgba(71, 85, 105, 0.5)',
                    borderWidth:     1,
                    padding:         10,
                    cornerRadius:    10,
                },
            },
        },
    });
})();
</script>
@endpush
