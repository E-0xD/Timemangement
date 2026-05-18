<x-layouts::auth :title="__('Create Account')">
    <div>
        <x-auth-header
            :title="__('Create your account')"
            :description="__('Start your academic journey with ' . config('site.name'))"
        />

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
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
                    placeholder="Jane Doe"
                />
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <flux:input
                    name="school"
                    :label="__('School / University')"
                    :value="old('school')"
                    type="text"
                    autocomplete="organization"
                    :placeholder="__('e.g. University of Lagos (optional)')"
                />
                @error('school')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    {{ __('Department') }}
                    <span class="text-slate-400 font-normal ml-1">{{ __('(optional)') }}</span>
                </label>
                <select
                    name="department_id"
                    class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">{{ __('Select your department') }}</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
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

        <p class="mt-8 text-center text-sm text-slate-500">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">{{ __('Sign in') }}</a>
        </p>
    </div>
</x-layouts::auth>
