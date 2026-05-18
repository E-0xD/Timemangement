<x-layouts::auth :title="__('Confirm Password')">
    <div>
        <x-auth-header
            :title="__('Confirm your password')"
            :description="__('Please confirm your password before continuing')"
        />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-5">
            @csrf

            <div>
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autofocus
                    autocomplete="current-password"
                    viewable
                />
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Confirm') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
