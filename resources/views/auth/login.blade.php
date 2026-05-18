<x-layouts::auth :title="__('Sign In')">
    <div>
        <x-auth-header
            :title="__('Welcome back')"
            :description="__('Sign in to your account to continue')"
        />

        <x-auth-session-status class="mb-5 text-sm text-green-600" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="you@example.com"
                />
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm font-medium text-slate-700">{{ __('Password') }}</span>
                    <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-700">
                        {{ __('Forgot password?') }}
                    </a>
                </div>
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Your password')"
                    viewable
                />
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <flux:checkbox name="remember" :label="__('Stay signed in')" :checked="old('remember')" />

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Sign in') }}
            </flux:button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">{{ __('Create one') }}</a>
        </p>
    </div>
</x-layouts::auth>
