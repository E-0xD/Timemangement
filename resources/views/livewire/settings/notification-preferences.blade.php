<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Notifications')" :subheading="__('Choose which notifications you receive in-app')">
        <form wire:submit="save" class="my-6 w-full space-y-3">

            @foreach($types as $type)
                <label class="flex cursor-pointer items-center justify-between rounded-lg border border-zinc-200 px-4 py-3 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $type->label() }}</span>
                    <div class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center">
                        <input type="checkbox"
                               wire:model="preferences.{{ $type->value }}"
                               class="sr-only peer" />
                        <div class="peer h-5 w-9 rounded-full bg-zinc-200 peer-checked:bg-indigo-600 dark:bg-zinc-700 dark:peer-checked:bg-indigo-500 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-4 after:shadow-sm"></div>
                    </div>
                </label>
            @endforeach

            <div class="pt-2">
                <flux:button type="submit" variant="primary">{{ __('Save preferences') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
