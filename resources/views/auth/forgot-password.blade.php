<x-guest-layout>
    <div class="mb-4 text-sm text-slate-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('reset_url'))
        <div class="mb-6 p-4 text-sm text-emerald-800 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-xl border border-emerald-200 dark:border-emerald-500/20">
            <strong class="font-bold block mb-2">Reset Link:</strong>
            <div style="word-break: break-all; overflow-wrap: anywhere; white-space: normal;">
                <a href="{{ session('reset_url') }}" class="font-mono text-[11px] leading-relaxed underline hover:text-emerald-900 dark:hover:text-emerald-300">{{ session('reset_url') }}</a>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
