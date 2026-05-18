<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6">

        <x-page-header
            title="Edit Event"
            subtitle="{{ $calendar->title }}"
            :back="route('calendar.index')"
        >
            <button
                type="submit"
                form="update-event-form"
                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
            >
                <span class="material-symbols-outlined text-sm leading-none">check</span>
                Save Changes
            </button>
            <button
                type="submit"
                form="delete-event-form"
                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-100 dark:border-red-800 dark:bg-red-950 dark:text-red-300 dark:hover:bg-red-900"
                onclick="return confirm('Delete this event? This cannot be undone.')"
            >
                <span class="material-symbols-outlined text-sm leading-none">delete</span>
                Delete
            </button>
        </x-page-header>

        <div class="flex flex-col gap-4 lg:flex-row lg:items-start">

            {{-- Update form --}}
            <div class="flex-1">
                <form
                    id="update-event-form"
                    method="POST"
                    action="{{ route('calendar.update', $calendar) }}"
                    class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
                >
                    @csrf
                    @method('PUT')

                    @include('calendar.partials.form-fields', ['event' => $calendar])

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
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Metadata sidebar --}}
            <div class="w-full lg:w-64 shrink-0">
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 space-y-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Event Info</h3>

                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full shrink-0" style="background-color: {{ $calendar->color ?: $calendar->type->color() }};"></span>
                        <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $calendar->type->label() }}</span>
                    </div>

                    @if($calendar->course)
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="font-medium text-zinc-700 dark:text-zinc-300">Course:</span>
                            {{ $calendar->course->name }}
                        </div>
                    @endif

                    <div class="text-xs text-zinc-500 dark:text-zinc-500 space-y-1 pt-1 border-t border-zinc-100 dark:border-zinc-800">
                        <div>Created {{ $calendar->created_at->diffForHumans() }}</div>
                        @if($calendar->updated_at->ne($calendar->created_at))
                            <div>Updated {{ $calendar->updated_at->diffForHumans() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete form (hidden) --}}
        <form
            id="delete-event-form"
            method="POST"
            action="{{ route('calendar.destroy', $calendar) }}"
            class="hidden"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>
</x-layouts::app>
