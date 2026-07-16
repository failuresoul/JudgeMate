@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Global Leaderboard - JudgeMate')

@section('content')
<!-- Bootstrap Icons for Badges and Trophies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header / Branding Section -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/30 p-8 shadow-xl backdrop-blur-md">
        <!-- Background glows -->
        <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-violet-500/10 blur-3xl"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    <span class="p-2 rounded-xl bg-indigo-600/10 text-indigo-400 border border-indigo-500/20">
                        <i class="bi bi-trophy text-2xl"></i>
                    </span>
                    Global Leaderboard
                </h1>
                <p class="mt-2 text-sm text-slate-400">
                    See the top-performing competitive programmers on JudgeMate ranked by distinct problems solved.
                </p>
            </div>
            
            <div class="flex items-center gap-3 px-4 py-2 rounded-2xl border border-slate-800 bg-slate-950/60 text-xs font-semibold text-slate-400">
                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Updated Live
            </div>
        </div>
    </div>

    <!-- Leaderboard Table Card -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-800 text-left text-sm text-slate-300">
                <thead class="bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-24">Rank</th>
                        <th scope="col" class="px-6 py-4">Username</th>
                        <th scope="col" class="px-6 py-4 text-center">Problems Solved</th>
                        <th scope="col" class="px-6 py-4 text-right">Badges</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                    @forelse($users as $user)
                        @php
                            $rank = ($users->currentPage() - 1) * $users->perPage() + $loop->iteration;
                        @endphp
                        <tr class="hover:bg-slate-900/20 transition-all duration-150">
                            <!-- Rank -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($rank === 1)
                                    <span class="inline-flex items-center gap-1.5 font-bold text-amber-400">
                                        <i class="bi bi-trophy-fill text-lg"></i> 1
                                    </span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center gap-1.5 font-bold text-slate-300">
                                        <i class="bi bi-trophy-fill text-lg"></i> 2
                                    </span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center gap-1.5 font-bold text-amber-600">
                                        <i class="bi bi-trophy-fill text-lg"></i> 3
                                    </span>
                                @else
                                    <span class="text-slate-500 font-mono pl-1">#{{ $rank }}</span>
                                @endif
                            </td>

                            <!-- Username -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-500 flex items-center justify-center font-bold text-white text-xs shadow-md">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('profile.show', $user) }}" class="font-semibold text-slate-200 hover:text-indigo-400 transition-colors">
                                            {{ $user->name }}
                                        </a>
                                        <span class="block text-xs text-slate-500 font-mono">{{ '@' . $user->username }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Problems Solved -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20 font-mono">
                                    {{ $user->solved_count ?? 0 }} Solved
                                </span>
                            </td>

                            <!-- Badges -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @forelse($user->badges as $badge)
                                        <span class="group relative inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-800/80 border border-slate-700/50 text-indigo-400 hover:text-indigo-300 hover:border-indigo-500/30 hover:scale-110 transition-all duration-200 cursor-pointer" 
                                              title="{{ $badge->name }}: {{ $badge->description }}">
                                            <i class="{{ $badge->icon_class }} text-sm"></i>
                                            
                                            <!-- Custom elegant CSS Tooltip (safeguard for default title hover) -->
                                            <span class="pointer-events-none absolute bottom-full mb-2 hidden group-hover:block z-30 w-48 p-2 rounded-lg bg-slate-900 border border-slate-700 text-left text-[11px] leading-relaxed text-slate-200 shadow-2xl">
                                                <strong class="block text-indigo-400 text-xs font-semibold mb-0.5">{{ $badge->name }}</strong>
                                                {{ $badge->description }}
                                            </span>
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-600 italic">No badges earned</span>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <i class="bi bi-people text-3xl text-slate-600 block mb-2"></i>
                                No contestants registered on the leaderboard yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
