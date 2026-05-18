<x-layouts::auth :title="__('Forgot Password')">
    <div>
        <x-auth-header
            :title="__('Forgot your password?')"
            :description="__('Enter your email and we\'ll send you a reset link')"
        />

        @if (session('status'))
            <div class="mb-5 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Send reset link') }}
            </flux:button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">{{ __('Back to sign in') }}</a>
        </p>
    </div>
</x-layouts::auth>
