<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JudgeMate - CP Judge Platform')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Fonts Style Override -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        pre, code, .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased">
    <div class="min-h-screen flex flex-col bg-slate-950 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-slate-950">
        
        <!-- Header / Navbar -->
        <header class="sticky top-0 z-40 w-full border-b border-slate-800/60 bg-slate-950/80 backdrop-blur-md">
            <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Brand / Logo & Toggle -->
                <div class="flex items-center gap-4">
                    <button id="mobile-sidebar-toggle" type="button" class="lg:hidden text-slate-400 hover:text-slate-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <a href="/" class="flex items-center gap-2 group">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-100 to-indigo-400 bg-clip-text text-transparent">Judge<span class="text-indigo-500">Mate</span></span>
                    </a>
                </div>

                <!-- Center Search / Info Bar (Optional/Placeholder) -->
                <div class="hidden md:flex max-w-md w-full items-center relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search problems, submissions, users..." class="w-full pl-9 pr-4 py-1.5 bg-slate-900 border border-slate-800 rounded-lg text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                </div>

                <!-- Right Utility Bar -->
                <div class="flex items-center gap-4">
                    <!-- Status Indicator -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 text-xs font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Judge Engine: Active
                    </div>

                    <!-- Notification Button -->
                    <button class="relative rounded-lg p-2 text-slate-400 hover:text-slate-200 hover:bg-slate-900 transition-colors">
                        <span class="sr-only">Notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                    </button>

                    <!-- User Profile Dropdown & Logout -->
                    <div class="flex items-center gap-3 border-l border-slate-800/80 pl-4">
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-sm font-semibold text-slate-200">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-indigo-400 font-medium">{{ '@' . auth()->user()->username }}</span>
                        </div>
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-500 flex items-center justify-center font-bold text-white shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <!-- Logout Action Form -->
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="rounded-lg p-1.5 text-rose-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Log Out">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Layout Body -->
        <div class="flex overflow-hidden" style="height: calc(100vh - 64px);">
            <!-- Sidebar Navigation -->
            @section('sidebar')
            <aside id="sidebar-nav" class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full lg:translate-x-0 lg:relative lg:flex-shrink-0 flex flex-col justify-between border-r border-slate-800/60 bg-slate-950 overflow-y-auto transition-transform duration-300 ease-in-out">
                <!-- Sidebar Top -->
                <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">
                    <!-- Nav Section Title -->
                    <div>
                        <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Navigation</span>
                        <ul class="mt-3 space-y-1">
                            <li>
                                <a href="/" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::is('/') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/50 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Problems
                                    <span class="ml-auto bg-slate-800 text-slate-400 text-xs px-2 py-0.5 rounded-full">120</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/50 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Submissions
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/50 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Leaderboard
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Management Section Title -->
                    @role('Admin')
                    <div>
                        <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Access Management</span>
                        <ul class="mt-3 space-y-1">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium {{ Request::routeIs('admin.users.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }} transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Users
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-900/50 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Roles & Permissions
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endrole
                </div>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-slate-800/60 bg-slate-950/50">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-sm">
                            v1.0
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-200">JudgeMate Platform</p>
                            <p class="text-[10px] text-slate-500">Built for CP Judges</p>
                        </div>
                    </div>
                </div>
            </aside>
            @show

            <!-- Sidebar Overlay for mobile -->
            <div id="sidebar-overlay" class="fixed inset-0 z-20 bg-slate-950/60 backdrop-blur-sm hidden lg:hidden animate-fade-in"></div>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-slate-950/40 flex flex-col justify-between">
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 w-full">
                    @yield('content')
                </div>
                <!-- Footer -->
                <footer class="border-t border-slate-800/60 bg-slate-950 px-4 py-6 sm:px-6 lg:px-8 text-center text-xs text-slate-500 w-full mt-auto">
                    &copy; {{ date('Y') }} JudgeMate. All rights reserved. Laravel 12 & Spatie Powered.
                </footer>
            </main>
        </div>
    </div>

    <!-- Toggle Script for Mobile Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('mobile-sidebar-toggle');
            const sidebar = document.getElementById('sidebar-nav');
            const overlay = document.getElementById('sidebar-overlay');

            const toggleSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            };

            toggleBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>
