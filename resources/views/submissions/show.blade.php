@extends(auth()->check() && auth()->user()->hasRole('Admin') ? 'layouts.admin' : (auth()->check() && auth()->user()->hasRole('ProblemSetter') ? 'layouts.judge' : 'layouts.app'))

@section('title', 'Submission Details - JudgeMate')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Submission Details</h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Review the code and evaluation verdict for this submission.</p>
        </div>
        <div>
            <a href="{{ route('submissions.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                &larr; Back to Submissions
            </a>
        </div>
    </div>

    {{-- Details Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 p-6 bg-slate-50 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-800">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Contestant</p>
                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $submission->user->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Problem</p>
                <a href="{{ route('problems.show', $submission->problem) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    {{ $submission->problem->title }}
                </a>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Language</p>
                <span class="font-mono text-xs uppercase bg-slate-200 dark:bg-slate-800 px-2 py-0.5 rounded-md text-slate-700 dark:text-slate-300">
                    {{ $submission->language }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Submitted At</p>
                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $submission->submitted_at->format('M d, Y H:i:s') }}</p>
            </div>
        </div>

        <div class="p-6 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">Evaluation Verdict</h3>
            <div class="flex items-start gap-4">
                @php
                    $badgeClasses = [
                        'pending'               => 'bg-amber-100 text-amber-700 ring-amber-300 dark:bg-amber-500/20 dark:text-amber-400 dark:ring-amber-500/30',
                        'accepted'              => 'bg-emerald-100 text-emerald-700 ring-emerald-300 dark:bg-emerald-500/20 dark:text-emerald-400 dark:ring-emerald-500/30',
                        'wrong_answer'          => 'bg-rose-100 text-rose-700 ring-rose-300 dark:bg-rose-500/20 dark:text-rose-400 dark:ring-rose-500/30',
                        'compilation_error'     => 'bg-rose-100 text-rose-700 ring-rose-300 dark:bg-rose-500/20 dark:text-rose-400 dark:ring-rose-500/30',
                        'time_limit_exceeded'   => 'bg-rose-100 text-rose-700 ring-rose-300 dark:bg-rose-500/20 dark:text-rose-400 dark:ring-rose-500/30',
                    ];
                    $class = $badgeClasses[$submission->status] ?? 'bg-slate-100 text-slate-700 ring-slate-300 dark:bg-slate-500/20 dark:text-slate-400 dark:ring-slate-500/30';
                    $statusLabel = ucfirst(str_replace('_', ' ', $submission->status));
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold ring-1 ring-inset {{ $class }}">
                    {{ $statusLabel }}
                </span>
                <p class="text-sm font-mono text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/50 rounded-lg p-3 w-full ring-1 ring-slate-200 dark:ring-slate-800">
                    {{ $submission->verdict_message ?? 'Evaluating...' }}
                </p>
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">Source Code</h3>
            <div class="rounded-xl overflow-hidden shadow-inner bg-[#1e1e1e]">
                <pre><code class="language-{{ $submission->language == 'python' ? 'python' : ($submission->language == 'java' ? 'java' : 'cpp') }} !bg-transparent !p-4 !m-0 text-sm">{!! htmlspecialchars($submission->code) !!}</code></pre>
            </div>
        </div>
    </div>
</div>

{{-- Include highlight.js for syntax highlighting --}}
@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/cpp.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/java.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/python.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el);
        });
    });
</script>
@endpush
@endsection
