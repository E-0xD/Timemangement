<x-layouts::auth :title="__('Forgot Password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Forgot your password?')"
            :description="__('Enter your email and we\'ll send you a reset link')"
        />

        @if (session('status'))
            <div class="text-sm font-medium text-center text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
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

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Send reset link') }}
            </flux:button>
        </form>

        <div class="text-sm text-center text-zinc-600 dark:text-zinc-400">
            <flux:link :href="route('login')">{{ __('Back to sign in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
