<x-layouts::app :title="__('New Task')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

        <x-page-header
            :title="__('New Task')"
            :back="route('tasks.index')"
        />

        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            @include('tasks.partials.form-fields', ['courses' => $courses])

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 mt-6">
                <a href="{{ route('tasks.index') }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                    Create Task
                </button>
            </div>
        </form>

    </div>
</x-layouts::app>
