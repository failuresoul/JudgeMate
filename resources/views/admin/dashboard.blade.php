@extends('layouts.admin')

@section('title', 'Admin Dashboard - JudgeMate')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-200 to-indigo-400 bg-clip-text text-transparent">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-slate-400">Overview and control center for the JudgeMate platform.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-400 ring-1 ring-indigo-500/20">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
            Super Admin Access
        </span>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Users Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Total Users</span>
                <div class="rounded-lg bg-indigo-500/10 p-2 text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-bold tracking-tight text-white">{{ $totalUsers }}</span>
                @if($pendingUsers > 0)
                    <span class="inline-flex items-center gap-0.5 rounded-full bg-amber-500/10 px-2 py-0.5 text-xs font-medium text-amber-400 ring-1 ring-amber-500/10">
                        {{ $pendingUsers }} pending
                    </span>
                @endif
            </div>
            <p class="mt-1 text-xs text-slate-500">Registered on the platform</p>
        </div>

        <!-- Problems Card -->
        <a href="{{ route('problems.index') }}" class="block relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl hover:border-slate-700 hover:bg-slate-900/70 transition-all duration-200 group">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400 group-hover:text-slate-200 transition-colors">Active Problems</span>
                <div class="rounded-lg bg-violet-500/10 p-2 text-violet-400 group-hover:bg-violet-500/20 group-hover:text-violet-300 transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-bold tracking-tight text-white">{{ $totalProblems }}</span>
            </div>
            <p class="mt-1 text-xs text-slate-500">Problems in the repository</p>
        </a>

        <!-- Contests Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Total Contests</span>
                <div class="rounded-lg bg-emerald-500/10 p-2 text-emerald-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-bold tracking-tight text-white">{{ $totalContests }}</span>
            </div>
            <p class="mt-1 text-xs text-slate-500">Scheduled and completed</p>
        </div>

        <!-- Submissions Card -->
        <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-400">Submissions</span>
                <div class="rounded-lg bg-rose-500/10 p-2 text-rose-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-bold tracking-tight text-white">{{ $totalSubmissions }}</span>
            </div>
            <p class="mt-1 text-xs text-slate-500">Evaluated solutions</p>
        </div>
    </div>

    {{-- Quick Actions & Activity Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Quick Actions Card -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-white">Administrative Actions</h2>
            <p class="mt-1 text-xs text-slate-400">Quick shortcuts to manage various modules.</p>
            <div class="mt-6 space-y-3">
                <a href="{{ route('admin.users.index') }}" class="flex w-full items-center justify-between rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 px-4 py-3 text-sm text-slate-300 hover:text-white transition-all group">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Review Pending Registrations
                    </span>
                    <svg class="h-4 w-4 text-slate-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="#" class="flex w-full items-center justify-between rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 px-4 py-3 text-sm text-slate-300 hover:text-white transition-all group opacity-60 cursor-not-allowed">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Contest
                    </span>
                    <span class="text-[10px] text-slate-500 bg-slate-950 px-2 py-0.5 rounded">TBD</span>
                </a>
                <a href="#" class="flex w-full items-center justify-between rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-700 px-4 py-3 text-sm text-slate-300 hover:text-white transition-all group opacity-60 cursor-not-allowed">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Add New Problem
                    </span>
                    <span class="text-[10px] text-slate-500 bg-slate-950 px-2 py-0.5 rounded">TBD</span>
                </a>
            </div>
        </div>

        <!-- Recent Activities Card -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">System Activity Log</h2>
                <span class="text-xs text-indigo-400 font-medium">Auto-refresh active</span>
            </div>
            <div class="mt-6 flow-root">
                <ul class="-mb-8">
                    <li>
                        <div class="relative pb-8">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-800" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-emerald-500/10 flex items-center justify-center ring-8 ring-slate-950 text-emerald-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-slate-300">New user approval queue updated</p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-xs text-slate-500">
                                        <time datetime="2026-07-05">Just now</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="relative pb-8">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-800" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-indigo-500/10 flex items-center justify-center ring-8 ring-slate-950 text-indigo-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-slate-300">Role seeding completed: Guest, Contestant, ProblemSetter, Admin</p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-xs text-slate-500">
                                        <time datetime="2026-07-05">2 mins ago</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="relative">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-rose-500/10 flex items-center justify-center ring-8 ring-slate-950 text-rose-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-slate-300">Admin security middleware initialized on /admin route</p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-xs text-slate-500">
                                        <time datetime="2026-07-05">5 mins ago</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
