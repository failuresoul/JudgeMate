<x-guest-layout>
    <x-slot:heading>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create your account</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Join JudgeMate — submit code, set problems, compete</p>
    </x-slot:heading>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Full Name</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none transition-all duration-200 focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border @error('name') border-red-500 ring-1 ring-red-500/30 @else border-slate-300 dark:border-slate-700 @enderror bg-white dark:bg-slate-900/70"
                       placeholder="John Doe">
            </div>
            @error('name')
                <p class="mt-1 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Username --}}
        <div>
            <label for="username" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Username</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none text-sm font-medium">@</span>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username"
                       class="w-full pl-8 pr-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none transition-all duration-200 focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border @error('username') border-red-500 ring-1 ring-red-500/30 @else border-slate-300 dark:border-slate-700 @enderror bg-white dark:bg-slate-900/70"
                       placeholder="yourhandle">
            </div>
            @error('username')
                <p class="mt-1 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Email Address</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none transition-all duration-200 focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border @error('email') border-red-500 ring-1 ring-red-500/30 @else border-slate-300 dark:border-slate-700 @enderror bg-white dark:bg-slate-900/70"
                       placeholder="you@example.com">
            </div>
            @error('email')
                <p class="mt-1 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Role Selection --}}
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Register As</label>
            <div class="grid grid-cols-2 gap-2.5" id="role-cards">
                {{-- Contestant card --}}
                <label for="role_contestant" class="role-card cursor-pointer rounded-xl p-3 transition-all duration-200 border bg-slate-50 dark:bg-slate-900/60 border-slate-300 dark:border-slate-700"
                       onclick="selectRole(this)">
                    <input type="radio" id="role_contestant" name="role" value="Contestant" class="sr-only"
                           {{ old('role') === 'Contestant' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center role-icon bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 role-label">Contestant</p>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Submit solutions & compete</p>
                        </div>
                    </div>
                </label>

                {{-- Judge / Problem Setter card --}}
                <label for="role_judge" class="role-card cursor-pointer rounded-xl p-3 transition-all duration-200 border bg-slate-50 dark:bg-slate-900/60 border-slate-300 dark:border-slate-700"
                       onclick="selectRole(this)">
                    <input type="radio" id="role_judge" name="role" value="ProblemSetter" class="sr-only"
                           {{ old('role') === 'ProblemSetter' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center gap-2 text-center">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center role-icon bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 role-label">Judge / Setter</p>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Create & review problems</p>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Approval notice --}}
            <div class="flex items-center gap-2 mt-2 px-3 py-2 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20">
                <svg class="w-3.5 h-3.5 text-amber-500 dark:text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-[10px] text-amber-700 dark:text-amber-400/80">Admin approval required before access is granted</p>
            </div>

            @error('role')
                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none transition-all duration-200 focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border @error('password') border-red-500 ring-1 ring-red-500/30 @else border-slate-300 dark:border-slate-700 @enderror bg-white dark:bg-slate-900/70"
                       placeholder="Min. 8 characters">
                <button type="button" onclick="togglePwd(this,'password')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">
                    <svg class="w-4 h-4 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="w-4 h-4 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Confirm Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-2.5 rounded-xl text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 outline-none transition-all duration-200 focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-500/60 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/70"
                       placeholder="Re-enter your password">
                <button type="button" onclick="togglePwd(this,'password_confirmation')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-400 transition-colors">
                    <svg class="w-4 h-4 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="w-4 h-4 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-5 rounded-xl text-sm font-semibold text-white transition-all duration-200 hover:opacity-90 hover:shadow-lg hover:shadow-indigo-500/30 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-indigo-500/60 mt-1 bg-gradient-to-br from-indigo-500 to-purple-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Create Account
        </button>

        {{-- Login link --}}
        <p class="text-center text-sm text-slate-500 dark:text-slate-500 pt-2 border-t border-slate-200 dark:border-slate-800">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-500 dark:text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 font-medium transition-colors ml-1">Sign in</a>
        </p>
    </form>
</x-guest-layout>

<style>
.role-card.selected {
    border-color: #6366f1 !important;
    background: #e0e7ff !important;
    box-shadow: 0 0 0 1px #6366f1, inset 0 0 12px rgba(99,102,241,0.1);
}
.role-card.selected .role-label { color: #4338ca; }

html.dark .role-card.selected {
    border-color: rgba(99,102,241,0.6) !important;
    background: rgba(99,102,241,0.08) !important;
    box-shadow: 0 0 0 1px rgba(99,102,241,0.4), inset 0 0 20px rgba(99,102,241,0.05);
}
html.dark .role-card.selected .role-label { color: #a5b4fc; }
</style>

<script>
function selectRole(label) {
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
    label.classList.add('selected');
    label.querySelector('input[type=radio]').checked = true;
}
function togglePwd(btn, fieldId) {
    const input = document.getElementById(fieldId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.querySelector('.eye-open').classList.toggle('hidden', !isText);
    btn.querySelector('.eye-closed').classList.toggle('hidden', isText);
}
// Pre-select if old value exists
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.role-card input:checked');
    if (checked) checked.closest('.role-card').classList.add('selected');
});
</script>
