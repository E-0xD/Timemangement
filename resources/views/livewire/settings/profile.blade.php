<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">

        {{-- Avatar --}}
        <div class="my-6">
            <p class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Profile Photo') }}</p>
            <div class="flex items-center gap-4">
                @if(Auth::user()->avatarUrl())
                    <img src="{{ Auth::user()->avatarUrl() }}" alt="{{ Auth::user()->name }}"
                         class="h-14 w-14 rounded-full object-cover" />
                @else
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-100 text-lg font-bold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                        {{ Auth::user()->initials() }}
                    </div>
                @endif
                <form wire:submit="updateAvatar" class="flex items-center gap-2">
                    <input type="file" wire:model="avatar_upload" accept="image/*" class="text-sm text-zinc-600 dark:text-zinc-400
                        file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700
                        dark:file:bg-zinc-800 dark:file:text-zinc-300" />
                    <flux:button type="submit" size="sm">{{ __('Upload') }}</flux:button>
                </form>
            </div>
            @error('avatar_upload') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Main profile form --}}
        <form wire:submit="updateProfileInformation" class="w-full space-y-5">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
                @if ($this->hasUnverifiedEmail)
                    <flux:text class="mt-2">
                        {{ __('Your email address is unverified.') }}
                        <flux:link class="cursor-pointer text-sm" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </flux:link>
                    </flux:text>
                @endif
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Phone') }}</label>
                    <input type="tel" wire:model="phone" placeholder="+1 555 000 0000"
                           class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" />
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('School') }}</label>
                    <input type="text" wire:model="school" placeholder="{{ __('University name') }}"
                           class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100" />
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Department') }}</label>
                <select wire:model="department_id"
                        class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                    <option value="">{{ __('Select department') }}</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Bio') }}</label>
                <textarea wire:model="bio" rows="3" maxlength="500" placeholder="{{ __('Tell us about yourself…') }}"
                          class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"></textarea>
                @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Timezone') }}</label>
                    <select wire:model="timezone"
                            class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                        @foreach($timezones as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Language') }}</label>
                    <select wire:model="language"
                            class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                        @foreach($languages as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-1">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>
