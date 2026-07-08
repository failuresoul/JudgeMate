@extends('layouts.app')

@section('title', 'Contestant Dashboard - JudgeMate')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    {{-- Welcome & Greeting Banner --}}
    <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-xl backdrop-blur-sm relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-violet-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 space-y-2">
            <h1 class="text-3xl font-extrabold tracking-tight text-white">
                Welcome back, <span class="text-indigo-400 font-extrabold">{{ auth()->user()->name }}</span>!
            </h1>
            <p class="text-slate-400 max-w-2xl text-sm leading-relaxed">
                Your JudgeMate coding workspace is ready. Sharpen your skills by solving the catalog of challenges, submitting your code, and tracking real-time evaluations.
            </p>
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Solved Problems --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 p-6 shadow-lg hover:border-slate-700/60 transition-colors duration-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold uppercase tracking-wider text-slate-500">Solved Problems</span>
                <div class="rounded-xl bg-emerald-500/10 p-2.5 text-emerald-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white">{{ $solvedCount }}</span>
                <p class="text-xs text-slate-500 mt-1">Unique challenges passed</p>
            </div>
        </div>

        {{-- Total Submissions --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 p-6 shadow-lg hover:border-slate-700/60 transition-colors duration-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold uppercase tracking-wider text-slate-500">Total Submissions</span>
                <div class="rounded-xl bg-indigo-500/10 p-2.5 text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white">{{ $totalSubmissions }}</span>
                <p class="text-xs text-slate-500 mt-1">Total solution runs uploaded</p>
            </div>
        </div>

        {{-- Acceptance Rate --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 p-6 shadow-lg hover:border-slate-700/60 transition-colors duration-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold uppercase tracking-wider text-slate-500">Acceptance Rate</span>
                <div class="rounded-xl bg-violet-500/10 p-2.5 text-violet-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white">{{ $acceptanceRate }}%</span>
                <p class="text-xs text-slate-500 mt-1">Ratio of accepted solutions</p>
            </div>
        </div>

        {{-- Pending In Queue --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 p-6 shadow-lg hover:border-slate-700/60 transition-colors duration-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold uppercase tracking-wider text-slate-500">Pending Queue</span>
                <div class="rounded-xl bg-amber-500/10 p-2.5 text-amber-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-extrabold text-white">{{ $pendingCount }}</span>
                <p class="text-xs text-slate-500 mt-1">Awaiting judge evaluation</p>
            </div>
        </div>
    </div>

    {{-- Main Sections Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left 2 Columns: Recommended Problems & Platform Guidelines --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Recommended Challenges --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
                    <h2 class="text-lg font-bold text-white tracking-tight">Recommended Problems</h2>
                    <a href="{{ route('problems.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                        Browse all →
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($recommendedProblems as $problem)
                        <div class="rounded-xl border border-slate-800/80 bg-slate-950/40 p-5 flex flex-col justify-between hover:border-slate-700 hover:bg-slate-900/20 transition-all duration-200 group">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    @php
                                        $diffColors = [
                                            'easy'   => 'text-emerald-400 bg-emerald-500/10 ring-emerald-500/20',
                                            'medium' => 'text-amber-400 bg-amber-500/10 ring-amber-500/20',
                                            'hard'   => 'text-rose-400 bg-rose-500/10 ring-rose-500/20',
                                        ];
                                        $color = $diffColors[$problem->difficulty] ?? 'text-slate-400 bg-slate-500/10';
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider ring-1 {{ $color }}">
                                        {{ $problem->difficulty }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors text-base">
                                    {{ $problem->title }}
                                </h3>
                                <p class="text-xs text-slate-500 font-mono">Slug: {{ $problem->slug }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-900 flex items-center justify-end">
                                <a href="{{ route('problems.show', $problem) }}" 
                                   class="inline-flex items-center gap-1 text-xs font-bold text-indigo-400 group-hover:translate-x-0.5 transition-transform">
                                    Solve Problem
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-slate-500">
                            <p class="text-sm">Wow! You've solved all recommended challenges.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Guidelines Card --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                    <h2 class="text-lg font-bold text-white tracking-tight">Competitive Programming Guide</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm text-slate-400">
                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-200">1. Select Language</h4>
                        <p class="text-xs leading-relaxed">Choose from C++ (g++ 17), Python (3.10), or Java (JDK 17) depending on performance needs.</p>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-200">2. Input / Output</h4>
                        <p class="text-xs leading-relaxed">Ensure standard input/output streams are used correctly. Avoid prompt messages.</p>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-200">3. Time & Space Limits</h4>
                        <p class="text-xs leading-relaxed">Be mindful of algorithm complexities to prevent Time Limit Exceeded (TLE) errors.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Solved Difficulty Breakdown & Recent Activity --}}
        <div class="space-y-6">
            {{-- Difficulty Breakdown --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
                    <h2 class="text-lg font-bold text-white tracking-tight">Difficulty Breakdown</h2>
                </div>
                <div class="space-y-4">
                    {{-- Easy --}}
                    @php
                        $easyPercent = $totalEasy > 0 ? round(($solvedEasy / $totalEasy) * 100) : 0;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-emerald-400 font-bold uppercase tracking-wider">Easy</span>
                            <span class="text-slate-400">{{ $solvedEasy }} / {{ $totalEasy }}</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-900 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $easyPercent }}%;"></div>
                        </div>
                    </div>

                    {{-- Medium --}}
                    @php
                        $mediumPercent = $totalMedium > 0 ? round(($solvedMedium / $totalMedium) * 100) : 0;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-amber-400 font-bold uppercase tracking-wider">Medium</span>
                            <span class="text-slate-400">{{ $solvedMedium }} / {{ $totalMedium }}</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-900 overflow-hidden">
                            <div class="h-full rounded-full bg-amber-500 transition-all duration-500" style="width: {{ $mediumPercent }}%;"></div>
                        </div>
                    </div>

                    {{-- Hard --}}
                    @php
                        $hardPercent = $totalHard > 0 ? round(($solvedHard / $totalHard) * 100) : 0;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-rose-400 font-bold uppercase tracking-wider">Hard</span>
                            <span class="text-slate-400">{{ $solvedHard }} / {{ $totalHard }}</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-900 overflow-hidden">
                            <div class="h-full rounded-full bg-rose-500 transition-all duration-500" style="width: {{ $hardPercent }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-5">
                    <h2 class="text-lg font-bold text-white tracking-tight">Recent Submissions</h2>
                    <a href="{{ route('submissions.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
                        View all →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentSubmissions as $submission)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-900 bg-slate-950/20 hover:border-slate-800 transition-colors">
                            <div class="space-y-1">
                                <a href="{{ route('problems.show', $submission->problem) }}" class="text-sm font-semibold text-slate-200 hover:text-indigo-400 transition-colors">
                                    {{ $submission->problem->title }}
                                </a>
                                <p class="text-[10px] text-slate-500">{{ $submission->submitted_at->diffForHumans() }}</p>
                            </div>
                            
                            @php
                                $badgeClasses = [
                                    'pending'               => 'bg-amber-500/10 text-amber-400 ring-amber-500/20',
                                    'accepted'              => 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/20',
                                    'wrong_answer'          => 'bg-rose-500/10 text-rose-400 ring-rose-500/20',
                                    'compilation_error'     => 'bg-rose-500/10 text-rose-400 ring-rose-500/20',
                                    'time_limit_exceeded'   => 'bg-rose-500/10 text-rose-400 ring-rose-500/20',
                                ];
                                $class = $badgeClasses[$submission->status] ?? 'bg-slate-500/10 text-slate-400 ring-slate-500/20';

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
                    @empty
                        <p class="text-xs text-slate-500 text-center py-4">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
