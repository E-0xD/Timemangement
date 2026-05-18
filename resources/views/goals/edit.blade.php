<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('goals.index') }}" wire:navigate
               class="inline-flex h-8 w-8 items-center justify-center rounded-md text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                <span class="material-symbols-outlined text-xl leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Edit Goal</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $goal->title }}</p>
            </div>
        </div>

        <div class="mx-auto w-full max-w-2xl space-y-4">

            {{-- Edit form --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('goals.update', $goal) }}" id="update-goal-form">
                    @csrf @method('PUT')

                    @include('goals.partials.form-fields', ['model' => $goal])

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                        <a href="{{ route('goals.index') }}" wire:navigate
                           class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Cancel
                        </a>
                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger zone --}}
            <div class="rounded-xl border border-red-200 bg-white p-5 shadow-sm dark:border-red-900 dark:bg-zinc-900">
                <h3 class="mb-1 text-sm font-semibold text-red-700 dark:text-red-400">Delete Goal</h3>
                <p class="mb-4 text-xs text-zinc-500 dark:text-zinc-400">This action cannot be undone.</p>
                <form method="POST" action="{{ route('goals.destroy', $goal) }}" id="delete-goal-form">
                    @csrf @method('DELETE')
                    <button type="submit" form="delete-goal-form"
                            onclick="return confirm('Delete this goal? This cannot be undone.')"
                            class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-950/30">
                        Delete Goal
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>
