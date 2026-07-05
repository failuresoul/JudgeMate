<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JudgeMate') }} — {{ $title ?? 'Authentication' }}</title>
        <meta name="description" content="JudgeMate — The competitive programming judge platform for contestants and problem setters.">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Plus Jakarta Sans', sans-serif; }
            code, pre, .mono { font-family: 'JetBrains Mono', monospace; }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33%       { transform: translateY(-18px) rotate(1deg); }
                66%       { transform: translateY(-8px) rotate(-1deg); }
            }
            @keyframes blob-pulse {
                0%, 100% { transform: scale(1) translate(0, 0); }
                25%       { transform: scale(1.08) translate(20px, -15px); }
                50%       { transform: scale(0.95) translate(-10px, 20px); }
                75%       { transform: scale(1.04) translate(-20px, -10px); }
            }
            @keyframes type-cursor {
                0%, 100% { opacity: 1; }
                50%       { opacity: 0; }
            }
            @keyframes slide-up {
                from { opacity: 0; transform: translateY(24px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes fade-in {
                from { opacity: 0; }
                to   { opacity: 1; }
            }
            @keyframes code-scroll {
                0%   { transform: translateY(0); }
                100% { transform: translateY(-50%); }
            }

            .blob-1 { animation: blob-pulse 9s ease-in-out infinite; }
            .blob-2 { animation: blob-pulse 12s ease-in-out infinite reverse; }
            .blob-3 { animation: blob-pulse 7s ease-in-out infinite 3s; }
            .float-anim { animation: float 6s ease-in-out infinite; }
            .slide-up   { animation: slide-up 0.6s cubic-bezier(.22,1,.36,1) both; }
            .code-scroll-anim { animation: code-scroll 30s linear infinite; }
            .cursor-blink { animation: type-cursor 1s step-end infinite; }

            .glass-card {
                background: rgba(15, 23, 42, 0.7);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(148, 163, 184, 0.1);
            }
            .gradient-text {
                background: linear-gradient(135deg, #fff 30%, #94a3b8 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .brand-text {
                background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="h-full antialiased" style="background: #020817;">

        <div class="min-h-screen flex">

            {{-- ═══════════════════════════════════════════════
                 LEFT PANEL — Branding & Code Art (hidden on mobile)
            ═══════════════════════════════════════════════ --}}
            <div class="hidden lg:flex lg:w-[52%] xl:w-[55%] relative overflow-hidden flex-col justify-between p-12"
                 style="background: linear-gradient(135deg, #0d1526 0%, #0a0f1e 60%, #0d0e1f 100%);">

                {{-- Animated background blobs --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="blob-1 absolute -top-32 -left-32 w-96 h-96 rounded-full opacity-20"
                         style="background: radial-gradient(circle, #4f46e5 0%, transparent 70%);"></div>
                    <div class="blob-2 absolute top-1/2 -right-24 w-80 h-80 rounded-full opacity-15"
                         style="background: radial-gradient(circle, #7c3aed 0%, transparent 70%);"></div>
                    <div class="blob-3 absolute -bottom-16 left-1/3 w-72 h-72 rounded-full opacity-10"
                         style="background: radial-gradient(circle, #2563eb 0%, transparent 70%);"></div>
                    {{-- Grid pattern --}}
                    <div class="absolute inset-0 opacity-[0.03]"
                         style="background-image: linear-gradient(#6366f1 1px, transparent 1px), linear-gradient(90deg, #6366f1 1px, transparent 1px); background-size: 48px 48px;"></div>
                </div>

                {{-- Top: Brand --}}
                <div class="relative z-10">
                    <a href="/" class="inline-flex items-center gap-3 group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-indigo-500/40"
                             style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            <x-application-logo class="w-7 h-7 text-white" />
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">Judge<span class="brand-text">Mate</span></span>
                    </a>
                </div>

                {{-- Middle: Animated Code Terminal --}}
                <div class="relative z-10 float-anim">
                    <div class="rounded-2xl overflow-hidden shadow-2xl shadow-indigo-950/60"
                         style="border: 1px solid rgba(99,102,241,0.2); background: rgba(8,12,28,0.9);">
                        {{-- Terminal bar --}}
                        <div class="flex items-center gap-2 px-4 py-3" style="background: rgba(99,102,241,0.1); border-bottom: 1px solid rgba(99,102,241,0.15);">
                            <span class="w-3 h-3 rounded-full bg-red-500 opacity-80"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400 opacity-80"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500 opacity-80"></span>
                            <span class="mono text-xs text-slate-500 ml-2">judgemate ~ verdict engine</span>
                        </div>
                        {{-- Code content --}}
                        <div class="p-5 overflow-hidden h-72 relative">
                            <div class="code-scroll-anim absolute top-5 left-5 right-5">
                                <pre class="mono text-xs leading-6 select-none"><span class="text-violet-400">function</span> <span class="text-sky-300">judge</span><span class="text-slate-300">(submission) {</span>
<span class="text-slate-500">  // Run test cases</span>
  <span class="text-violet-400">const</span> <span class="text-slate-300">results = testCases.</span><span class="text-sky-300">map</span><span class="text-slate-300">(tc =&gt; {</span>
    <span class="text-violet-400">const</span> <span class="text-slate-300">output = </span><span class="text-sky-300">execute</span><span class="text-slate-300">(submission, tc.input);</span>
    <span class="text-violet-400">return</span> <span class="text-slate-300">output === tc.expected</span>
      <span class="text-slate-500">? </span><span class="text-emerald-400">"AC"</span> <span class="text-slate-500">: </span><span class="text-red-400">"WA"</span><span class="text-slate-300">;</span>
  <span class="text-slate-300">});</span>

  <span class="text-violet-400">if</span> <span class="text-slate-300">(results.every(r =&gt; r === </span><span class="text-emerald-400">"AC"</span><span class="text-slate-300">)) {</span>
    <span class="text-violet-400">return</span> <span class="text-slate-300">{</span>
      <span class="text-sky-300">verdict</span><span class="text-slate-300">: </span><span class="text-emerald-400">"Accepted ✓"</span><span class="text-slate-300">,</span>
      <span class="text-sky-300">score</span><span class="text-slate-300">: </span><span class="text-amber-400">100</span><span class="text-slate-300">,</span>
      <span class="text-sky-300">time</span><span class="text-slate-300">: </span><span class="text-amber-400">42</span><span class="text-slate-300"> + </span><span class="text-amber-400">"ms"</span>
    <span class="text-slate-300">};</span>
  <span class="text-slate-300">}</span>
  <span class="text-violet-400">return</span> <span class="text-slate-300">{</span>
    <span class="text-sky-300">verdict</span><span class="text-slate-300">: </span><span class="text-red-400">"Wrong Answer"</span><span class="text-slate-300">,</span>
    <span class="text-sky-300">failed</span><span class="text-slate-300">: results.</span><span class="text-sky-300">indexOf</span><span class="text-slate-300">(</span><span class="text-red-400">"WA"</span><span class="text-slate-300">) + </span><span class="text-amber-400">1</span>
  <span class="text-slate-300">};</span>
<span class="text-slate-300">}</span>

<span class="text-slate-600">// --- Pending submissions queue ---</span>
<span class="text-violet-400">const</span> <span class="text-slate-300">queue = [</span>
  <span class="text-emerald-400">"sub_8f3a"</span><span class="text-slate-300">,</span> <span class="text-emerald-400">"sub_9b2c"</span><span class="text-slate-300">,</span> <span class="text-emerald-400">"sub_7e1d"</span>
<span class="text-slate-300">];</span>
<span class="text-slate-300">queue.</span><span class="text-sky-300">forEach</span><span class="text-slate-300">(id =&gt; </span><span class="text-sky-300">judge</span><span class="text-slate-300">(</span><span class="text-sky-300">fetchSub</span><span class="text-slate-300">(id)));</span>
</pre>
                                {{-- Duplicate for seamless loop --}}
                                <pre class="mono text-xs leading-6 select-none mt-4"><span class="text-violet-400">function</span> <span class="text-sky-300">judge</span><span class="text-slate-300">(submission) {</span>
<span class="text-slate-500">  // Run test cases</span>
  <span class="text-violet-400">const</span> <span class="text-slate-300">results = testCases.</span><span class="text-sky-300">map</span><span class="text-slate-300">(tc =&gt; {</span>
    <span class="text-violet-400">const</span> <span class="text-slate-300">output = </span><span class="text-sky-300">execute</span><span class="text-slate-300">(submission, tc.input);</span>
    <span class="text-violet-400">return</span> <span class="text-slate-300">output === tc.expected</span>
      <span class="text-slate-500">? </span><span class="text-emerald-400">"AC"</span> <span class="text-slate-500">: </span><span class="text-red-400">"WA"</span><span class="text-slate-300">;</span>
  <span class="text-slate-300">});</span>

  <span class="text-violet-400">if</span> <span class="text-slate-300">(results.every(r =&gt; r === </span><span class="text-emerald-400">"AC"</span><span class="text-slate-300">)) {</span>
    <span class="text-violet-400">return</span> <span class="text-slate-300">{</span>
      <span class="text-sky-300">verdict</span><span class="text-slate-300">: </span><span class="text-emerald-400">"Accepted ✓"</span><span class="text-slate-300">,</span>
      <span class="text-sky-300">score</span><span class="text-slate-300">: </span><span class="text-amber-400">100</span><span class="text-slate-300">,</span>
      <span class="text-sky-300">time</span><span class="text-slate-300">: </span><span class="text-amber-400">42</span><span class="text-slate-300"> + </span><span class="text-amber-400">"ms"</span>
    <span class="text-slate-300">};</span>
  <span class="text-slate-300">}</span>
  <span class="text-violet-400">return</span> <span class="text-slate-300">{</span>
    <span class="text-sky-300">verdict</span><span class="text-slate-300">: </span><span class="text-red-400">"Wrong Answer"</span><span class="text-slate-300">,</span>
    <span class="text-sky-300">failed</span><span class="text-slate-300">: results.</span><span class="text-sky-300">indexOf</span><span class="text-slate-300">(</span><span class="text-red-400">"WA"</span><span class="text-slate-300">) + </span><span class="text-amber-400">1</span>
  <span class="text-slate-300">};</span>
<span class="text-slate-300">}</span>
</pre>
                            </div>
                            {{-- Fade overlay bottom --}}
                            <div class="absolute bottom-0 left-0 right-0 h-20 pointer-events-none"
                                 style="background: linear-gradient(to top, rgba(8,12,28,0.95), transparent);"></div>
                        </div>
                    </div>

                    {{-- Verdict badges floating near terminal --}}
                    <div class="absolute -top-4 -right-4 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg"
                         style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #34d399;">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block" style="box-shadow: 0 0 6px #34d399;"></span>
                        Accepted
                    </div>
                    <div class="absolute -bottom-3 left-8 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold shadow-lg"
                         style="background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25); color: #fbbf24;">
                        42ms · 4MB
                    </div>
                </div>

                {{-- Bottom: Stats --}}
                <div class="relative z-10">
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="text-center">
                            <p class="text-2xl font-bold gradient-text">10K+</p>
                            <p class="text-xs text-slate-500 mt-0.5">Submissions</p>
                        </div>
                        <div class="text-center" style="border-left: 1px solid rgba(148,163,184,0.1); border-right: 1px solid rgba(148,163,184,0.1);">
                            <p class="text-2xl font-bold gradient-text">500+</p>
                            <p class="text-xs text-slate-500 mt-0.5">Problems</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold gradient-text">98%</p>
                            <p class="text-xs text-slate-500 mt-0.5">Accuracy</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        A secure, role-based competitive programming platform built for contestants, judges, and administrators.
                    </p>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════
                 RIGHT PANEL — Auth Form
            ═══════════════════════════════════════════════ --}}
            <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-12 relative overflow-y-auto"
                 style="background: #030712;">

                {{-- Background blobs (mobile only) --}}
                <div class="lg:hidden absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full opacity-10"
                         style="background: radial-gradient(circle, #4f46e5 0%, transparent 70%);"></div>
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full opacity-10"
                         style="background: radial-gradient(circle, #7c3aed 0%, transparent 70%);"></div>
                </div>

                <div class="relative z-10 w-full max-w-[420px] slide-up">
                    {{-- Mobile brand (visible only on mobile) --}}
                    <div class="lg:hidden flex flex-col items-center gap-3 mb-8">
                        <a href="/" class="flex flex-col items-center gap-2 group">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl shadow-lg shadow-indigo-500/20 transition-transform duration-300 group-hover:scale-110"
                                 style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                                <x-application-logo class="w-8 h-8 text-white" />
                            </div>
                            <span class="text-2xl font-bold tracking-tight text-white mt-1">Judge<span class="brand-text">Mate</span></span>
                        </a>
                        <p class="text-xs text-slate-500">Competitive Programming Judge Platform</p>
                    </div>

                    {{-- Page title slot (optional per-page heading) --}}
                    @if(isset($heading))
                        <div class="mb-7">
                            {{ $heading }}
                        </div>
                    @endif

                    {{-- Form card --}}
                    <div class="glass-card rounded-2xl px-7 py-8 shadow-2xl">
                        {{ $slot }}
                    </div>

                    {{-- Footer --}}
                    <p class="text-center text-xs text-slate-700 mt-6">
                        © {{ date('Y') }} JudgeMate · All rights reserved
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
