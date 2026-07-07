@extends('layouts.judge')

@section('title', 'Manage Test Cases - JudgeMate')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-800 pb-5">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Test Cases</h1>
            <p class="mt-1 text-sm text-slate-400">Select a problem to add, modify, or delete its evaluation test cases.</p>
        </div>
    </div>

    {{-- Problems List --}}
    <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden shadow-xl">
        @if($problems->isEmpty())
            <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-800">
                    <svg class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500">You haven't created any problems yet.</p>
                <a href="{{ route('problems.create') }}" class="mt-2 text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">Create your first problem</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-800 text-left">
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Problem</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Difficulty</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Visible / Sample</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Hidden / Evaluator</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($problems as $problem)
                        <tr class="group hover:bg-slate-800/30 transition-colors duration-100">
                            {{-- Title & Slug --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('judge.test-cases.show', $problem) }}" class="font-semibold text-slate-100 hover:text-indigo-400 transition-colors text-base">
                                    {{ $problem->title }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $problem->slug }}</p>
                            </td>

                            {{-- Difficulty --}}
                            <td class="px-6 py-4">
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

                            {{-- Visible Test Cases count --}}
                            <td class="px-6 py-4 text-slate-300">
                                <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-400">
                                    {{ $problem->testCases->where('is_hidden', false)->count() }} visible
                                </span>
                            </td>

                            {{-- Hidden Test Cases count --}}
                            <td class="px-6 py-4 text-slate-300">
                                <span class="inline-flex items-center rounded-full bg-amber-500/10 px-2.5 py-0.5 text-xs font-medium text-amber-400">
                                    {{ $problem->testCases->where('is_hidden', true)->count() }} hidden
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('judge.test-cases.show', $problem) }}" 
                                   class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 border border-indigo-500/20 px-3 py-1.5 text-xs font-bold text-indigo-400 hover:bg-indigo-500/20 transition-colors duration-150">
                                    Manage Test Cases
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($problems->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                    {{ $problems->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
