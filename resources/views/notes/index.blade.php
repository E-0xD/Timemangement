<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Notes</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Your study notes and summaries</p>
            </div>
            <a href="{{ route('notes.create') }}" wire:navigate
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <span class="material-symbols-outlined text-base leading-none">add</span>
                New Note
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('notes.index') }}" class="flex flex-wrap gap-3">
            <div class="flex flex-1 min-w-48 items-center gap-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 dark:border-zinc-600 dark:bg-zinc-800">
                <span class="material-symbols-outlined text-base text-zinc-400 leading-none">search</span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search notes…"
                       class="flex-1 bg-transparent text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none dark:text-zinc-100" />
            </div>
            <select name="course_id"
                    class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                <option value="">All courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                Filter
            </button>
            @if($search || $courseId)
                <a href="{{ route('notes.index') }}" wire:navigate
                   class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-500 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800">
                    Clear
                </a>
            @endif
        </form>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        {{-- Notes list --}}
        @if($notes->isEmpty())
            <div class="flex flex-1 flex-col items-center justify-center py-20">
                <span class="material-symbols-outlined mb-3 text-5xl text-zinc-300 dark:text-zinc-700">note</span>
                <p class="text-base font-medium text-zinc-500 dark:text-zinc-400">
                    {{ $search || $courseId ? 'No notes match your filters' : 'No notes yet' }}
                </p>
                @unless($search || $courseId)
                    <a href="{{ route('notes.create') }}" wire:navigate
                       class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-base leading-none">add</span>
                        New Note
                    </a>
                @endunless
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($notes as $note)
                    <a href="{{ route('notes.show', $note) }}" wire:navigate
                       class="flex flex-col rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition-colors hover:border-indigo-300 hover:bg-indigo-50/20 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-indigo-700">
                        <div class="mb-2 flex items-start justify-between gap-2">
                            <h3 class="line-clamp-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $note->title }}</h3>
                        </div>
                        @if($note->content)
                            <p class="mb-3 line-clamp-3 text-xs text-zinc-500 dark:text-zinc-400">{{ $note->excerpt(200) }}</p>
                        @endif
                        <div class="mt-auto flex flex-wrap items-center gap-2 border-t border-zinc-100 pt-3 dark:border-zinc-800">
                            @if($note->course)
                                <span class="inline-flex items-center rounded-full border border-zinc-200 px-2 py-0.5 text-[11px] font-medium text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">
                                    {{ $note->course->name }}
                                </span>
                            @endif
                            <span class="ml-auto text-[11px] text-zinc-400 dark:text-zinc-600">{{ $note->updated_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div>{{ $notes->links() }}</div>
        @endif

    </div>
</x-layouts::app>
