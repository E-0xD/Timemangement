<x-layouts::app :title="__('Edit Timetable Entry')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">

        <x-page-header
            :title="__('Edit Timetable Entry')"
            :back="route('timetable.index')"
        />

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6">

            {{-- Update form --}}
            <form id="update-entry-form" method="POST" action="{{ route('timetable.update', $timetable) }}">
                @csrf
                @method('PUT')

                @include('timetable.partials.form-fields', [
                    'entry'   => $timetable,
                    'courses' => $courses,
                ])
            </form>

            {{-- Delete form (separate to avoid nesting) --}}
            <form
                id="delete-entry-form"
                method="POST"
                action="{{ route('timetable.destroy', $timetable) }}"
                onsubmit="return confirm('Delete this timetable entry?')"
            >
                @csrf
                @method('DELETE')
            </form>

            {{-- Action bar --}}
            <div class="flex items-center justify-between mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-800">
                <button
                    type="submit"
                    form="delete-entry-form"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors"
                >
                    <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                    Delete Entry
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('timetable.index') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        form="update-entry-form"
                        class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors"
                    >
                        Save Changes
                    </button>
                </div>
            </div>

        </div>

        {{-- Entry metadata --}}
        <p class="mt-3 text-xs text-slate-400 dark:text-zinc-500 text-right">
            Created {{ $timetable->created_at->diffForHumans() }}
            @if ($timetable->updated_at->ne($timetable->created_at))
                · Updated {{ $timetable->updated_at->diffForHumans() }}
            @endif
        </p>

    </div>
</x-layouts::app>
