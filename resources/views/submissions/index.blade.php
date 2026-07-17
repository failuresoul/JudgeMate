@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'My Submissions - JudgeMate')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                @if(auth()->user()->hasRole('Admin'))
                    All Submissions
                @elseif(auth()->user()->hasRole('ProblemSetter'))
                    Problem Submissions
                @else
                    My Submissions
                @endif
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                @if(auth()->user()->hasRole('Admin'))
                    Monitor all contestant code submissions across the platform.
                @elseif(auth()->user()->hasRole('ProblemSetter'))
                    Monitor contestant submissions for the problems you created.
                @else
                    Track and monitor status of your solution evaluations in real-time.
                @endif
            </p>
        </div>
    </div>

    {{-- Submissions Table Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 overflow-hidden shadow-sm dark:shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-sm text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    <tr>
                        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('ProblemSetter'))
                            <th scope="col" class="px-4 py-3">Contestant</th>
                        @endif
                        <th scope="col" class="px-4 py-3">Problem</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Language</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">Status</th>
                        <th scope="col" class="px-4 py-3 w-full">Verdict</th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap text-right">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-950/20">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('ProblemSetter'))
                                <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-200">
                                    {{ $submission->user->name ?? 'Unknown' }}
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $submission->user->email ?? '' }}</div>
                                </td>
                            @endif
                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-200">
                                <a href="{{ route('problems.show', $submission->problem) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors text-base font-semibold">
                                    {{ $submission->problem->title }}
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-mono text-xs uppercase bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-md text-slate-700 dark:text-slate-300 ring-1 ring-slate-200 dark:ring-slate-700/50">
                                    {{ $submission->language }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $badgeClasses = [
                                        'pending'               => 'bg-amber-50 text-amber-600 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20',
                                        'accepted'              => 'bg-emerald-50 text-emerald-600 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20',
                                        'wrong_answer'          => 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20',
                                        'compilation_error'     => 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20',
                                        'time_limit_exceeded'   => 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20',
                                    ];
                                    $class = $badgeClasses[$submission->status] ?? 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:ring-slate-500/20';

                                    $statusLabels = [
                                        'pending'               => 'Pending',
                                        'accepted'              => 'Accepted',
                                        'wrong_answer'          => 'Wrong Answer',
                                        'compilation_error'     => 'Compilation Error',
                                        'time_limit_exceeded'   => 'Time Limit Exceeded',
                                    ];
                                    $label = $statusLabels[$submission->status] ?? ucfirst(str_replace('_', ' ', $submission->status));
                                @endphp
                                <span class="status-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $class }}"
                                      data-submission-id="{{ $submission->id }}"
                                      data-status="{{ $submission->status }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300 font-mono text-xs verdict-text" data-submission-id="{{ $submission->id }}">
                                {{ $submission->verdict_message ?? 'Evaluating...' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-500 dark:text-slate-400 text-right">
                                {{ $submission->submitted_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('ProblemSetter')) ? 6 : 5 }}" class="px-6 py-10 text-center text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">No submissions found</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Submit code to a problem to see your history here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/30">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pollPendingSubmissions = () => {
        const pendingBadges = document.querySelectorAll('.status-badge[data-status="pending"]');
        
        pendingBadges.forEach(badge => {
            const submissionId = badge.getAttribute('data-submission-id');
            const url = `/submissions/${submissionId}/status`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                if (data.status !== 'pending') {
                    badge.setAttribute('data-status', data.status);
                    
                    let badgeClass = '';
                    let statusLabel = '';

                    switch (data.status) {
                        case 'accepted':
                            badgeClass = 'bg-emerald-50 text-emerald-600 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20';
                            statusLabel = 'Accepted';
                            break;
                        case 'wrong_answer':
                            badgeClass = 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20';
                            statusLabel = 'Wrong Answer';
                            break;
                        case 'compilation_error':
                            badgeClass = 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20';
                            statusLabel = 'Compilation Error';
                            break;
                        case 'time_limit_exceeded':
                            badgeClass = 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20';
                            statusLabel = 'Time Limit Exceeded';
                            break;
                        default:
                            badgeClass = 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:ring-slate-500/20';
                            statusLabel = data.status.charAt(0).toUpperCase() + data.status.slice(1).replace('_', ' ');
                    }

                    badge.className = `status-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset ${badgeClass}`;
                    badge.innerHTML = statusLabel;

                    // Update verdict text
                    const verdictTd = document.querySelector(`.verdict-text[data-submission-id="${submissionId}"]`);
                    if (verdictTd) {
                        verdictTd.innerHTML = data.verdict_message || 'Evaluation completed.';
                    }
                }
            })
            .catch(err => console.error('Error fetching status for submission ' + submissionId, err));
        });
    };

    // Set polling interval of 3 seconds
    const intervalId = setInterval(() => {
        const pending = document.querySelectorAll('.status-badge[data-status="pending"]');
        if (pending.length === 0) {
            clearInterval(intervalId);
        } else {
            pollPendingSubmissions();
        }
    }, 3000);
});
</script>
@endsection
