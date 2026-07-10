@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Contests - JudgeMate')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-800 pb-5">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Contests</h1>
            <p class="mt-1 text-sm text-slate-400">Join active coding contests or solve past challenges.</p>
        </div>
        @role('ProblemSetter')
        <a href="{{ route('contests.create') }}" 
           class="inline-flex items-center gap-1.5 rounded-lg py-2.5 px-4 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98]"
           style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            Create Contest
        </a>
        @endrole
    </div>

    {{-- Local Hosted Contests list --}}
    <div>
        <h2 class="text-xl font-bold text-white mb-4">Hosted Contests</h2>
        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden shadow-xl">
            @if($contests->isEmpty())
                <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-800">
                        <svg class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">No hosted contests found.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-300">
                        <thead class="bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <tr class="border-b border-slate-800">
                                <th scope="col" class="px-6 py-4">Title</th>
                                <th scope="col" class="px-6 py-4">Author</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Starts At</th>
                                <th scope="col" class="px-6 py-4">Ends At</th>
                                <th scope="col" class="px-6 py-4">Challenges</th>
                                <th scope="col" class="px-6 py-4">Participants</th>
                                <th scope="col" class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                            @foreach($contests as $contest)
                                <tr class="hover:bg-slate-900/20 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-200">
                                        <a href="{{ route('contests.show', $contest) }}" class="hover:text-indigo-400 transition-colors text-base font-semibold">
                                            {{ $contest->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400 text-xs">
                                        {{ $contest->creator->name ?? 'System' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $now = now();
                                            $starts = $contest->starts_at;
                                            $ends = $contest->ends_at;

                                            if (!$contest->is_approved) {
                                                $statusClass = 'bg-rose-500/10 text-rose-400 ring-rose-500/20';
                                                $statusText = 'Pending Approval';
                                            } elseif (!$contest->is_active) {
                                                $statusClass = 'bg-slate-500/10 text-slate-400 ring-slate-500/20';
                                                $statusText = 'Disabled';
                                            } elseif ($now->lt($starts)) {
                                                $statusClass = 'bg-amber-500/10 text-amber-400 ring-amber-500/20';
                                                $statusText = 'Upcoming';
                                            } elseif ($now->gt($ends)) {
                                                $statusClass = 'bg-slate-500/10 text-slate-400 ring-slate-500/20';
                                                $statusText = 'Ended';
                                            } else {
                                                $statusClass = 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/20';
                                                $statusText = 'Running';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400 font-mono text-xs">
                                        {{ $contest->starts_at ? $contest->starts_at->format('Y-m-d H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400 font-mono text-xs">
                                        {{ $contest->ends_at ? $contest->ends_at->format('Y-m-d H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-300 font-semibold font-mono">
                                        {{ $contest->problems_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-300 font-semibold font-mono">
                                        {{ $contest->participants_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                        <div class="flex items-center justify-end gap-2.5">
                                            {{-- Admin Approve Button --}}
                                            @if(!$contest->is_approved)
                                                @role('Admin')
                                                <form method="POST" action="{{ route('contests.approve', $contest) }}" class="inline-block">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center gap-1 rounded-lg bg-emerald-500/10 border border-emerald-500/25 px-2.5 py-1.5 font-semibold text-emerald-400 hover:bg-emerald-500/20 transition-colors duration-150">
                                                        Approve
                                                    </button>
                                                </form>
                                                @endrole
                                            @endif

                                            {{-- Register or Enter Button for Contestants --}}
                                            @if($contest->is_approved)
                                                @if(auth()->user()->hasRole('Contestant'))
                                                    @if($contest->participants->contains(auth()->id()))
                                                        <a href="{{ route('contests.show', $contest) }}" 
                                                           class="inline-flex items-center gap-1 rounded-lg bg-emerald-500/10 border border-emerald-500/25 px-2.5 py-1.5 font-semibold text-emerald-400 hover:bg-emerald-500/20 transition-colors duration-150">
                                                            Enter
                                                        </a>
                                                    @else
                                                        <form method="POST" action="{{ route('contests.register', $contest) }}" class="inline-block">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="inline-flex items-center gap-1 rounded-lg bg-indigo-600/15 border border-indigo-500/25 px-2.5 py-1.5 font-semibold text-indigo-400 hover:bg-indigo-600/30 transition-colors duration-150">
                                                                Register
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    {{-- Non-contestants (Admins, Judges) can enter directly --}}
                                                    <a href="{{ route('contests.show', $contest) }}" 
                                                       class="inline-flex items-center gap-1 rounded-lg bg-slate-800 border border-slate-700 px-2.5 py-1.5 font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-colors duration-150">
                                                        Enter
                                                    </a>
                                                @endif
                                            @else
                                                @if(auth()->id() === $contest->created_by || auth()->user()->hasRole('Admin'))
                                                    <a href="{{ route('contests.show', $contest) }}" 
                                                       class="inline-flex items-center gap-1 rounded-lg bg-slate-800 border border-slate-700 px-2.5 py-1.5 font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-colors duration-150">
                                                        View Details
                                                    </a>
                                                @endif
                                            @endif

                                            {{-- Creator or Admin Edit/Delete Actions --}}
                                            @if(auth()->id() === $contest->created_by || auth()->user()->hasRole('Admin'))
                                                <a href="{{ route('contests.edit', $contest) }}" 
                                                   class="inline-flex items-center gap-1 rounded-lg bg-slate-800 border border-slate-700 px-2.5 py-1.5 font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-colors duration-150">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('contests.destroy', $contest) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to delete this contest?')"
                                                            class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 border border-rose-500/20 px-2.5 py-1.5 font-semibold text-rose-400 hover:bg-rose-500/20 transition-colors duration-150">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($contests->hasPages())
                    <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                        {{ $contests->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Widget: External Contests (Listed below without boxed cards) --}}
    @if(!empty($externalContests))
        <div class="pt-6 border-t border-slate-800/80">
            <div class="flex items-center gap-2 mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                <h2 class="text-xl font-bold text-white">Recent & Upcoming Contests Elsewhere</h2>
            </div>
            
            <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900/10">
                <table class="w-full text-sm text-left text-slate-300">
                    <thead class="bg-slate-900/50 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <tr class="border-b border-slate-800">
                            <th scope="col" class="px-6 py-4">Contest Name</th>
                            <th scope="col" class="px-6 py-4">Platform</th>
                            <th scope="col" class="px-6 py-4">Start Time</th>
                            <th scope="col" class="px-6 py-4">Duration</th>
                            <th scope="col" class="px-6 py-4 text-right">Link</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 bg-slate-950/20">
                        @foreach($externalContests as $ext)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-200">
                                    {{ $ext['name'] }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeColors = [
                                            'Codeforces' => 'text-indigo-400 bg-indigo-500/10 ring-indigo-500/20',
                                            'CodeChef'   => 'text-amber-400 bg-amber-500/10 ring-amber-500/20',
                                            'AtCoder'    => 'text-rose-400 bg-rose-500/10 ring-rose-500/20',
                                            'LeetCode'   => 'text-emerald-400 bg-emerald-500/10 ring-emerald-500/20',
                                        ];
                                        $badge = $badgeColors[$ext['site']] ?? 'text-slate-400 bg-slate-500/10 ring-slate-500/20';
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $badge }}">
                                        {{ $ext['site'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                    {{ $ext['start_time'] }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                    {{ $ext['duration'] }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ $ext['url'] }}" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">
                                        View
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
    @endif
</div>
@endsection
