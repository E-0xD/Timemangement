<x-layouts::app :title="__('Edit Task')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

        <x-page-header
            :title="__('Edit Task')"
            :back="route('tasks.show', $task)"
        />

        {{-- Update form --}}
        <form id="update-task-form" method="POST" action="{{ route('tasks.update', $task) }}">
            @csrf
            @method('PUT')

            @include('tasks.partials.form-fields', [
                'task'         => $task,
                'courses'      => $courses,
                'subtasksData' => $subtasksData,
            ])
        </form>

        {{-- Delete form (separate to avoid nesting) --}}
        <form id="delete-task-form" method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Are you sure you want to delete this task?')">
            @csrf
            @method('DELETE')
        </form>

        {{-- Action bar --}}
        <div class="flex items-center justify-between mt-6">
            <button
                type="submit"
                form="delete-task-form"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors"
            >
                <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                Delete Task
            </button>

            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.show', $task) }}" class="px-5 py-2 text-sm font-medium text-slate-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    Cancel
                </a>
                <button
                    type="submit"
                    form="update-task-form"
                    class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors"
                >
                    Save Changes
                </button>
            </div>
        </div>

    </div>
</x-layouts::app>
