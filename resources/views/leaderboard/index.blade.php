@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Global Leaderboard & Contest Scoreboards - JudgeMate')

@section('content')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="space-y-8 max-w-7xl mx-auto px-4 pb-12">
    <!-- Header / Branding Section -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 p-8 shadow-sm dark:shadow-xl backdrop-blur-md">
        <!-- Background glows -->
        <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-100 dark:bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-violet-100 dark:bg-violet-500/10 blur-3xl"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="shrink-0 p-2 rounded-xl bg-indigo-50 dark:bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                        <i class="bi bi-trophy text-xl sm:text-2xl"></i>
                    </span>
                    <span class="truncate">Global Leaderboard</span>
                </h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    See overall user standings or browse individual live and past contest scoreboards.
                </p>
            </div>
            
            <div class="flex items-center self-start md:self-auto shrink-0 gap-3 px-4 py-2 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 text-xs font-semibold text-slate-600 dark:text-slate-400">
                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Updated Live
            </div>
        </div>
    </div>

    <!-- Dual Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Main Content (Ongoing Contest Scoreboard / Global User Rankings) -->
        <div class="lg:col-span-2 space-y-8">
            
            @if($ongoingContests->isNotEmpty())
                @php
                    $ongoing = $ongoingContests->first();
                @endphp
                
                {{-- Ongoing Live Scoreboard --}}
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="relative flex h-3 w-3 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <h2 class="text-lg font-black text-slate-900 dark:text-white tracking-tight truncate">Live Scoreboard: {{ $ongoing->title }}</h2>
                        </div>
                        <span class="inline-flex shrink-0 self-start sm:self-auto items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20">
                            Ends {{ $ongoing->ends_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Live Scoreboard Table --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 overflow-hidden shadow-sm dark:shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300 min-w-[600px]">
                                <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                                    <tr id="scoreboard-header">
                                        <th scope="col" class="px-4 py-3.5 w-16 text-center">Rank</th>
                                        <th scope="col" class="px-4 py-3.5">Contestant</th>
                                        <th scope="col" class="px-4 py-3.5 w-24 text-center">Solved</th>
                                        <th scope="col" class="px-4 py-3.5 w-28 text-center">Penalty</th>
                                    </tr>
                                </thead>
                                <tbody id="scoreboard-body" class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-950/20">
                                    <tr id="loading-row">
                                        <td colspan="4" class="px-4 py-12 text-center text-slate-500">
                                            <div class="inline-flex h-8 w-8 animate-spin rounded-full border-4 border-slate-200 dark:border-slate-700 border-t-indigo-500 mb-2"></div>
                                            <p class="text-sm font-medium">Loading live standings...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/40 border-t border-slate-200 dark:border-slate-800 text-right">
                            <a href="{{ route('contests.scoreboard', $ongoing) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">
                                View Full Live Scoreboard
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Global User Rankings Section --}}
            <div class="space-y-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <i class="bi bi-globe text-indigo-500 dark:text-indigo-400"></i>
                        All-Time User Rankings
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Overall standing based on distinct problems solved across the platform.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 overflow-hidden shadow-sm dark:shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-sm text-slate-700 dark:text-slate-300 min-w-[550px]">
                            <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <tr>
                                    <th scope="col" class="px-6 py-4 w-24">Rank</th>
                                    <th scope="col" class="px-6 py-4">Username</th>
                                    <th scope="col" class="px-6 py-4 text-center">Problems Solved</th>
                                    <th scope="col" class="px-6 py-4 text-right">Badges</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-950/20">
                                @forelse($users as $user)
                                    @php
                                        $rank = ($users->currentPage() - 1) * $users->perPage() + $loop->iteration;
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-all duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($rank === 1)
                                                <span class="inline-flex items-center gap-1.5 font-bold text-amber-500 dark:text-amber-400">
                                                    <i class="bi bi-trophy-fill text-lg"></i> 1
                                                </span>
                                            @elseif($rank === 2)
                                                <span class="inline-flex items-center gap-1.5 font-bold text-slate-400 dark:text-slate-300">
                                                    <i class="bi bi-trophy-fill text-lg"></i> 2
                                                </span>
                                            @elseif($rank === 3)
                                                <span class="inline-flex items-center gap-1.5 font-bold text-amber-700 dark:text-amber-600">
                                                    <i class="bi bi-trophy-fill text-lg"></i> 3
                                                </span>
                                            @else
                                                <span class="text-slate-500 font-mono pl-1">#{{ $rank }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-500 flex items-center justify-center font-bold text-white text-xs shadow-md">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <a href="{{ route('profile.show', $user) }}" class="font-semibold text-slate-900 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                        {{ $user->name }}
                                                    </a>
                                                    <span class="block text-xs text-slate-500 font-mono">{{ '@' . $user->username }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20 font-mono">
                                                {{ $user->solved_count ?? 0 }} Solved
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                @forelse($user->badges as $badge)
                                                    <span class="group relative inline-flex items-center justify-center h-8 w-8 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/50 text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:border-indigo-300 dark:hover:border-indigo-500/30 hover:scale-110 transition-all duration-200 cursor-pointer shadow-sm" 
                                                          title="{{ $badge->name }}: {{ $badge->description }}">
                                                        <i class="{{ $badge->icon_class }} text-sm"></i>
                                                        <span class="pointer-events-none absolute bottom-full mb-2 hidden group-hover:block z-30 w-48 p-2 rounded-lg bg-slate-900 border border-slate-700 text-left text-[11px] leading-relaxed text-slate-200 shadow-2xl">
                                                            <strong class="block text-indigo-400 text-xs font-semibold mb-0.5">{{ $badge->name }}</strong>
                                                            {{ $badge->description }}
                                                        </span>
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-400 dark:text-slate-600 italic">No badges earned</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                            <i class="bi bi-people text-3xl text-slate-400 dark:text-slate-600 block mb-2"></i>
                                            No contestants registered on the leaderboard yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/10">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Previous Contests Scoreboards Directory -->
        <div class="space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <i class="bi bi-journal-text text-indigo-500 dark:text-indigo-400"></i>
                    Contest Leaderboards
                </h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Browse final scoreboards of concluded programming events.</p>
            </div>

            <div class="space-y-3">
                @forelse($pastContests as $past)
                    <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900/10 hover:border-slate-300 dark:hover:border-slate-750 transition-all duration-200 shadow-sm dark:shadow-none">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <a href="{{ route('contests.scoreboard', $past) }}" class="font-bold text-slate-900 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors block line-clamp-1">
                                    {{ $past->title }}
                                </a>
                                <span class="text-[10px] text-slate-500 font-mono block mt-1">
                                    Ended {{ $past->ends_at->format('M d, Y') }}
                                </span>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-850 px-2 py-0.5 text-[9px] font-semibold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800">
                                Concluded
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                            <span class="font-mono text-[10px]">{{ $past->problems_count ?? $past->problems()->count() }} Challenges</span>
                            <a href="{{ route('contests.scoreboard', $past) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors inline-flex items-center gap-1">
                                Scoreboard
                                <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/10 text-slate-500 shadow-sm dark:shadow-none">
                        <i class="bi bi-journal-x text-2xl block mb-1 text-slate-400 dark:text-slate-650"></i>
                        <p class="text-xs">No past contest scoreboards available.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@if($ongoingContests->isNotEmpty())
    @php
        $ongoing = $ongoingContests->first();
    @endphp
    {{-- Ajax polling for ongoing scoreboard --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const contestId = @json($ongoing->id);
        const scoreboardBody = document.getElementById('scoreboard-body');
        const scoreboardHeader = document.getElementById('scoreboard-header');

        let problemHeaderColumnsAdded = false;

        const fetchScoreboard = async () => {
            try {
                const response = await fetch(`/contests/${contestId}/scoreboard/data`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch scoreboard.');
                }

                const data = await response.json();
                renderScoreboard(data);
            } catch (error) {
                console.error('Error polling scoreboard:', error);
            }
        };

        const renderScoreboard = (data) => {
            if (!problemHeaderColumnsAdded && data.problems.length > 0) {
                document.querySelectorAll('.dynamic-problem-header').forEach(el => el.remove());
                
                data.problems.forEach(problem => {
                    const th = document.createElement('th');
                    th.className = 'px-4 py-3 text-center dynamic-problem-header w-20';
                    th.innerHTML = `
                        <div class="font-mono font-bold text-indigo-600 dark:text-indigo-400 text-sm">${problem.label}</div>
                    `;
                    scoreboardHeader.appendChild(th);
                });
                problemHeaderColumnsAdded = true;
            }

            if (data.rows.length === 0) {
                scoreboardBody.innerHTML = `
                    <tr>
                        <td colspan="${4 + data.problems.length}" class="px-4 py-12 text-center text-slate-500">
                            <p class="text-xs text-slate-500">No participants enrolled in this ongoing contest yet.</p>
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.rows.forEach((row, index) => {
                const rank = index + 1;
                let rankClass = 'text-slate-700 dark:text-slate-300';
                let rankBadge = '';
                if (rank === 1) {
                    rankClass = 'text-amber-500 dark:text-amber-400 font-black';
                    rankBadge = '🥇';
                } else if (rank === 2) {
                    rankClass = 'text-slate-400 font-black';
                    rankBadge = '🥈';
                } else if (rank === 3) {
                    rankClass = 'text-amber-700 dark:text-amber-600 font-black';
                    rankBadge = '🥉';
                }

                let problemsCells = '';
                data.problems.forEach(problem => {
                    const detail = row.problems[problem.id];
                    if (!detail) {
                        problemsCells += `<td class="px-4 py-3 text-center text-slate-400 dark:text-slate-600 font-medium">-</td>`;
                    } else if (detail.solved) {
                        const attemptsText = detail.wrong_attempts > 0 ? `<span class="text-[9px] text-emerald-600/80 dark:text-emerald-400/80 font-bold ml-1">+${detail.wrong_attempts}</span>` : '';
                        problemsCells += `
                            <td class="px-4 py-3 text-center bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-200 dark:border-emerald-500/15">
                                <span class="font-mono text-emerald-600 dark:text-emerald-400 font-semibold text-xs">${detail.minutes}m</span>${attemptsText}
                            </td>
                        `;
                    } else {
                        const attemptsText = detail.wrong_attempts > 0 ? `<span class="text-xs font-mono font-bold">-${detail.wrong_attempts}</span>` : '';
                        problemsCells += `
                            <td class="px-4 py-3 text-center bg-rose-50 dark:bg-rose-500/5 border border-rose-200 dark:border-rose-500/15 text-rose-600 dark:text-rose-400">
                                ${attemptsText}
                            </td>
                        `;
                    }
                });

                html += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                        <td class="px-4 py-3 text-center font-mono font-bold text-sm ${rankClass}">
                            ${rankBadge ? rankBadge : rank}
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-slate-200">
                            ${row.name} <span class="text-xs text-slate-500 font-normal font-mono">(@${row.username})</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-500/15 px-2.5 py-0.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-200 dark:ring-indigo-500/20 font-mono">
                                ${row.solve_count}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-xs text-slate-500 dark:text-slate-400">
                            ${row.total_penalty}
                        </td>
                        ${problemsCells}
                    </tr>
                `;
            });

            scoreboardBody.innerHTML = html;
        };

        fetchScoreboard();
        setInterval(fetchScoreboard, 30000);
    });
    </script>
@endif
@endsection
