<x-guest-layout>
    <div class="text-center">
        @if(session('success'))
            {{-- Fresh registration --}}
            <div class="flex flex-col items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-500/20 ring-1 ring-amber-500/30">
                    <svg class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl font-bold text-white">Registration Submitted!</h1>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        {{ session('success') }}
                    </p>
                </div>

                <div class="w-full rounded-xl border border-amber-500/20 bg-amber-500/5 p-4 text-left">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-400 mb-2">What happens next?</p>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-amber-400">①</span>
                            An Admin will review your registration request.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-amber-400">②</span>
                            Once approved, you can log in and access your dashboard.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-amber-400">③</span>
                            You'll be able to log in with your email and password.
                        </li>
                    </ul>
                </div>
            </div>

        @elseif(session('rejected'))
            {{-- Rejected user tried to log in --}}
            <div class="flex flex-col items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-500/20 ring-1 ring-red-500/30">
                    <svg class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Registration Rejected</h1>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Your registration request was not approved.
                    </p>
                    @if(session('reason'))
                        <p class="mt-2 text-sm text-red-400 italic">"{{ session('reason') }}"</p>
                    @endif
                </div>
            </div>

        @else
            {{-- Still pending — tried to log in --}}
            <div class="flex flex-col items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-500/20 ring-1 ring-amber-500/30">
                    <svg class="h-8 w-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Approval Pending</h1>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                        Your account is still awaiting Admin approval. Please check back later.
                    </p>
                </div>
            </div>
        @endif

        <div class="mt-6 border-t border-slate-800 pt-4">
            <a href="{{ route('login') }}"
               class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors duration-150">
                ← Back to Login
            </a>
        </div>
    </div>
</x-guest-layout>
