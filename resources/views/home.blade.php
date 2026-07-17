@extends('layouts.app')

@section('title', 'Dashboard - JudgeMate')

@section('content')
<div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 items-start">
    
    {{-- Left Column: Main Content --}}
    <div class="w-full lg:w-3/4 flex flex-col gap-6">
        
        {{-- Live Contest / Welcome Banner --}}
        <div class="rounded-xl border border-indigo-200 dark:border-indigo-900 bg-indigo-50 dark:bg-indigo-900/20 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wider">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                        </span>
                        Dashboard Active
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                        Welcome back, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-400 max-w-2xl">
                        Your JudgeMate workspace is ready. Solve problems, submit your code, and track your progress in real-time.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('problems.index') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                        Solve Problems
                    </a>
                </div>
            </div>
        </div>

        {{-- Recommended Problems (Styled like announcements) --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Recommended Problems</h2>
                <a href="{{ route('problems.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">All problems →</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recommendedProblems as $problem)
                    <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <a href="{{ route('problems.show', $problem) }}" class="text-base font-medium text-indigo-600 dark:text-indigo-400 group-hover:underline">
                                    {{ $problem->title }}
                                </a>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Added by admin • Tags: {{ $problem->difficulty }}
                                </p>
                            </div>
                            @php
                                $diffColors = [
                                    'easy'   => 'text-emerald-700 bg-emerald-50 ring-emerald-600/20 dark:text-emerald-400 dark:bg-emerald-400/10 dark:ring-emerald-400/20',
                                    'medium' => 'text-amber-700 bg-amber-50 ring-amber-600/20 dark:text-amber-400 dark:bg-amber-400/10 dark:ring-amber-400/20',
                                    'hard'   => 'text-red-700 bg-red-50 ring-red-600/20 dark:text-red-400 dark:bg-red-400/10 dark:ring-red-400/20',
                                ];
                                $color = $diffColors[$problem->difficulty] ?? 'text-slate-700 bg-slate-50 ring-slate-600/20';
                            @endphp
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $color }} capitalize">
                                {{ $problem->difficulty }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                        You have solved all recommended problems. Great job!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Platform Guidelines --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-800 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Platform Guidelines</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                    <div class="space-y-2">
                        <h4 class="font-semibold text-slate-900 dark:text-slate-200">1. Select Language</h4>
                        <p class="text-slate-600 dark:text-slate-400">Choose from C++, Python, or Java. Make sure your local setup matches the judge environment.</p>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-slate-900 dark:text-slate-200">2. Input / Output</h4>
                        <p class="text-slate-600 dark:text-slate-400">Use standard input/output (`cin/cout`, `input()`, `Scanner`). Do not print prompt messages.</p>
                    </div>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-slate-900 dark:text-slate-200">3. Constraints</h4>
                        <p class="text-slate-600 dark:text-slate-400">Pay attention to time limits (usually 1s or 2s) to avoid TLE, and space limits for MLE.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Sidebar --}}
    <div class="w-full lg:w-1/4 flex flex-col gap-6">
        
        {{-- Your Stats Widget --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-800 px-4 py-3">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Your Stats</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Solved Problems</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $solvedCount }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Total Submissions</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $totalSubmissions }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Acceptance Rate</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $acceptanceRate }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Pending</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $pendingCount }}</span>
                </div>
            </div>
        </div>

        {{-- Difficulty Breakdown Widget --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-800 px-4 py-3">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Difficulty Breakdown</h3>
            </div>
            <div class="p-4 space-y-5">
                {{-- Easy --}}
                @php $easyPercent = $totalEasy > 0 ? round(($solvedEasy / $totalEasy) * 100) : 0; @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span class="text-emerald-600 dark:text-emerald-400">Easy</span>
                        <span class="text-slate-500 dark:text-slate-400">{{ $solvedEasy }} / {{ $totalEasy }}</span>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $easyPercent }}%;"></div>
                    </div>
                </div>

                {{-- Medium --}}
                @php $mediumPercent = $totalMedium > 0 ? round(($solvedMedium / $totalMedium) * 100) : 0; @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span class="text-amber-600 dark:text-amber-400">Medium</span>
                        <span class="text-slate-500 dark:text-slate-400">{{ $solvedMedium }} / {{ $totalMedium }}</span>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full rounded-full bg-amber-500" style="width: {{ $mediumPercent }}%;"></div>
                    </div>
                </div>

                {{-- Hard --}}
                @php $hardPercent = $totalHard > 0 ? round(($solvedHard / $totalHard) * 100) : 0; @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span class="text-red-600 dark:text-red-400">Hard</span>
                        <span class="text-slate-500 dark:text-slate-400">{{ $solvedHard }} / {{ $totalHard }}</span>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full rounded-full bg-red-500" style="width: {{ $hardPercent }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Submissions Widget --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-800 px-4 py-3 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Recent Activity</h3>
                <a href="{{ route('submissions.index') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">All →</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentSubmissions as $submission)
                    <div class="p-4 flex items-center justify-between gap-2">
                        <div class="truncate">
                            <a href="{{ route('problems.show', $submission->problem) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline truncate block">
                                {{ $submission->problem->title }}
                            </a>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $submission->submitted_at->diffForHumans() }}</p>
                        </div>
                        @php
                            $badgeClasses = [
                                'pending'               => 'bg-slate-100 text-slate-600 ring-slate-500/10 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-500/20',
                                'accepted'              => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-400/10 dark:text-emerald-400 dark:ring-emerald-400/20',
                                'wrong_answer'          => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/20',
                                'compilation_error'     => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/20',
                                'time_limit_exceeded'   => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-400 dark:ring-amber-400/20',
                            ];
                            $class = $badgeClasses[$submission->status] ?? 'bg-slate-50 text-slate-600 ring-slate-500/10';
                            
                            $statusLabels = [
                                'pending'               => 'Pending',
                                'accepted'              => 'Accepted',
                                'wrong_answer'          => 'WA',
                                'compilation_error'     => 'CE',
                                'time_limit_exceeded'   => 'TLE',
                            ];
                            $label = $statusLabels[$submission->status] ?? 'ERR';
                        @endphp
                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium ring-1 ring-inset {{ $class }} flex-shrink-0">
                            {{ $label }}
                        </span>
                    </div>
                @empty
                    <div class="p-4 text-center text-xs text-slate-500 dark:text-slate-400">
                        No submissions yet.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
