@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', $problem->title . ' - JudgeMate')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    {{-- Back and Edit Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('problems.index') }}" 
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Problems
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('problems.submit', $problem) }}" 
               class="inline-flex items-center gap-1.5 rounded-lg py-2 px-4 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-emerald-500/30 active:scale-[.98]"
               style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Submit Solution
            </a>
            @hasanyrole('Admin|ProblemSetter')
            <a href="{{ route('problems.edit', $problem) }}" 
               class="inline-flex items-center gap-1.5 rounded-lg py-2 px-4 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98]"
               style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                Edit Problem
            </a>
            @endhasanyrole
        </div>
    </div>

    {{-- Main Container --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Problem Details & Statements --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 space-y-4">
                {{-- Title & Difficulty --}}
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-white tracking-tight">{{ $problem->title }}</h1>
                        <p class="text-xs font-mono text-slate-500 mt-1">Slug: {{ $problem->slug }}</p>
                        @if($problem->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                @foreach($problem->tags as $tag)
                                    <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-2 py-0.5 text-xs font-medium text-indigo-400 ring-1 ring-inset ring-indigo-500/30">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @php
                        $difficultyColors = [
                            'easy'   => 'text-emerald-400 bg-emerald-500/10 ring-emerald-500/20',
                            'medium' => 'text-amber-400 bg-amber-500/10 ring-amber-500/20',
                            'hard'   => 'text-rose-400 bg-rose-500/10 ring-rose-500/20',
                        ];
                        $color = $difficultyColors[$problem->difficulty] ?? 'text-slate-400 bg-slate-500/10 ring-slate-500/20';
                    @endphp
                    <span class="inline-flex items-center rounded-md px-3 py-1 text-xs font-bold uppercase tracking-wider ring-1 {{ $color }}">
                        {{ $problem->difficulty }}
                    </span>
                </div>

                {{-- Statement --}}
                <div class="border-t border-slate-800/80 pt-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Statement</h3>
                    <div class="text-slate-200 text-sm whitespace-pre-line leading-relaxed font-sans">
                        {{ $problem->statement }}
                    </div>
                </div>

                {{-- Input Format --}}
                @if($problem->input_format)
                <div class="border-t border-slate-800/80 pt-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Input Format</h3>
                    <p class="text-slate-300 text-sm whitespace-pre-line leading-relaxed font-sans">
                        {{ $problem->input_format }}
                    </p>
                </div>
                @endif

                {{-- Output Format --}}
                @if($problem->output_format)
                <div class="border-t border-slate-800/80 pt-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Output Format</h3>
                    <p class="text-slate-300 text-sm whitespace-pre-line leading-relaxed font-sans">
                        {{ $problem->output_format }}
                    </p>
                </div>
                @endif

                {{-- Constraints --}}
                @if($problem->constraints)
                <div class="border-t border-slate-800/80 pt-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Constraints</h3>
                    <div class="rounded-xl bg-slate-950/70 border border-slate-900 px-4 py-2.5 font-mono text-xs text-indigo-300">
                        {{ $problem->constraints }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Test Cases / Info --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 space-y-4">
                <h2 class="text-lg font-bold text-white border-b border-slate-800 pb-2">Problem Info</h2>
                
                {{-- Creator --}}
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400 font-medium">Created By</span>
                    <span class="text-slate-200 font-semibold">{{ $problem->creator->name ?? 'System' }}</span>
                </div>

                {{-- Created At --}}
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400 font-medium">Date Created</span>
                    <span class="text-slate-200 font-semibold">{{ $problem->created_at->format('M d, Y') }}</span>
                </div>

                {{-- Status --}}
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400 font-medium">Status</span>
                    @if($problem->is_published)
                        <span class="text-emerald-400 font-semibold">Published</span>
                    @else
                        <span class="text-slate-400 font-semibold">Draft</span>
                    @endif
                </div>
            </div>

            {{-- Test Cases Section --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 space-y-4">
                <h2 class="text-lg font-bold text-white border-b border-slate-800 pb-2">Sample Test Cases</h2>

                @php
                    $visibleTestCases = $problem->testCases->where('is_hidden', false)->values();
                @endphp

                @if($visibleTestCases->isEmpty())
                    <p class="text-xs text-slate-500 italic">No sample test cases are currently visible.</p>
                @else
                    <div class="space-y-4">
                        @foreach($visibleTestCases as $index => $tc)
                            <div class="space-y-2">
                                <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Sample #{{ $index + 1 }}</span>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="space-y-1">
                                        <span class="text-slate-500 uppercase tracking-wider font-semibold text-[10px]">Input</span>
                                        <pre class="bg-slate-950/70 border border-slate-900 p-2 rounded-lg text-slate-200 overflow-x-auto select-all max-h-24 font-mono">{{ $tc->input }}</pre>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-slate-500 uppercase tracking-wider font-semibold text-[10px]">Expected Output</span>
                                        <pre class="bg-slate-950/70 border border-slate-900 p-2 rounded-lg text-slate-200 overflow-x-auto select-all max-h-24 font-mono">{{ $tc->expected_output }}</pre>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @hasrole('Admin')
                @php
                    $hiddenTestCases = $problem->testCases->where('is_hidden', true)->values();
                @endphp
                @if(!$hiddenTestCases->isEmpty())
                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-amber-500/10 pb-2">
                            <h2 class="text-lg font-bold text-amber-400">Hidden Test Cases (Admin Only)</h2>
                        </div>
                        <div class="space-y-4">
                            @foreach($hiddenTestCases as $index => $tc)
                                <div class="space-y-2">
                                    <span class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Hidden Case #{{ $index + 1 }}</span>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="space-y-1">
                                            <span class="text-slate-500 uppercase tracking-wider font-semibold text-[10px]">Input</span>
                                            <pre class="bg-slate-950/70 border border-slate-900 p-2 rounded-lg text-slate-200 overflow-x-auto select-all max-h-24 font-mono">{{ $tc->input }}</pre>
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-slate-500 uppercase tracking-wider font-semibold text-[10px]">Expected Output</span>
                                            <pre class="bg-slate-950/70 border border-slate-900 p-2 rounded-lg text-slate-200 overflow-x-auto select-all max-h-24 font-mono">{{ $tc->expected_output }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endhasrole
        </div>

    </div>
</div>
@endsection
