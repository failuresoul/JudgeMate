@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Contests - JudgeMate')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 pb-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Contests</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Participate in scheduled programming contests, compete with peers, and sharpen your coding skills.</p>
        </div>
        @role('ProblemSetter')
        <div class="flex-shrink-0">
            <a href="{{ route('contests.create') }}" 
               class="inline-flex items-center gap-2 rounded-xl py-3 px-5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/10 hover:shadow-indigo-500/25 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98]"
               style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Contest
            </a>
        </div>
        @endrole
    </div>

    {{-- Local Contests Sections --}}
    @php
        $now = now();
        $activeContests = [];
        $upcomingContests = [];
        $pastContests = [];

        foreach($contests as $contest) {
            if (!$contest->is_approved) {
                // Pending contests go to upcoming/review lists
                $upcomingContests[] = $contest;
            } elseif (!$contest->is_active) {
                $pastContests[] = $contest;
            } elseif ($now->lt($contest->starts_at)) {
                $upcomingContests[] = $contest;
            } elseif ($now->gt($contest->ends_at)) {
                $pastContests[] = $contest;
            } else {
                $activeContests[] = $contest;
            }
        }
    @endphp

    {{-- Section 1: Active & Running Contests --}}
    @if(!empty($activeContests))
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <h2 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Running Now</h2>
            </div>
            <div class="grid gap-4">
                @foreach($activeContests as $contest)
                    @include('contests.partials.contest_row_card', ['contest' => $contest, 'type' => 'active'])
                @endforeach
            </div>
        </div>
    @endif

    {{-- Section 2: Upcoming Contests --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2">
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-200">Upcoming Contests</h2>
            <span class="text-xs text-slate-500 font-mono">Future Schedules</span>
        </div>
        @if(empty($upcomingContests) && empty($activeContests))
            <div class="flex flex-col items-center justify-center gap-3 py-16 text-center rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/10 shadow-sm dark:shadow-none">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <svg class="h-6 w-6 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500">No upcoming contests scheduled.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($upcomingContests as $contest)
                    @include('contests.partials.contest_row_card', ['contest' => $contest, 'type' => 'upcoming'])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Section 3: Past Contests --}}
    @if(!empty($pastContests))
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-200">Past Contests</h2>
                <span class="text-xs text-slate-500 font-mono">Ended Events</span>
            </div>
            <div class="grid gap-4">
                @foreach($pastContests as $contest)
                    @include('contests.partials.contest_row_card', ['contest' => $contest, 'type' => 'past'])
                @endforeach
            </div>
        </div>
    @endif

    {{-- External Contests Section --}}
    @if(!empty($externalContests))
        <div class="space-y-4 pt-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-2">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-200">Upcoming Contests on Other Platforms</h2>
                <span class="text-xs text-slate-500 font-mono">Global Aggregator</span>
            </div>
            
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950/20 overflow-hidden shadow-sm dark:shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-900/40 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-4">Contest Name</th>
                                <th scope="col" class="px-6 py-4">Platform</th>
                                <th scope="col" class="px-6 py-4">Start Time</th>
                                <th scope="col" class="px-6 py-4">Duration</th>
                                <th scope="col" class="px-6 py-4 text-right">Link</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 bg-transparent">
                            @foreach($externalContests as $ext)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors duration-150">
                                    <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-200">
                                        {{ $ext['name'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badgeColors = [
                                                'Codeforces' => 'text-indigo-600 bg-indigo-50 border-indigo-200 dark:text-indigo-400 dark:bg-indigo-500/10 dark:ring-indigo-500/20 dark:border-indigo-500/25',
                                                'CodeChef'   => 'text-amber-600 bg-amber-50 border-amber-200 dark:text-amber-400 dark:bg-amber-500/10 dark:ring-amber-500/20 dark:border-amber-500/25',
                                                'AtCoder'    => 'text-rose-600 bg-rose-50 border-rose-200 dark:text-rose-400 dark:bg-rose-500/10 dark:ring-rose-500/20 dark:border-rose-500/25',
                                                'LeetCode'   => 'text-emerald-600 bg-emerald-50 border-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:ring-emerald-500/20 dark:border-emerald-500/25',
                                            ];
                                            $badge = $badgeColors[$ext['site']] ?? 'text-slate-600 bg-slate-100 border-slate-200 dark:text-slate-400 dark:bg-slate-500/10 dark:ring-slate-500/20 dark:border-slate-500/25';
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 border {{ $badge }}">
                                            {{ $ext['site'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400">
                                        {{ $ext['start_time'] }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400">
                                        {{ $ext['duration'] }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ $ext['url'] }}" target="_blank" rel="noopener noreferrer" 
                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                            View External
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
