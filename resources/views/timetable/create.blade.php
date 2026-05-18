<x-layouts::app :title="__('Add Timetable Entry')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">

        <x-page-header
            :title="__('Add Timetable Entry')"
            :back="route('timetable.index')"
        />

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6">
            <form method="POST" action="{{ route('timetable.store') }}">
                @csrf

                @include('timetable.partials.form-fields', ['courses' => $courses])

                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-zinc-100 dark:border-zinc-800">
                    <a href="{{ route('timetable.index') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                        Add Entry
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts::app>
