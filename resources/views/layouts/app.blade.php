<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'JudgeMate - CP Judge Platform')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Fonts Style Override -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        pre,
        code,
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent FOUC in Dark Mode -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="min-h-screen flex flex-col bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-indigo-500/30 transition-colors duration-200">

    <!-- Top Navigation Bar -->
    <header
        class="sticky top-0 z-40 w-full border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-950/90 backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">

                <!-- Left Side: Logo & Links -->
                <div class="flex items-center gap-4 lg:gap-8">
                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-1.5 sm:gap-2 group">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                            </svg>
                        </div>
                        <span
                            class="hidden min-[375px]:inline-block text-xl font-bold tracking-tight bg-gradient-to-r from-slate-900 via-slate-700 to-indigo-600 dark:from-white dark:via-slate-100 dark:to-indigo-400 bg-clip-text text-transparent truncate">Judge<span
                                class="text-indigo-600 dark:text-indigo-500">Mate</span></span>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden lg:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::is('/') || Request::is('dashboard') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Home</a>
                        <a href="{{ route('contests.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::routeIs('contests.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Contests</a>
                        <a href="{{ route('problems.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::is('problems*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Problemset</a>
                        <a href="{{ route('submissions.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::routeIs('submissions.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Submissions</a>
                        <a href="{{ route('leaderboard') }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::is('leaderboard*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Standings</a>
                        <a href="{{ route('blogs.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::is('blogs*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Inspiration</a>
                        @auth
                            @role('Admin')
                            <a href="{{ route('admin.users.index') }}"
                                class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::routeIs('admin.users.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Admin</a>
                            @endrole
                            @role('ProblemSetter')
                            <a href="{{ route('judge.dashboard') }}"
                                class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ Request::routeIs('judge.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Judge</a>
                            @endrole
                        @endauth
                    </nav>
                </div>

                <!-- Right Side: Theme Toggle & Profile -->
                <div class="flex items-center gap-1 sm:gap-4 shrink-0">

                    <!-- Theme Toggle -->
                    <button @click="darkMode = !darkMode"
                        class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors focus:outline-none"
                        title="Toggle Theme">
                        <!-- Sun Icon (shows in dark mode) -->
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (shows in light mode) -->
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Notifications -->
                    @auth
                        <div class="relative" x-data="{ openNotifications: false, unreadCount: {{ auth()->user()->unreadNotifications()->count() }} }">
                            <button
                                @click="openNotifications = !openNotifications"
                                @click.outside="openNotifications = false"
                                class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors focus:outline-none"
                            >
                                <span class="sr-only">Notifications</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <span x-show="unreadCount > 0" x-text="unreadCount"
                                    class="absolute top-1 right-1 flex items-center justify-center px-1 py-0.5 text-[9px] font-bold leading-none text-white bg-indigo-600 rounded-full min-w-[14px]"
                                    style="display: none;"></span>
                            </button>

                            <!-- Notifications Dropdown -->
                            <div x-show="openNotifications" x-transition
                                class="absolute right-0 mt-2 w-80 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl overflow-hidden py-1 z-50"
                                style="display: none;">
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                    <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Notifications</h3>
                                    <button @click="fetch('{{ route('notifications.mark-read') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => unreadCount = 0)" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Mark all read</button>
                                </div>
                                <div class="max-h-[300px] overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                        <a href="{{ route('notifications.redirect', $notification->id) }}" 
                                           class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ $notification->unread() ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
                                            <div class="text-xl shrink-0 mt-0.5">
                                                {{ $notification->data['icon'] ?? '🔔' }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-0.5">
                                                    {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if($notification->unread())
                                                <div class="shrink-0 w-2 h-2 rounded-full bg-indigo-600 mt-2"></div>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                            No recent notifications.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endauth

                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1 hidden sm:block"></div>

                    <!-- User Profile / Auth Links -->
                    @auth
                        <div class="flex items-center gap-3">
                            <span
                                class="hidden lg:block text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ auth()->user()->username }}</span>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false"
                                    class="flex h-8 w-8 items-center justify-center rounded-full border border-indigo-200 dark:border-indigo-800 bg-white dark:bg-slate-800 font-bold text-indigo-600 dark:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" x-transition
                                    class="absolute right-0 mt-2 w-48 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-lg overflow-hidden py-1"
                                    style="display: none;">
                                    <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800 mb-1">
                                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                            {{ auth()->user()->name }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">Log
                                            out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white px-3 py-1.5 transition-colors">Log
                                in</a>
                            <a href="{{ route('register') }}"
                                class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-1.5 rounded-lg transition-colors">Sign
                                up</a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Toggle -->
                    <div class="lg:hidden flex items-center" x-data="{ mobileMenuOpen: false }">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none">
                            <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Mobile Menu Dropdown -->
                        <div x-show="mobileMenuOpen" @click.outside="mobileMenuOpen = false" x-transition
                            class="absolute top-16 left-0 right-0 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-lg p-4 flex flex-col gap-2 z-50"
                            style="display: none;">
                            <a href="{{ route('dashboard') }}"
                                class="px-3 py-2 rounded-lg text-sm font-medium {{ Request::is('/') || Request::is('dashboard') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Home</a>
                            <a href="{{ route('contests.index') }}"
                                class="px-3 py-2 rounded-lg text-sm font-medium {{ Request::routeIs('contests.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Contests</a>
                            <a href="{{ route('problems.index') }}"
                                class="px-3 py-2 rounded-lg text-sm font-medium {{ Request::is('problems*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Problemset</a>
                            <a href="{{ route('submissions.index') }}"
                                class="px-3 py-2 rounded-lg text-sm font-medium {{ Request::routeIs('submissions.*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Submissions</a>
                            <a href="{{ route('leaderboard') }}"
                                class="px-3 py-2 rounded-lg text-sm font-medium {{ Request::is('leaderboard*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Standings</a>
                            <a href="{{ route('blogs.index') }}"
                                class="px-3 py-2 rounded-lg text-sm font-medium {{ Request::is('blogs*') ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">Inspiration</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <main class="flex-1 w-full">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            {{-- Global Session Alerts --}}
            @if(session('success'))
                <div
                    class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div
                    class="mb-6 rounded-xl border border-red-200 dark:border-red-500/20 bg-red-50 dark:bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer
        class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-6 sm:px-6 lg:px-8 text-center text-sm text-slate-500 dark:text-slate-400 mt-auto">
        &copy; {{ date('Y') }} JudgeMate. All rights reserved.
    </footer>

    @stack('scripts')
</body>

</html>