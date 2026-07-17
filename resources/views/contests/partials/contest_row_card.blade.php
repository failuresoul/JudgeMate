<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950/40 backdrop-blur-xl shadow-sm hover:shadow-md dark:shadow-lg dark:hover:border-slate-700/60 transition-all duration-200 group">
    {{-- Left column: Title & Metadata --}}
    <div class="space-y-2">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('contests.show', $contest) }}" class="text-lg font-bold text-slate-900 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                {{ $contest->title }}
            </a>
            
            {{-- Status Badge --}}
            @php
                $now = now();
                $starts = $contest->starts_at;
                $ends = $contest->ends_at;

                if (!$contest->is_approved) {
                    $badgeClass = 'bg-rose-50 text-rose-600 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/20';
                    $badgeText = 'Pending Approval';
                } elseif (!$contest->is_active) {
                    $badgeClass = 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:ring-slate-500/20';
                    $badgeText = 'Disabled';
                } elseif ($now->lt($starts)) {
                    $badgeClass = 'bg-amber-50 text-amber-600 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/20';
                    $badgeText = 'Upcoming';
                } elseif ($now->gt($ends)) {
                    $badgeClass = 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:ring-slate-500/20';
                    $badgeText = 'Ended';
                } else {
                    $badgeClass = 'bg-emerald-50 text-emerald-600 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20';
                    $badgeText = 'Running';
                }
            @endphp
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">
                {{ $badgeText }}
            </span>
        </div>
        
        {{-- Metadata badges --}}
        <div class="flex flex-wrap items-center gap-y-1.5 gap-x-4 text-xs text-slate-500 dark:text-slate-400">
            <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                By <span class="font-medium text-slate-700 dark:text-slate-300">{{ $contest->creator->name ?? 'System' }}</span>
            </span>
            <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ $contest->problems_count }} {{ Str::plural('Challenge', $contest->problems_count) }}
            </span>
            <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ $contest->participants_count }} {{ Str::plural('Participant', $contest->participants_count) }}
            </span>
        </div>
    </div>

    {{-- Center column: Schedule & Relative Time --}}
    <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
        <div class="flex flex-col">
            <span class="text-xs text-slate-500 uppercase tracking-wider font-semibold">
                @if($type === 'active')
                    Running
                @elseif($type === 'upcoming')
                    Starts
                @else
                    Ended
                @endif
            </span>
            <span class="text-slate-700 dark:text-slate-200 font-mono text-xs mt-1">
                @if($type === 'active')
                    Ends in {{ $contest->ends_at->diffForHumans(null, true) }}
                @elseif($type === 'upcoming')
                    {{ $contest->starts_at->diffForHumans() }}
                @else
                    {{ $contest->ends_at->diffForHumans() }}
                @endif
            </span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">
                {{ $contest->starts_at->format('M d, H:i') }} - {{ $contest->ends_at->format('H:i') }}
            </span>
        </div>
    </div>

    {{-- Right column: Action buttons --}}
    <div class="flex flex-wrap items-center gap-2 self-start md:self-center">
        {{-- Scoreboard Action --}}
        @if($contest->is_approved)
            @if($type === 'active')
                {{-- Running contest: Big prominent Scoreboard button --}}
                <a href="{{ route('contests.scoreboard', $contest) }}" 
                   class="inline-flex items-center gap-2 rounded-xl py-2.5 px-5 text-sm font-extrabold text-white shadow-md shadow-indigo-500/15 hover:shadow-indigo-500/25 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98]"
                   style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Scoreboard
                </a>
            @else
                {{-- Upcoming/Past contest: Regular Scoreboard button --}}
                <a href="{{ route('contests.scoreboard', $contest) }}" 
                   class="inline-flex items-center gap-1.5 rounded-xl bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Scoreboard
                </a>
            @endif
        @endif

        {{-- Admin Approve Action --}}
        @if(!$contest->is_approved)
            @role('Admin')
            <form method="POST" action="{{ route('contests.approve', $contest) }}" class="inline-block">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/25 px-4 py-2 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-colors duration-150">
                    Approve
                </button>
            </form>
            @endrole
        @endif

        {{-- Register / Enter Action --}}
        @if($contest->is_approved)
            @if(auth()->user()->hasRole('Contestant'))
                @if($contest->participants->contains(auth()->id()))
                    <a href="{{ route('contests.show', $contest) }}" 
                       class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-500/10 dark:border-emerald-500/25 px-4 py-2 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 hover:border-emerald-300 dark:hover:border-emerald-500/40 transition-colors duration-150">
                        Enter
                    </a>
                @else
                    <form method="POST" action="{{ route('contests.register', $contest) }}" class="inline-block">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 border border-indigo-200 dark:bg-indigo-600/15 dark:border-indigo-500/25 px-4 py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-600/30 hover:border-indigo-300 dark:hover:border-indigo-500/40 transition-colors duration-150">
                            Register
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('contests.show', $contest) }}" 
                   class="inline-flex items-center gap-1 rounded-xl bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
                    Enter
                </a>
            @endif
        @else
            @if(auth()->id() === $contest->created_by || auth()->user()->hasRole('Admin'))
                <a href="{{ route('contests.show', $contest) }}" 
                   class="inline-flex items-center gap-1 rounded-xl bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
                    View Details
                </a>
            @endif
        @endif

        {{-- Edit/Delete Actions --}}
        @if(auth()->id() === $contest->created_by || auth()->user()->hasRole('Admin'))
            <a href="{{ route('contests.edit', $contest) }}" 
               class="inline-flex items-center gap-1 rounded-xl bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
                Edit
            </a>
            <form method="POST" action="{{ route('contests.destroy', $contest) }}" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this contest?')"
                        class="inline-flex items-center gap-1 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 px-4 py-2 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20 hover:border-rose-300 dark:hover:border-rose-500/40 transition-colors duration-150">
                    Delete
                </button>
            </form>
        @endif
    </div>
</div>
