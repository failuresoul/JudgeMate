@extends('layouts.judge')

@section('title', 'Judge Dashboard — JudgeMate')

@section('content')

    {{-- ═══════════ PAGE HEADER ═══════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest mb-1 text-violet-600 dark:text-violet-500">
                {{ date('l, F j, Y') }}
            </p>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Welcome back, <span
                    class="bg-gradient-to-br from-violet-500 to-indigo-500 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>
                👋
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Here's your judge panel overview for today.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
            <a href="{{ route('problems.create') }}"
                class="group inline-flex flex-1 sm:flex-none justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-violet-500/40 bg-gradient-to-br from-violet-600 via-indigo-600 to-indigo-700 border border-white/10 shadow-md">
                <svg class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="whitespace-nowrap tracking-wide">New Problem</span>
            </a>
            <a href="{{ route('judge.blogs.create') }}"
                class="group inline-flex flex-1 sm:flex-none justify-center items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-fuchsia-500/40 bg-gradient-to-br from-fuchsia-500 via-pink-600 to-rose-600 border border-white/10 shadow-md">
                <svg class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
                <span class="whitespace-nowrap tracking-wide">New Post</span>
            </a>
        </div>
    </div>

    {{-- ═══════════ STAT CARDS ═══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Problems Created --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-violet-500/10 dark:hover:shadow-violet-950/40 bg-white dark:bg-[#0f0a28]/70 border border-slate-200 dark:border-violet-500/20 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-500/15 border border-violet-100 dark:border-violet-500/25">
                    <svg class="h-5 w-5 text-violet-500 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-violet-50 dark:bg-violet-500/15 text-violet-600 dark:text-violet-400">Total</span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $stats['problems_created'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Problems Created</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-violet-600 dark:text-violet-500">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                All time
            </div>
        </div>

        {{-- Pending Review --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-amber-500/10 dark:hover:shadow-amber-950/30 bg-white dark:bg-[#0f0a05]/70 border border-slate-200 dark:border-amber-500/20 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20">
                    <svg class="h-5 w-5 text-amber-500 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-500/12 text-amber-600 dark:text-amber-400">Queue</span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $stats['pending_review'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Pending Review</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-amber-600 dark:text-amber-600">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Awaiting judgment
            </div>
        </div>

        {{-- Accepted Today --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-emerald-500/10 dark:hover:shadow-emerald-950/30 bg-white dark:bg-[#050f0a]/70 border border-slate-200 dark:border-emerald-500/20 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                    <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Today</span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $stats['accepted_today'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Accepted Verdicts</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-emerald-600 dark:text-emerald-700">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Accepted today
            </div>
        </div>

        {{-- Total Contestants --}}
        <div class="rounded-2xl p-5 transition-all duration-200 hover:translate-y-[-2px] hover:shadow-xl hover:shadow-sky-500/10 dark:hover:shadow-sky-950/30 bg-white dark:bg-[#050a14]/70 border border-slate-200 dark:border-sky-500/20 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 dark:bg-sky-500/10 border border-sky-100 dark:border-sky-500/20">
                    <svg class="h-5 w-5 text-sky-500 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400">Active</span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 dark:text-white tabular-nums">{{ $stats['total_contestants'] }}</p>
            <p class="text-xs text-slate-500 mt-1 font-medium">Approved Contestants</p>
            <div class="mt-3 flex items-center gap-1 text-[10px] text-sky-600 dark:text-sky-800">
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
        <div class="rounded-2xl p-6 lg:col-span-1 bg-white dark:bg-[#0a081e]/70 border border-slate-200 dark:border-violet-500/18 shadow-sm">
            <h2 class="text-sm font-bold uppercase tracking-widest mb-5 text-violet-600 dark:text-violet-500">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('problems.create') }}"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1 bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/15">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0 bg-gradient-to-br from-violet-600 to-indigo-600">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-200 group-hover:text-violet-600 dark:group-hover:text-violet-300 transition-colors">Create
                            Problem</p>
                        <p class="text-[11px] text-slate-500">Draft a new problem with test cases</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-400 dark:text-slate-600 group-hover:text-violet-500 dark:group-hover:text-violet-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="#"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/15">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0 bg-gradient-to-br from-amber-500 to-orange-600">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-300 transition-colors">Review
                            Queue</p>
                        <p class="text-[11px] text-slate-500">Judge pending submissions</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-400 dark:text-slate-600 group-hover:text-amber-500 dark:group-hover:text-amber-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="#"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/15">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0 bg-gradient-to-br from-emerald-500 to-teal-600">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-200 group-hover:text-emerald-600 dark:group-hover:text-emerald-300 transition-colors">
                            Schedule Contest</p>
                        <p class="text-[11px] text-slate-500">Create &amp; configure a contest</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-400 dark:text-slate-600 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('leaderboard') }}"
                    class="group flex items-center gap-4 p-3.5 rounded-xl transition-all duration-150 hover:translate-x-1 bg-sky-50 dark:bg-sky-500/10 border border-sky-100 dark:border-sky-500/15">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-200 group-hover:text-sky-600 dark:group-hover:text-sky-300 transition-colors">View
                            Leaderboard</p>
                        <p class="text-[11px] text-slate-500">See contestant rankings</p>
                    </div>
                    <svg class="ml-auto h-4 w-4 text-slate-400 dark:text-slate-600 group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Verdict Breakdown + Activity --}}
        <div class="lg:col-span-2 flex flex-col">

            {{-- Verdict distribution panel --}}
            <div class="rounded-2xl p-6 flex-1 flex flex-col justify-between bg-white dark:bg-[#0a081e]/70 border border-slate-200 dark:border-violet-500/18 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-violet-600 dark:text-violet-500">Verdict Distribution</h2>
                    <span class="text-xs text-slate-500">All time</span>
                </div>

                {{-- Visual bars --}}
                <div class="flex-grow flex flex-col justify-between my-2 min-h-[260px]">
                    @foreach($verdicts as $v)
                        <div class="flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded"
                                        style="background: {{ $v['bg'] }}; color: {{ $v['color'] }}; font-family: 'JetBrains Mono', monospace;">{{ $v['short'] }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $v['label'] }}</span>
                                </div>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 tabular-nums">{{ $v['val'] }}</span>
                            </div>
                            <div class="h-1.5 rounded-full overflow-hidden bg-slate-100 dark:bg-[#1e1b4b]/50">
                                <div class="h-full rounded-full transition-all duration-700"
                                    style="width: {{ max($v['pct'], 2) }}%; background: {{ $v['color'] }}; opacity: 0.8;"></div>
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
        <div class="rounded-2xl p-6 bg-white dark:bg-[#0a081e]/70 border border-slate-200 dark:border-violet-500/18 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-bold uppercase tracking-widest text-violet-600 dark:text-violet-500">Recent Submissions</h2>
                <a href="{{ route('submissions.index') }}" class="text-xs font-semibold transition-colors hover:opacity-80 text-violet-600 dark:text-violet-400">View all →</a>
            </div>
            @if($submissions->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl mb-4 bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20">
                        <svg class="h-7 w-7 text-violet-500 dark:text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No submissions yet</p>
                    <p class="text-xs text-slate-500 dark:text-slate-600 mt-1 font-medium">Submissions will appear here once contestants start solving your problems.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @foreach($submissions as $submission)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <a href="{{ route('problems.show', $submission->problem) }}" class="font-semibold text-slate-900 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">
                                    {{ $submission->problem->title }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">
                                    by <span class="font-medium text-slate-700 dark:text-slate-300">{{ $submission->user->name }}</span> &bull; {{ $submission->submitted_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-[10px] uppercase bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-600 dark:text-slate-300">
                                    {{ $submission->language }}
                                </span>
                                @php
                                    $badgeClasses = [
                                        'pending'               => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 ring-amber-500/20',
                                        'accepted'              => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-emerald-500/20',
                                        'wrong_answer'          => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 ring-rose-500/20',
                                        'compilation_error'     => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 ring-rose-500/20',
                                        'time_limit_exceeded'   => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 ring-rose-500/20',
                                    ];
                                    $class = $badgeClasses[$submission->status] ?? 'bg-slate-50 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400 ring-slate-500/20';

                                    $statusLabels = [
                                        'pending'               => 'Pending',
                                        'accepted'              => 'Accepted',
                                        'wrong_answer'          => 'Wrong Answer',
                                        'compilation_error'     => 'Compilation Error',
                                        'time_limit_exceeded'   => 'Time Limit Exceeded',
                                    ];
                                    $label = $statusLabels[$submission->status] ?? ucfirst(str_replace('_', ' ', $submission->status));
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $class }}">
                                    {{ $label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- My Problems --}}
        <div class="rounded-2xl p-6 bg-white dark:bg-[#0a081e]/70 border border-slate-200 dark:border-violet-500/18 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-bold uppercase tracking-widest text-violet-600 dark:text-violet-500">My Problems</h2>
                <a href="{{ route('problems.create') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold transition-colors hover:opacity-80 px-3 py-1.5 rounded-lg bg-violet-50 dark:bg-violet-500/12 border border-violet-200 dark:border-violet-500/20 text-violet-600 dark:text-violet-400">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Create
                </a>
            </div>

            @if($myProblems->isEmpty())
                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl mb-4 bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20">
                        <svg class="h-7 w-7 text-violet-500 dark:text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No problems created yet</p>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Start building your problem set for contestants.</p>
                    <a href="{{ route('problems.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all duration-200 hover:opacity-90 bg-gradient-to-br from-violet-600 to-indigo-600">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Create your first problem
                    </a>
                </div>
            @else
                {{-- Problems List --}}
                <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @foreach($myProblems as $problem)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <a href="{{ route('problems.show', $problem) }}" class="font-semibold text-slate-900 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">
                                    {{ $problem->title }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $problem->slug }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $difficultyColors = [
                                        'easy'   => 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 ring-emerald-500/20',
                                        'medium' => 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 ring-amber-500/20',
                                        'hard'   => 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10 ring-rose-500/20',
                                    ];
                                    $color = $difficultyColors[$problem->difficulty] ?? 'text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-500/10 ring-slate-500/20';
                                @endphp
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider ring-1 {{ $color }}">
                                    {{ $problem->difficulty }}
                                </span>
                                <a href="{{ route('problems.edit', $problem) }}" class="text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors bg-slate-100 dark:bg-slate-800 px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>



@endsection