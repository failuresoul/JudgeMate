@extends('layouts.judge')

@section('title', 'Write Post - JudgeMate')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
        <a href="{{ route('judge.blogs.index') }}" class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Write a New Post</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Share tips, tricks, or inspiration with contestants.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('judge.blogs.store') }}" enctype="multipart/form-data" class="space-y-6 bg-white dark:bg-slate-900/50 p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Post Title <span class="text-rose-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                   class="mt-2 block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors py-2.5 px-4"
                   placeholder="e.g. How to optimize your Python solutions">
            @error('title')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image/GIF Upload --}}
        <div>
            <label for="image" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Upload Image or GIF (Optional)</label>
            <div class="mt-2 flex justify-center rounded-xl border border-dashed border-slate-300 dark:border-slate-700 px-6 py-10 transition-colors hover:border-indigo-500">
                <div class="text-center" x-data="{ fileName: '', fileSizeError: false }">
                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                    </svg>
                    <div class="mt-4 flex flex-col items-center text-sm leading-6 text-slate-600 dark:text-slate-400 justify-center">
                        <div class="flex">
                            <label for="image" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                <span>Upload a file</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/png, image/jpeg, image/gif"
                                       @change="
                                           if($event.target.files.length > 0) {
                                               if($event.target.files[0].size > 2097152) {
                                                   fileSizeError = true;
                                                   fileName = '';
                                                   $event.target.value = '';
                                               } else {
                                                   fileSizeError = false;
                                                   fileName = $event.target.files[0].name;
                                               }
                                           } else {
                                               fileName = '';
                                           }
                                       ">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p x-show="fileName" x-text="fileName" class="mt-2 text-sm font-bold text-emerald-600 dark:text-emerald-400" style="display: none;"></p>
                        <p x-show="fileSizeError" class="mt-2 text-sm font-bold text-rose-500" style="display: none;">File must be smaller than 2MB.</p>
                    </div>
                    <p class="text-xs leading-5 text-slate-500 mt-2">PNG, JPG, GIF up to 2MB</p>
                </div>
            </div>
            @error('image')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Content --}}
        <div>
            <label for="content" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Content <span class="text-rose-500">*</span></label>
            <div class="mt-2">
                <textarea id="content" name="content" rows="8" required
                          class="block w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-4"
                          placeholder="Write your inspiration post here...">{{ old('content') }}</textarea>
            </div>
            @error('content')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 flex items-center justify-end gap-4">
            <a href="{{ route('judge.blogs.index') }}" class="text-sm font-semibold leading-6 text-slate-900 dark:text-slate-300 hover:text-slate-500">Cancel</a>
            <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                Submit for Review
            </button>
        </div>
    </form>
</div>
@endsection
