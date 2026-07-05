<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Judge Panel — JudgeMate')</title>
    <meta name="description" content="JudgeMate Judge Panel — Create and manage problems, review submissions.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        pre, code, .font-mono { font-family: 'JetBrains Mono', monospace; }

        @keyframes pulse-ring {
            0%, 100% { transform: scale(1); opacity: .8; }
            50%       { transform: scale(1.4); opacity: 0; }
        }
        .pulse-ring { animation: pulse-ring 2s ease-out infinite; }

        @keyframes slide-in-left {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slide-in-left .35s cubic-bezier(.22,1,.36,1) both; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased bg-slate-950">

<div class="min-h-screen flex flex-col" style="background: radial-gradient(ellipse at 80% 0%, rgba(124,58,237,0.07) 0%, transparent 60%), #020817;">

    {{-- ══════════ TOP NAVBAR ══════════ --}}
    <header class="sticky top-0 z-40 w-full border-b border-slate-800/60 bg-slate-950/80 backdrop-blur-md">
        <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            {{-- Left: Brand + mobile toggle --}}
            <div class="flex items-center gap-4">
                <button id="mobile-sidebar-toggle" type="button"
                        class="lg:hidden text-slate-400 hover:text-slate-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <a href="{{ route('judge.dashboard') }}" class="flex items-center gap-2.5 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl shadow-lg shadow-violet-500/20 group-hover:scale-105 transition-transform duration-200"
                         style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold tracking-tight text-white">Judge<span style="color:#a78bfa">Mate</span></span>
                        <span class="hidden sm:block text-[10px] font-semibold uppercase tracking-widest" style="color:#7c3aed;">Judge Panel</span>
                    </div>
                </a>
            </div>

            {{-- Center: Search bar --}}
            <div class="hidden md:flex max-w-sm w-full items-center relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" placeholder="Search problems, submissions..."
                       class="w-full pl-9 pr-4 py-1.5 rounded-lg text-sm text-slate-300 placeholder-slate-500 transition-colors focus:outline-none focus:border-violet-500 focus:ring-1 focus:ring-violet-500"
                       style="background: rgba(30,27,75,0.4); border: 1px solid rgba(109,40,217,0.25);">
            </div>

            {{-- Right: Status pill + notification + user --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
                     style="background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.25); color: #a78bfa;">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="pulse-ring absolute inline-flex h-full w-full rounded-full" style="background:#7c3aed;"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full" style="background:#a78bfa;"></span>
                    </span>
                    Judge Mode
                </div>

                {{-- Notification --}}
                <button class="relative rounded-lg p-2 text-slate-400 hover:text-slate-200 hover:bg-slate-900 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </button>

                {{-- User info + logout --}}
                <div class="flex items-center gap-3 border-l border-slate-800/80 pl-3">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-sm font-semibold text-slate-200">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] font-semibold uppercase tracking-widest" style="color:#a78bfa;">Problem Setter</span>
                    </div>
                    <div class="h-9 w-9 rounded-xl flex items-center justify-center font-bold text-white text-sm shadow-md"
                         style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-1.5 text-rose-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Log out">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- ══════════ BODY: Sidebar + Main ══════════ --}}
    <div class="flex overflow-hidden" style="height: calc(100vh - 64px);">

        {{-- Sidebar --}}
        <aside id="sidebar-nav"
               class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full lg:translate-x-0 lg:relative lg:flex-shrink-0 flex flex-col justify-between transition-transform duration-300 ease-in-out"
               style="background: #030712; border-right: 1px solid rgba(109,40,217,0.15);">

            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-7">
                {{-- Overview section --}}
                <div>
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest mb-3" style="color:#4c1d95;">Overview</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('judge.dashboard') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 animate-slide-in
                               {{ Request::routeIs('judge.dashboard') ? 'text-violet-300' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}"
                               @if(Request::routeIs('judge.dashboard')) style="background: rgba(124,58,237,0.12); border: 1px solid rgba(124,58,237,0.25);" @endif>
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Problem Management --}}
                <div>
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest mb-3" style="color:#4c1d95;">Problem Management</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create Problem
                                <span class="ml-auto text-[10px] font-semibold px-1.5 py-0.5 rounded" style="background:rgba(124,58,237,0.2);color:#a78bfa;">New</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                My Problems
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Test Cases
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Judging --}}
                <div>
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest mb-3" style="color:#4c1d95;">Judging</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Pending Review
                                <span class="ml-auto text-[10px] font-semibold px-1.5 py-0.5 rounded-full" style="background:rgba(245,158,11,0.15);color:#fbbf24;">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                All Submissions
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Contests --}}
                <div>
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest mb-3" style="color:#4c1d95;">Contests</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/60 transition-all duration-150">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Manage Contests
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Sidebar footer --}}
            <div class="p-4" style="border-top: 1px solid rgba(109,40,217,0.12);">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl flex items-center justify-center font-bold text-white text-sm flex-shrink-0"
                         style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] truncate" style="color:#7c3aed;">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-20 hidden lg:hidden" style="background:rgba(2,8,23,0.7);backdrop-filter:blur(4px);"></div>

        {{-- Main content (only this scrolls) --}}
        <main class="flex-1 overflow-y-auto flex flex-col justify-between">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 w-full">
                @yield('content')
            </div>
            {{-- Footer --}}
            <footer class="text-center text-xs py-4 border-t w-full mt-auto" style="border-color:rgba(109,40,217,0.1);color:#4c1d95;">
                © {{ date('Y') }} JudgeMate · Judge Panel · Laravel 12 & Spatie Powered
            </footer>
        </main>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle  = document.getElementById('mobile-sidebar-toggle');
    const sidebar = document.getElementById('sidebar-nav');
    const overlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
    toggle?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
});
</script>
</body>
</html>
