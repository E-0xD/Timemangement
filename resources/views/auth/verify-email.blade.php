<x-layouts::auth :title="__('Verify Email')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Verify your email')"
            :description="__('Check your inbox and click the verification link')"
        />

        @if (session('status') === 'verification-link-sent')
            <div class="text-sm font-medium text-center text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <flux:text class="text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __("Thanks for signing up! Before getting started, please verify your email address by clicking on the link we sent you.") }}
        </flux:text>

        <form method="POST" action="{{ route('verification.send') }}" class="flex flex-col gap-4">
            @csrf
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Resend verification email') }}
            </flux:button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <flux:button variant="ghost" type="submit" class="w-full">
                {{ __('Sign out') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
