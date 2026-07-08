@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Submit Solution: ' . $problem->title . ' - JudgeMate')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('problems.show', $problem) }}" 
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-700 bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition-all duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back to Problem
        </a>
    </div>

    {{-- Problem Header Card --}}
    <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Submit Solution</h1>
                <p class="text-xs text-slate-400 mt-1">Problem: <span class="text-indigo-400 font-semibold">{{ $problem->title }}</span></p>
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
    </div>

    {{-- Code Submission Form --}}
    <div class="rounded-2xl border border-slate-800 bg-slate-900/10 p-6 space-y-6">
        <form action="{{ route('problems.submissions.store', $problem) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Language Select --}}
            <div class="space-y-2">
                <label for="language" class="block text-sm font-semibold uppercase tracking-wider text-slate-400">Language</label>
                <div class="relative max-w-xs">
                    <select id="language" name="language" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-200 border border-slate-800 bg-slate-900 focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 outline-none transition duration-150 appearance-none">
                        <option value="cpp" {{ old('language') == 'cpp' ? 'selected' : '' }}>C++ (g++ 17)</option>
                        <option value="python" {{ old('language') == 'python' ? 'selected' : '' }}>Python (3.10)</option>
                        <option value="java" {{ old('language') == 'java' ? 'selected' : '' }}>Java (JDK 17)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
                @error('language')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Source Code Textarea --}}
            <div class="space-y-2">
                <label for="code" class="block text-sm font-semibold uppercase tracking-wider text-slate-400">Source Code</label>
                <textarea id="code" name="code" rows="18" required
                          class="w-full px-4 py-3 rounded-xl text-sm text-slate-200 border border-slate-800 bg-slate-950 font-mono focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 outline-none transition duration-150"
                          placeholder="Write or paste your code here...">{{ old('code') }}</textarea>
                @error('code')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Row --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('problems.show', $problem) }}" 
                   class="rounded-xl border border-slate-800 bg-slate-900/50 px-5 py-2.5 text-sm font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition-colors duration-150">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-1.5 rounded-xl py-2.5 px-6 text-sm font-bold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98]"
                        style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                    Submit Solution
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
