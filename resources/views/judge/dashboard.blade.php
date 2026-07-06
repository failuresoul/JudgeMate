@extends('layouts.judge')

@section('title', 'Judge Dashboard — JudgeMate')

@section('content')

    {{-- ═══════════ PAGE HEADER ═══════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#7c3aed;">
                {{ date('l, F j, Y') }}
            </p>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">
                Welcome back, <span
                    style="background:linear-gradient(135deg,#a78bfa,#818cf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ auth()->user()->name }}</span>
                👋
            </h1>
            <p class="text-sm text-slate-500 mt-1">Here's your judge panel overview for today.</p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('problems.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-violet-500/30"
                style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Problem
            </a>
            <a href="#"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-slate-300 transition-all duration-200 hover:text-white"
                style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.25);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Review Queue
            </a>
        </div>
    </div>

    {{-- ═══════════ STAT CARDS ═══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Problems Created --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-violet-950/40"
            style="background:rgba(15,10,40,0.7);border:1px solid rgba(124,58,237,0.2);">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                    style="background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.25);">
                    <svg class="h-5 w-5" style="color:#a78bfa;" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                    style="background:rgba(124,58,237,0.15);color:#a78bfa;">Total</span>
            </div>
            <p class="text-3xl font-extrabold text-white tabular-nums">{{ $stats['problems_created'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Problems Created</p>
            <div class="mt-3 flex items-center gap-1 text-[10px]" style="color:#7c3aed;">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                All time
            </div>
        </div>

        {{-- Pending Review --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-amber-950/30"
            style="background:rgba(15,10,5,0.7);border:1px solid rgba(245,158,11,0.2);">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                    style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);">
                    <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                    style="background:rgba(245,158,11,0.12);color:#fbbf24;">Queue</span>
            </div>
            <p class="text-3xl font-extrabold text-white tabular-nums">{{ $stats['pending_review'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Pending Review</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-amber-600">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Awaiting judgment
            </div>
        </div>

        {{-- Accepted Today --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-emerald-950/30"
            style="background:rgba(5,15,10,0.7);border:1px solid rgba(16,185,129,0.2);">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                    style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);">
                    <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                    style="background:rgba(16,185,129,0.1);color:#34d399;">Today</span>
            </div>
            <p class="text-3xl font-extrabold text-white tabular-nums">{{ $stats['accepted_today'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Accepted Verdicts</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-emerald-700">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Accepted today
            </div>
        </div>

        {{-- Total Contestants --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-sky-950/30"
            style="background:rgba(5,10,20,0.7);border:1px solid rgba(56,189,248,0.2);">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                    style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);">
                    <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                    style="background:rgba(56,189,248,0.1);color:#38bdf8;">Active</span>
            </div>
            <p class="text-3xl font-extrabold text-white tabular-nums">{{ $stats['total_contestants'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Approved Contestants</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-sky-800">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Registered &amp; approved
            </div>
        </div>
    </div>

    {{-- ═══════════ MIDDLE ROW: Quick Actions + Verdict Breakdown ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Quick Actions --}}
        <div class="rounded-2xl p-6 lg:col-span-1"
            style="background:rgba(10,8,30,0.7);border:1px solid rgba(124,58,237,0.18);">
            <h2 class="text-sm font-bold uppercase tracking-widest mb-5" style="color:#7c3aed;">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('problems.create') }}"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1"
                    style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.15);">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0"
                        style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200 group-hover:text-violet-300 transition-colors">Create
                            Problem</p>
                        <p class="text-[11px] text-slate-500">Draft a new problem with test cases</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-600 group-hover:text-violet-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="#"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1"
                    style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.15);">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0"
                        style="background:linear-gradient(135deg,#d97706,#b45309);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200 group-hover:text-amber-300 transition-colors">Review
                            Queue</p>
                        <p class="text-[11px] text-slate-500">Judge pending submissions</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-600 group-hover:text-amber-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="#"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1"
                    style="background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0"
                        style="background:linear-gradient(135deg,#059669,#0d9488);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200 group-hover:text-emerald-300 transition-colors">
                            Schedule Contest</p>
                        <p class="text-[11px] text-slate-500">Create &amp; configure a contest</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-600 group-hover:text-emerald-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="#"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1"
                    style="background:rgba(56,189,248,0.06);border:1px solid rgba(56,189,248,0.15);">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0"
                        style="background:linear-gradient(135deg,#0284c7,#0e7490);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200 group-hover:text-sky-300 transition-colors">View
                            Leaderboard</p>
                        <p class="text-[11px] text-slate-500">See contestant rankings</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-600 group-hover:text-sky-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Verdict Breakdown + Activity --}}
        <div class="lg:col-span-2 flex flex-col">

            {{-- Verdict distribution panel --}}
            <div class="rounded-2xl p-6 flex-1 flex flex-col justify-between"
                style="background:rgba(10,8,30,0.7);border:1px solid rgba(124,58,237,0.18);">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-bold uppercase tracking-widest" style="color:#7c3aed;">Verdict Distribution</h2>
                    <span class="text-xs text-slate-500">All time</span>
                </div>

                {{-- Visual bars --}}
                <div class="flex-grow flex flex-col justify-between my-2 min-h-[260px]">
                    @php
                        $verdicts = [
                            ['label' => 'Accepted', 'short' => 'AC', 'val' => 0, 'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.15)', 'pct' => 0],
                            ['label' => 'Wrong Answer', 'short' => 'WA', 'val' => 0, 'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.12)', 'pct' => 0],
                            ['label' => 'Time Limit', 'short' => 'TLE', 'val' => 0, 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.12)', 'pct' => 0],
                            ['label' => 'Runtime Error', 'short' => 'RTE', 'val' => 0, 'color' => '#f97316', 'bg' => 'rgba(249,115,22,0.12)', 'pct' => 0],
                            ['label' => 'Compile Error', 'short' => 'CE', 'val' => 0, 'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.12)', 'pct' => 0],
                        ];
                    @endphp
                    @foreach($verdicts as $v)
                        <div class="flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded"
                                        style="background: {{ $v['bg'] }}; color: {{ $v['color'] }}; font-family: 'JetBrains Mono', monospace;">{{ $v['short'] }}</span>
                                    <span class="text-xs text-slate-400">{{ $v['label'] }}</span>
                                </div>
                                <span class="text-xs font-semibold text-slate-300 tabular-nums">{{ $v['val'] }}</span>
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(30,27,75,0.5);">
                                <div class="h-full rounded-full transition-all duration-700"
                                    style="width: {{ max($v['pct'], 2) }}%; background: {{ $v['color'] }}; opacity: 0.7;"></div>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ BOTTOM ROW: Recent Submissions + My Problems ═══════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent Submissions --}}
        <div class="rounded-2xl p-6" style="background:rgba(10,8,30,0.7);border:1px solid rgba(124,58,237,0.18);">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-bold uppercase tracking-widest" style="color:#7c3aed;">Recent Submissions</h2>
                <a href="#" class="text-xs font-semibold transition-colors hover:opacity-80" style="color:#a78bfa;">View all
                    →</a>
            </div>
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl mb-4"
                    style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);">
                    <svg class="h-7 w-7" style="color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-300">No submissions yet</p>
                <p class="text-xs text-slate-600 mt-1">Submissions will appear here once contestants start solving problems.
                </p>
            </div>
        </div>

        {{-- My Problems --}}
        <div class="rounded-2xl p-6" style="background:rgba(10,8,30,0.7);border:1px solid rgba(124,58,237,0.18);">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-bold uppercase tracking-widest" style="color:#7c3aed;">My Problems</h2>
                <a href="{{ route('problems.create') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold transition-colors hover:opacity-80 px-3 py-1.5 rounded-lg"
                    style="background:rgba(124,58,237,0.12);border:1px solid rgba(124,58,237,0.2);color:#a78bfa;">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Create
                </a>
            </div>

            @if($myProblems->isEmpty())
                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl mb-4"
                        style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);">
                        <svg class="h-7 w-7" style="color:#7c3aed;" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-300">No problems created yet</p>
                    <p class="text-xs text-slate-600 mt-1 mb-4">Start building your problem set for contestants.</p>
                    <a href="{{ route('problems.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all duration-200 hover:opacity-90"
                        style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Create your first problem
                    </a>
                </div>
            @else
                {{-- Problems List --}}
                <div class="divide-y divide-slate-800/60">
                    @foreach($myProblems as $problem)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <a href="{{ route('problems.show', $problem) }}" class="font-semibold text-slate-100 hover:text-indigo-400 text-sm transition-colors">
                                    {{ $problem->title }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $problem->slug }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $difficultyColors = [
                                        'easy'   => 'text-emerald-400 bg-emerald-500/10 ring-emerald-500/20',
                                        'medium' => 'text-amber-400 bg-amber-500/10 ring-amber-500/20',
                                        'hard'   => 'text-rose-400 bg-rose-500/10 ring-rose-500/20',
                                    ];
                                    $color = $difficultyColors[$problem->difficulty] ?? 'text-slate-400 bg-slate-500/10';
                                @endphp
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider ring-1 {{ $color }}">
                                    {{ $problem->difficulty }}
                                </span>
                                <a href="{{ route('problems.edit', $problem) }}" class="text-xs font-semibold text-slate-400 hover:text-white transition-colors bg-slate-800 px-2.5 py-1.5 rounded-lg border border-slate-700">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════ JUDGE TIPS BANNER ═══════════ --}}
    <div class="mt-6 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4"
        style="background:linear-gradient(135deg,rgba(124,58,237,0.08),rgba(79,70,229,0.06));border:1px solid rgba(124,58,237,0.2);">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl flex-shrink-0"
            style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-bold text-slate-200">💡 Getting Started as a Judge</p>
            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                Start by creating your first problem with a clear statement, input/output format, and test cases.
                Once contestants submit, you'll see all verdicts here in real-time.
            </p>
        </div>
        <a href="#"
            class="flex-shrink-0 text-xs font-semibold px-4 py-2 rounded-xl transition-all duration-200 hover:opacity-80"
            style="background:rgba(124,58,237,0.2);border:1px solid rgba(124,58,237,0.3);color:#a78bfa;">
            Read Guide →
        </a>
    </div>

@endsection