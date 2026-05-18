<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('notes.index') }}" wire:navigate
                   class="inline-flex h-8 w-8 items-center justify-center rounded-md text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <span class="material-symbols-outlined text-xl leading-none">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $note->title }}</h1>
                    <div class="mt-0.5 flex flex-wrap items-center gap-2 text-xs text-zinc-400 dark:text-zinc-600">
                        @if($note->course)
                            <span class="rounded-full border border-zinc-200 px-2 py-0.5 text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">{{ $note->course->name }}</span>
                        @endif
                        <span>Updated {{ $note->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('notes.edit', $note) }}" wire:navigate
               class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 px-3 py-1.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                <span class="material-symbols-outlined text-base leading-none">edit</span>
                Edit
            </a>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        {{-- Content --}}
        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                @if($note->content)
                    <div class="prose prose-zinc max-w-none text-sm leading-relaxed text-zinc-800 dark:prose-invert dark:text-zinc-200 whitespace-pre-wrap font-mono">{{ $note->content }}</div>
                @else
                    <p class="text-sm text-zinc-400 dark:text-zinc-600">This note has no content yet.</p>
                @endif
            </div>
        </div>

    </div>
</x-layouts::app>
