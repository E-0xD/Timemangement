<x-layouts::auth :title="__('Reset Password')">
    <div>
        <x-auth-header
            :title="__('Set new password')"
            :description="__('Choose a strong password for your account')"
        />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email', request()->string('email'))"
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
                <flux:input
                    name="password"
                    :label="__('New password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Enter new password')"
                    viewable
                />
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm new password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirm new password')"
                    viewable
                />
            </div>

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Reset password') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
