<x-layouts::auth :title="__('Create Account')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Create your account')"
            :description="__('Start your academic journey with '. config('site.name'))"
        />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
            @csrf

            <div>
                <flux:input
                    name="name"
                    :label="__('Full name')"
                    :value="old('name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="__('John Doe')"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="you@example.com"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Create a strong password')"
                    viewable
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Repeat your password')"
                    viewable
                />
            </div>

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </form>

        <div class="space-x-1 text-sm text-center text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')">{{ __('Sign in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
