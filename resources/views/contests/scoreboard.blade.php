@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Scoreboard: ' . $contest->title . ' - JudgeMate')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-indigo-400 font-semibold uppercase tracking-wider">
                <a href="{{ route('contests.index') }}" class="hover:text-indigo-300">Contests</a>
                <span>/</span>
                <a href="{{ route('contests.show', $contest) }}" class="hover:text-indigo-300">{{ $contest->title }}</a>
                <span>/</span>
                <span class="text-slate-400">Scoreboard</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Live ICPC Scoreboard</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time standings for <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ $contest->title }}</span>.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-bold text-emerald-400 ring-1 ring-emerald-500/20">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Live Polling
            </span>
            <a href="{{ route('contests.scoreboard.pdf', $contest) }}" 
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150"
               title="Download ICPC Scoreboard PDF">
                <svg class="h-4 w-4 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('contests.show', $contest) }}" 
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Contest
            </a>
        </div>
    </div>

    {{-- Scoreboard Table Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300 min-w-[700px]">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">
                    <tr id="scoreboard-header">
                        <th scope="col" class="px-4 py-3 w-16 text-center">Rank</th>
                        <th scope="col" class="px-4 py-3">Contestant</th>
                        <th scope="col" class="px-4 py-3 w-24 text-center">Solved</th>
                        <th scope="col" class="px-4 py-3 w-28 text-center">Penalty</th>
                        {{-- Problem columns will be dynamically injected here --}}
                    </tr>
                </thead>
                <tbody id="scoreboard-body" class="divide-y divide-slate-200 dark:divide-slate-800/60 bg-white dark:bg-slate-950/20">
                    {{-- Row list will be dynamically updated --}}
                    <tr id="loading-row">
                        <td colspan="4" class="px-4 py-12 text-center text-slate-500">
                            <div class="inline-flex h-8 w-8 animate-spin rounded-full border-4 border-slate-200 dark:border-slate-700 border-t-indigo-500 mb-2"></div>
                            <p class="text-sm font-medium">Computing standings...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const contestId = @json($contest->id);
    const scoreboardBody = document.getElementById('scoreboard-body');
    const scoreboardHeader = document.getElementById('scoreboard-header');

    // Keep track of dynamically added problem header columns to avoid duplicates
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
                throw new Error('Failed to fetch scoreboard data.');
            }

            const data = await response.json();
            renderScoreboard(data);
        } catch (error) {
            console.error('Error polling scoreboard:', error);
        }
    };

    const renderScoreboard = (data) => {
        // 1. Render Headers once
        if (!problemHeaderColumnsAdded && data.problems.length > 0) {
            // Remove any dynamic columns if they existed
            document.querySelectorAll('.dynamic-problem-header').forEach(el => el.remove());
            
            data.problems.forEach(problem => {
                const th = document.createElement('th');
                th.className = 'px-4 py-3 text-center dynamic-problem-header w-20';
                th.innerHTML = `
                    <div class="font-mono font-bold text-indigo-600 dark:text-indigo-400 text-sm">${problem.label}</div>
                    <div class="text-[10px] text-slate-500 font-medium truncate max-w-[80px]" title="${problem.title}">${problem.title}</div>
                `;
                scoreboardHeader.appendChild(th);
            });
            problemHeaderColumnsAdded = true;
        }

        // 2. Render Rows
        if (data.rows.length === 0) {
            scoreboardBody.innerHTML = `
                <tr>
                    <td colspan="${4 + data.problems.length}" class="px-4 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">No participants enrolled</p>
                        <p class="text-xs text-slate-500 mt-1">Standing updates will appear once participants join the contest.</p>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        data.rows.forEach((row, index) => {
            const rank = index + 1;
            
            // Gold, Silver, Bronze, normal rank highlights
            let rankClass = 'text-slate-700 dark:text-slate-300';
            let rankBadge = '';
            if (rank === 1) {
                rankClass = 'text-amber-500 dark:text-amber-400 font-black';
                rankBadge = '<span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-amber-100 dark:bg-amber-400/10 text-amber-600 dark:text-amber-400 text-[10px] font-bold">🥇</span>';
            } else if (rank === 2) {
                rankClass = 'text-slate-500 dark:text-slate-400 font-black';
                rankBadge = '<span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-slate-200 dark:bg-slate-400/10 text-slate-600 dark:text-slate-400 text-[10px] font-bold">🥈</span>';
            } else if (rank === 3) {
                rankClass = 'text-orange-500 dark:text-amber-600 font-black';
                rankBadge = '<span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-orange-100 dark:bg-amber-600/10 text-orange-600 dark:text-amber-600 text-[10px] font-bold">🥉</span>';
            }

            let problemsCells = '';
            data.problems.forEach(problem => {
                const detail = row.problems[problem.id];
                if (!detail) {
                    // Not attempted
                    problemsCells += `<td class="px-4 py-3 text-center text-slate-400 dark:text-slate-600 font-medium">-</td>`;
                } else if (detail.solved) {
                    // Solved: show time and attempts
                    const attemptsText = detail.wrong_attempts > 0 ? `<div class="text-[10px] text-emerald-600/80 dark:text-emerald-400/80 font-bold">+${detail.wrong_attempts}</div>` : '';
                    problemsCells += `
                        <td class="px-4 py-3 text-center bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/15">
                            <div class="font-mono text-emerald-600 dark:text-emerald-400 font-semibold text-xs">${detail.minutes}m</div>
                            ${attemptsText}
                        </td>
                    `;
                } else {
                    // Attempted but not solved
                    const attemptsText = detail.wrong_attempts > 0 ? `<div class="text-xs font-mono font-bold">-${detail.wrong_attempts}</div>` : '';
                    problemsCells += `
                        <td class="px-4 py-3 text-center bg-rose-50 dark:bg-rose-500/5 border border-rose-100 dark:border-rose-500/15 text-rose-500 dark:text-rose-400">
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
                    <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">
                        ${row.name}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center rounded-full bg-indigo-100 dark:bg-indigo-500/15 px-2.5 py-0.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20">
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

    // First load
    fetchScoreboard();

    // Auto refresh every 30 seconds
    setInterval(fetchScoreboard, 30000);
});
</script>
@endsection
