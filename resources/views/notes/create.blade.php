<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('notes.index') }}" wire:navigate
               class="inline-flex h-8 w-8 items-center justify-center rounded-md text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                <span class="material-symbols-outlined text-xl leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">New Note</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Write your study notes</p>
            </div>
        </div>

        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('notes.store') }}">
                    @csrf

                    @include('notes.partials.form-fields')

                    <div class="mt-6 flex items-center justify-end gap-3 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                        <a href="{{ route('notes.index') }}" wire:navigate
                           class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Cancel
                        </a>
                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Save Note
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts::app>
