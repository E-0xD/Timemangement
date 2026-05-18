<x-layouts::auth :title="__('Verify Email')">
    <div>
        <x-auth-header
            :title="__('Verify your email')"
            :description="__('Check your inbox and click the verification link')"
        />

        @if (session('status') === 'verification-link-sent')
            <div class="mb-5 text-sm text-green-600">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <p class="mb-6 text-sm text-slate-500 leading-relaxed">
            {{ __("Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent you.") }}
        </p>

        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-zinc-50 transition-colors"
                >
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
