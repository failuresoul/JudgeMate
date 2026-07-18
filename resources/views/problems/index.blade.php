@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Problems - JudgeMate')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5 gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Problems</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Practice your coding skills by solving problems.</p>
        </div>
        @hasrole('ProblemSetter')
        <a href="{{ route('problems.create') }}" 
           class="shrink-0 whitespace-nowrap self-start sm:self-auto inline-flex items-center gap-1.5 rounded-lg py-2.5 px-4 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-indigo-500/60"
           style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
            Create Problem
        </a>
        @endrole
    </div>



    {{-- Problems Table --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 overflow-hidden shadow-sm dark:shadow-xl">
        @if($problems->isEmpty())
            <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                    <svg class="h-6 w-6 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500">No problems found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[700px]">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-left bg-slate-50 dark:bg-transparent">
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Title</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Difficulty</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Creator</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            @hasanyrole('ProblemSetter|Admin')
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                            @endhasanyrole
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($problems as $problem)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors duration-100">
                            {{-- Title --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('problems.show', $problem) }}" class="font-semibold text-slate-900 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors text-base">
                                    {{ $problem->title }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $problem->slug }}</p>
                            </td>

                            {{-- Difficulty --}}
                            <td class="px-6 py-4">
                                @php
                                    $difficultyColors = [
                                        'easy'   => 'text-emerald-600 bg-emerald-50 ring-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:ring-emerald-500/20',
                                        'medium' => 'text-amber-600 bg-amber-50 ring-amber-200 dark:text-amber-400 dark:bg-amber-500/10 dark:ring-amber-500/20',
                                        'hard'   => 'text-rose-600 bg-rose-50 ring-rose-200 dark:text-rose-400 dark:bg-rose-500/10 dark:ring-rose-500/20',
                                    ];
                                    $color = $difficultyColors[$problem->difficulty] ?? 'text-slate-600 bg-slate-50 ring-slate-200 dark:text-slate-400 dark:bg-slate-500/10 dark:ring-slate-500/20';
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider ring-1 {{ $color }}">
                                    {{ $problem->difficulty }}
                                </span>
                            </td>

                            {{-- Creator --}}
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-md bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-[10px]">
                                        {{ strtoupper(substr($problem->creator->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium">{{ $problem->creator->name ?? 'System' }}</span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($problem->is_published)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-500/10 px-2.5 py-0.5 text-xs font-semibold text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-500/20">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            @hasanyrole('ProblemSetter|Admin')
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @role('Admin')
                                        @if(!$problem->is_published)
                                            <form method="POST" action="{{ route('problems.toggle-publish', $problem) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20 hover:bg-emerald-500/20 transition-colors duration-150"
                                                    onclick="return confirm('Approve and publish this problem?')">
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('problems.toggle-publish', $problem) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 px-2.5 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 ring-1 ring-red-200 dark:ring-red-500/20 hover:bg-red-500/20 transition-colors duration-150"
                                                    onclick="return confirm('Unpublish this problem?')">
                                                    Unpublish
                                                </button>
                                            </form>
                                        @endif
                                    @endrole

                                    @role('ProblemSetter')
                                    <a href="{{ route('problems.edit', $problem) }}" 
                                       class="inline-flex items-center gap-1 rounded-lg bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-colors duration-150">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('problems.destroy', $problem) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this problem?')"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-50 border border-rose-200 dark:bg-rose-500/10 dark:border-rose-500/20 px-2.5 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors duration-150">
                                            Delete
                                        </button>
                                    </form>
                                    @endrole
                                </div>
                            </td>
                            @endhasanyrole
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($problems->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/10">
                    {{ $problems->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
