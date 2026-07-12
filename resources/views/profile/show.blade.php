@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', $user->name . ' - Profile - JudgeMate')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header / User Info Card -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/30 p-8 shadow-xl backdrop-blur-md">
        <!-- Background glows -->
        <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-violet-500/10 blur-3xl"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <!-- User Avatar / Initial -->
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center font-extrabold text-white text-3xl shadow-lg shadow-indigo-500/20">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $user->name }}</h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2 text-sm text-slate-400">
                        <span class="text-indigo-400 font-semibold font-mono">{{ '@' . $user->username }}</span>
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-700"></span>
                        <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            @if(auth()->id() === $user->id)
                <div>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 border border-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-800 transition-all">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Total Accepted Count Card -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-lg flex items-center gap-5">
            <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-sm font-medium text-slate-400">Accepted Submissions</span>
                <span class="text-3xl font-extrabold text-white mt-1">{{ $user->accepted_submissions_count ?? 0 }}</span>
            </div>
        </div>

        <!-- Distinct Solved Count Card -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 shadow-lg flex items-center gap-5">
            <div class="h-12 w-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div>
                <span class="block text-sm font-medium text-slate-400">Distinct Problems Solved</span>
                <span class="text-3xl font-extrabold text-white mt-1">{{ $solvedCount ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Submissions Section -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-white tracking-tight">Recent Submissions</h2>
        
        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-slate-800 text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th scope="col" class="px-6 py-4">Problem</th>
                            <th scope="col" class="px-6 py-4">Language</th>
                            <th scope="col" class="px-6 py-4">Verdict</th>
                            <th scope="col" class="px-6 py-4">Verdict Message</th>
                            <th scope="col" class="px-6 py-4 text-right">Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                        @forelse($recentSubmissions as $submission)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-200">
                                    <a href="{{ route('problems.show', $submission->problem) }}" class="hover:text-indigo-400 transition-colors">
                                        {{ $submission->problem->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs uppercase bg-slate-800 px-2.5 py-1 rounded-md text-slate-300 ring-1 ring-slate-700/50">
                                        {{ $submission->language }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $class }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400 font-mono text-xs">
                                    {{ $submission->verdict_message ?? 'Evaluating...' }}
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-right whitespace-nowrap">
                                    {{ $submission->submitted_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    No submissions found yet. Start solving problems to see your history!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
