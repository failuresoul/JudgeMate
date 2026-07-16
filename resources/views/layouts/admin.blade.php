@extends('layouts.app')

@section('sidebar')
<aside id="sidebar-nav" class="relative lg:flex-shrink-0 w-64 flex-none hidden lg:flex flex-col justify-between border-r border-slate-800/60 bg-slate-950 overflow-y-auto transition-transform duration-300 ease-in-out">
    <!-- Sidebar Top -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">
        <!-- Section Title: Admin Control Panel -->
        <div>
            <span class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Admin Control Panel</span>
            <ul class="mt-3 space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::routeIs('admin.dashboard') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- Problems -->
                <li>
                    <a href="{{ route('problems.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::is('problems*') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Problems
                    </a>
                </li>

                <!-- Contests -->
                <li>
                    <a href="{{ route('contests.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::routeIs('contests.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Contests
                    </a>
                </li>

                <!-- Users -->
                <li>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::routeIs('admin.users.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Users
                    </a>
                </li>

                <!-- Submissions -->
                <li>
                    <a href="{{ route('submissions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::routeIs('submissions.*') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Submissions
                    </a>
                </li>
                <!-- Leaderboard -->
                <li>
                    <a href="{{ route('leaderboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ Request::is('leaderboard*') ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 pl-2.5' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/50' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Leaderboard
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-800/60 bg-slate-950/50">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-bold text-sm">
                ADM
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-200">Admin Mode</p>
                <p class="text-[10px] text-slate-500">System Administrator</p>
            </div>
        </div>
    </div>
</aside>
@endsection
