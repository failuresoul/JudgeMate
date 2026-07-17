@extends('layouts.admin')

@section('title', 'User Management - Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">User Management</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Review and approve registration requests.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-400 ring-1 ring-red-500/20">
            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
            Admin Panel
        </span>
    </div>



    {{-- Status Tabs --}}
    <div class="flex gap-1 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-1">
        @foreach(['pending' => ['label'=>'Pending','color'=>'amber'], 'approved' => ['label'=>'Approved','color'=>'emerald'], 'rejected' => ['label'=>'Rejected','color'=>'red'], 'all' => ['label'=>'All Users','color'=>'slate']] as $key => $tab)
            <a href="{{ route('admin.users.index', ['filter' => $key]) }}"
               class="flex-1 flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150
                      {{ $filter === $key ? 'bg-white border border-slate-200 dark:border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-white shadow' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:text-slate-200' }}">
                {{ $tab['label'] }}
                @if(isset($counts[$key]))
                    <span class="rounded-md bg-slate-700/60 px-1.5 py-0.5 text-xs font-bold {{ $counts[$key] > 0 && $key === 'pending' ? 'text-amber-600 dark:text-amber-400' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $counts[$key] }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Users Table --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/30 overflow-hidden shadow-sm dark:shadow-xl">
        @if($users->isEmpty())
            <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white border border-slate-200 dark:border-slate-200 dark:border-slate-700 dark:bg-slate-800">
                    <svg class="h-6 w-6 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">No users found for this filter.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-left">
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">User</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Role</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Registered</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-200 dark:divide-slate-800/60">
                        @foreach($users as $user)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors duration-100">
                            {{-- User Info --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-bold text-slate-900 dark:text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role Badge --}}
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    @php
                                        $roleColors = [
                                            'Contestant'    => 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 ring-indigo-500/20',
                                            'ProblemSetter' => 'text-violet-400 bg-violet-500/10 ring-violet-500/20',
                                            'Guest'         => 'text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-500/10 ring-slate-200 dark:ring-slate-500/20',
                                            'Admin'         => 'text-red-400 bg-red-500/10 ring-red-500/20',
                                        ];
                                        $color = $roleColors[$role->name] ?? 'text-slate-600 dark:text-slate-400 bg-slate-700/50 ring-slate-600/30';
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 {{ $color }}">
                                        {{ $role->name === 'ProblemSetter' ? 'Judge/Setter' : $role->name }}
                                    </span>
                                @endforeach
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @if($user->status === 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-600 dark:text-amber-400 ring-1 ring-amber-200 dark:ring-amber-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span> Pending
                                    </span>
                                @elseif($user->status === 'approved')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-500/10 px-2.5 py-1 text-xs font-semibold text-red-400 ring-1 ring-red-500/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span> Rejected
                                    </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ $user->created_at->format('M d, Y') }}<br>
                                <span class="text-slate-600">{{ $user->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                @if($user->id !== auth()->id() && !$user->hasRole('Admin'))
                                    <div class="flex items-center justify-end gap-2">
                                        @if($user->status !== 'approved')
                                            {{-- Approve --}}
                                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-500/20 hover:bg-emerald-500/20 transition-colors duration-150"
                                                    onclick="return confirm('Approve {{ addslashes($user->name) }}?')">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if($user->status !== 'rejected')
                                            {{-- Reject (opens inline form) --}}
                                            <button type="button"
                                                onclick="document.getElementById('reject-form-{{ $user->id }}').classList.toggle('hidden')"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-400 ring-1 ring-red-500/20 hover:bg-red-500/20 transition-colors duration-150">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Reject
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Inline reject reason form --}}
                                    @if($user->status !== 'rejected')
                                    <div id="reject-form-{{ $user->id }}" class="hidden mt-2">
                                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                            @csrf
                                            <div class="flex gap-2">
                                                <input type="text" name="reason" placeholder="Reason (optional)"
                                                    class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white border border-slate-200 dark:border-slate-200 dark:border-slate-700 dark:bg-slate-800 px-2 py-1 text-xs text-slate-900 dark:text-slate-200 placeholder-slate-500 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500/50">
                                                <button type="submit"
                                                    class="rounded-lg bg-red-600 px-2 py-1 text-xs font-semibold text-slate-900 dark:text-white hover:bg-red-500 transition-colors">
                                                    Confirm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif

                                    {{-- Show rejection reason if rejected --}}
                                    @if($user->status === 'rejected' && $user->rejected_reason)
                                        <p class="mt-1 text-xs text-red-400/70 italic text-right">"{{ $user->rejected_reason }}"</p>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 dark:bg-slate-500/10 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:text-slate-400 ring-1 ring-slate-200 dark:ring-slate-500/20">
                                        System Protected
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
