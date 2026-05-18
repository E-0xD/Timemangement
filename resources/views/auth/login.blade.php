<x-layouts::auth :title="__('Sign In')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Welcome back')"
            :description="__('Sign in to your '. config('site.name') .' account')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
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
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Your password')"
                        viewable
                    />
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')">
                        {{ __('Forgot password?') }}
                    </flux:link>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Sign in') }}
            </flux:button>
        </form>

        <div class="space-x-1 text-sm text-center text-zinc-600 dark:text-zinc-400">
            <span>{{ __("Don't have an account?") }}</span>
            <flux:link :href="route('register')">{{ __('Create one') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
