@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Create Contest - JudgeMate')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Create Contest</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Design a new local contest and assign programming problems.</p>
        </div>
        <a href="{{ route('contests.index') }}" 
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-white transition-all duration-150">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Contests
        </a>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('contests.store') }}" class="space-y-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 p-6 shadow-sm dark:shadow-xl">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Title</label>
            <input type="text" name="title" id="title" required value="{{ old('title') }}"
                   class="mt-1.5 block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-2.5 text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            @error('title')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1.5 block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-2.5 text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="starts_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Starts At</label>
                <input type="datetime-local" name="starts_at" id="starts_at" required value="{{ old('starts_at') }}"
                       class="mt-1.5 block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-2.5 text-slate-900 dark:text-slate-200 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('starts_at')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ends_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Ends At</label>
                <input type="datetime-local" name="ends_at" id="ends_at" required value="{{ old('ends_at') }}"
                       class="mt-1.5 block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-4 py-2.5 text-slate-900 dark:text-slate-200 transition-colors focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('ends_at')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Problems Multi-select --}}
        <div>
            <label for="problems" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Assign Problems</label>
            <p class="text-xs text-slate-500 mt-0.5 mb-2">Hold down Ctrl (Windows) or Command (Mac) to select multiple problems. The order of selection will determine A, B, C... labels sequentially.</p>
            <select name="problems[]" id="problems" multiple class="block w-full rounded-lg border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2.5 text-slate-900 dark:text-slate-200 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 min-h-[160px]">
                @foreach($problems as $problem)
                    <option value="{{ $problem->id }}" {{ (is_array(old('problems')) && in_array($problem->id, old('problems'))) ? 'selected' : '' }}>
                        {{ $problem->title }} ({{ ucfirst($problem->difficulty) }})
                    </option>
                @endforeach
            </select>
            @error('problems')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status / Is Active Checkbox --}}
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-950 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-offset-slate-950">
            <label for="is_active" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Activate Contest immediately</label>
        </div>

        {{-- Form Buttons --}}
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-800/60 pt-5">
            <a href="{{ route('contests.index') }}" 
               class="rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-4.5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">
                Cancel
            </a>
            <button type="submit" 
                    class="rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30"
                    style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                Create Contest
            </button>
        </div>
    </form>
</div>
@endsection
