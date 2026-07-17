@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Edit Problem - JudgeMate')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Edit Problem</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update problem configuration, statement, and metadata.</p>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('problems.update', $problem) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Problem Title <span class="text-rose-500">*</span></label>
                <input id="title" type="text" name="title" value="{{ old('title', $problem->title) }}" required
                       class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm dark:shadow-none"
                       placeholder="e.g. Two Sum">
                @error('title')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Slug --}}
            <div>
                <label for="slug" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Slug (optional)</label>
                <input id="slug" type="text" name="slug" value="{{ old('slug', $problem->slug) }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm dark:shadow-none"
                       placeholder="e.g. two-sum (auto-generated if blank)">
                @error('slug')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Difficulty --}}
            <div>
                <label for="difficulty" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Difficulty <span class="text-rose-500">*</span></label>
                <select id="difficulty" name="difficulty" required
                        class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm dark:shadow-none">
                    <option value="" disabled>Select difficulty</option>
                    <option value="easy" {{ old('difficulty', $problem->difficulty) === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty', $problem->difficulty) === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ old('difficulty', $problem->difficulty) === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
                @error('difficulty')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Status --}}
            @hasrole('Admin')
            <div class="flex items-center gap-3 md:mt-8">
                <input id="is_published" type="checkbox" name="is_published" value="1" {{ old('is_published', $problem->is_published) ? 'checked' : '' }}
                       class="w-4 h-4 rounded accent-indigo-500 bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-700 cursor-pointer">
                <label for="is_published" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">Publish immediately (visible to all contestants)</label>
                @error('is_published')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            @endrole
        </div>

        {{-- Tags --}}
        <div>
            <label for="tags" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Problem Tags (Hold Ctrl/Cmd to select multiple)</label>
            <select id="tags" name="tags[]" multiple
                    class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 min-h-[120px] shadow-sm dark:shadow-none">
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ is_array(old('tags')) ? (in_array($tag->id, old('tags')) ? 'selected' : '') : ($problem->tags->contains($tag->id) ? 'selected' : '') }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
            @error('tags')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror
            @error('tags.*')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Statement --}}
        <div>
            <label for="statement" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Problem Statement <span class="text-rose-500">*</span></label>
            <textarea id="statement" name="statement" rows="6" required
                      class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 font-mono shadow-sm dark:shadow-none"
                      placeholder="Describe the problem, input format, and output format using markdown...">{{ old('statement', $problem->statement) }}</textarea>
            @error('statement')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Input Format --}}
            <div>
                <label for="input_format" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input Format</label>
                <textarea id="input_format" name="input_format" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 font-mono shadow-sm dark:shadow-none"
                          placeholder="Describe constraints/details on the input...">{{ old('input_format', $problem->input_format) }}</textarea>
                @error('input_format')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Output Format --}}
            <div>
                <label for="output_format" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Output Format</label>
                <textarea id="output_format" name="output_format" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 font-mono shadow-sm dark:shadow-none"
                          placeholder="Describe output expected output format...">{{ old('output_format', $problem->output_format) }}</textarea>
                @error('output_format')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Constraints --}}
            <div>
                <label for="constraints" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Constraints</label>
                <textarea id="constraints" name="constraints" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900/50 font-mono shadow-sm dark:shadow-none"
                          placeholder="e.g. 1 <= N <= 10^5...">{{ old('constraints', $problem->constraints) }}</textarea>
                @error('constraints')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end gap-3 pt-8 mt-16">
            <button type="submit"
                    class="flex items-center justify-center gap-2 py-2.5 px-6 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-indigo-500/60 bg-gradient-to-br from-indigo-600 to-violet-600">
                Update Problem
            </button>
        </div>
    </form>
    
    {{-- Test Cases Section --}}
    @include('problems.partials.test-cases')
</div>
@endsection
