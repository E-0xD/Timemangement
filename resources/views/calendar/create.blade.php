<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6">

        <x-page-header
            title="Add Calendar Event"
            subtitle="Schedule a new event on your calendar"
            :back="route('calendar.index')"
        />

        <div class="max-w-2xl">
            <form
                method="POST"
                action="{{ route('calendar.store') }}"
                class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
            >
                @csrf

                @include('calendar.partials.form-fields')

                <div class="flex items-center justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <a href="{{ route('calendar.index') }}"
                       class="text-sm font-medium text-zinc-600 transition-colors hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
                       wire:navigate>
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                    >
                        <span class="material-symbols-outlined text-sm leading-none">check</span>
                        Save Event
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts::app>
