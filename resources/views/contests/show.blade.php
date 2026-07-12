@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', $contest->title . ' - JudgeMate')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    {{-- Back Header --}}
    <div class="flex items-center justify-between pb-5">
        <div class="flex items-center gap-2">
            <a href="{{ route('contests.index') }}" 
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-150">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back to Contests
            </a>
            @if($contest->is_approved && now()->gte($contest->starts_at))
                <a href="{{ route('contests.scoreboard', $contest) }}" 
                   class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-550/20 bg-indigo-500/10 px-3.5 py-2 text-sm font-semibold text-indigo-400 hover:bg-indigo-500/20 hover:text-indigo-300 transition-all duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                    Scoreboard
                </a>
            @endif
        </div>
        @role('Admin')
        <a href="{{ route('contests.edit', $contest) }}" 
           class="inline-flex items-center gap-1.5 rounded-lg py-2.5 px-4 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30"
           style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            Edit Contest
        </a>
        @endrole
    </div>

    {{-- Main Container --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Details & Problems --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 space-y-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $contest->title }}</h1>
                    <p class="text-sm text-slate-400 mt-2 leading-relaxed">
                        {{ $contest->description ?? 'No description provided for this contest.' }}
                    </p>
                </div>
            </div>

            {{-- Problems --}}
            <div>
                <h2 class="text-xl font-bold text-white mb-4">Contest Problems</h2>
                @php
                    $now = now();
                    $isCreator = auth()->id() === $contest->created_by;
                    $isAdmin = auth()->user()->hasRole('Admin');
                    $hasStarted = $now->gte($contest->starts_at);
                    $canSeeProblems = $isCreator || $isAdmin || $hasStarted;
                @endphp

                @if($canSeeProblems)
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden shadow-xl">
                        @if($contest->problems->isEmpty())
                            <p class="text-sm text-slate-500 text-center py-10">No problems have been added to this contest yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-slate-300">
                                    <thead class="bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400">
                                        <tr class="border-b border-slate-800">
                                            <th scope="col" class="px-4 py-3">#</th>
                                            <th scope="col" class="px-4 py-3">Title</th>
                                            <th scope="col" class="px-4 py-3">Difficulty</th>
                                            <th scope="col" class="px-4 py-3">Author</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                                        @foreach($contest->problems as $problem)
                                            <tr class="hover:bg-slate-900/20 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap font-mono text-indigo-400 font-bold text-lg">
                                                    {{ $problem->pivot->label ?? '?' }}
                                                </td>
                                                <td class="px-4 py-3 font-semibold text-slate-200">
                                                    <a href="{{ route('problems.show', [$problem, 'contest_id' => $contest->id]) }}" class="hover:text-indigo-400 transition-colors text-base font-bold">
                                                        {{ $problem->title }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @php
                                                        $difficultyColors = [
                                                            'easy'   => 'text-emerald-400 bg-emerald-500/10 ring-emerald-500/20',
                                                            'medium' => 'text-amber-400 bg-amber-500/10 ring-amber-500/20',
                                                            'hard'   => 'text-rose-400 bg-rose-500/10 ring-rose-500/20',
                                                        ];
                                                        $color = $difficultyColors[$problem->difficulty] ?? 'text-slate-400 bg-slate-500/10 ring-slate-500/20';
                                                    @endphp
                                                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider ring-1 {{ $color }}">
                                                        {{ $problem->difficulty }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-slate-400">
                                                    {{ $problem->creator->name ?? 'System' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-850 bg-slate-950/40 p-8 text-center space-y-4 shadow-xl">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900 border border-slate-800 text-indigo-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-200">Problems are Locked</h3>
                        <p class="text-sm text-slate-500 max-w-sm mx-auto">This contest has not started yet. Problems and challenges will be revealed as soon as the contest begins.</p>
                        <div class="text-xs text-indigo-400 font-mono">Starts {{ $contest->starts_at->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Contest Info Panel --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-400">Contest Info</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-slate-500 uppercase font-semibold">Starts At</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5">
                            {{ $contest->starts_at ? $contest->starts_at->format('M d, Y h:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 uppercase font-semibold">Ends At</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5">
                            {{ $contest->ends_at ? $contest->ends_at->format('M d, Y h:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 uppercase font-semibold">Duration</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5">
                            @if($contest->starts_at && $contest->ends_at)
                                @php
                                    $diff = $contest->starts_at->diff($contest->ends_at);
                                    echo "{$diff->h} hours " . ($diff->i > 0 ? " {$diff->i} mins" : "");
                                @endphp
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 uppercase font-semibold">Total Problems</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5 font-mono">
                            {{ $contest->problems->count() }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 uppercase font-semibold">Participants Enrolled</span>
                        <p class="text-sm font-semibold text-slate-200 mt-0.5 font-mono">
                            {{ $contest->participants->count() }}
                        </p>
                    </div>
                </div>
            </div>

            @php
                $hasStarted = now()->gte($contest->starts_at);
            @endphp

            @if($hasStarted)
                {{-- Leaderboard Standings Panel --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-400">Leaderboard</h3>
                        <span class="inline-flex items-center rounded bg-indigo-500/10 px-1.5 py-0.5 text-[10px] font-bold text-indigo-400 ring-1 ring-indigo-500/20">Standings</span>
                    </div>
                    @if(empty($scoreboardStandings))
                        <p class="text-xs text-slate-500">No standings computed yet.</p>
                    @else
                        <div class="space-y-3 max-h-68 overflow-y-auto divide-y divide-slate-800/60">
                            @foreach($scoreboardStandings as $index => $standing)
                                <div class="flex items-center justify-between text-xs py-2 {{ $index > 0 ? 'border-t border-slate-900/30' : '' }}">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-slate-500 w-4">{{ $index + 1 }}</span>
                                        <span class="font-semibold text-slate-300">{{ $standing['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 font-mono text-[10px]">
                                        <span class="text-emerald-400 font-bold" title="Solved Problems">{{ $standing['solve_count'] }} AC</span>
                                        <span class="text-slate-500" title="Penalty Minutes">{{ $standing['total_penalty'] }}m</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                {{-- Participants List --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-indigo-400">Participants</h3>
                    @if($contest->participants->isEmpty())
                        <p class="text-xs text-slate-500">No participants registered yet.</p>
                    @else
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @foreach($contest->participants as $participant)
                                <div class="flex items-center justify-between text-xs py-1">
                                    <span class="font-medium text-slate-300">{{ $participant->name }}</span>
                                    <span class="text-slate-500 font-mono">
                                        {{ $participant->participant->joined_at ? \Carbon\Carbon::parse($participant->participant->joined_at)->diffForHumans() : 'Joined' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
