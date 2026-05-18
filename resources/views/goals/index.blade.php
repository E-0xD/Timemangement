<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Goals</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Track your academic progress targets</p>
            </div>
            <a href="{{ route('goals.create') }}" wire:navigate
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <span class="material-symbols-outlined text-base leading-none">add</span>
                New Goal
            </a>
        </div>

        {{-- Filter tabs --}}
        <div class="flex gap-1 border border-zinc-200 rounded-lg p-1 w-fit bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800/50">
            @foreach(['all' => 'All', 'active' => 'Active', 'completed' => 'Completed'] as $val => $label)
                <a href="{{ route('goals.index', ['filter' => $val]) }}" wire:navigate
                   class="rounded-md px-3 py-1.5 text-sm font-medium transition-colors
                          {{ $filter === $val
                              ? 'bg-white text-zinc-900 shadow-sm dark:bg-zinc-700 dark:text-zinc-100'
                              : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">
                {{ $value }}
            </div>
        @endsession

        {{-- Goals grid --}}
        @if($goals->isEmpty())
            <div class="flex flex-1 flex-col items-center justify-center py-20">
                <span class="material-symbols-outlined mb-3 text-5xl text-zinc-300 dark:text-zinc-700">flag</span>
                <p class="text-base font-medium text-zinc-500 dark:text-zinc-400">No goals yet</p>
                <p class="mt-1 text-sm text-zinc-400 dark:text-zinc-600">Set a target and track your progress</p>
                <a href="{{ route('goals.create') }}" wire:navigate
                   class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    <span class="material-symbols-outlined text-base leading-none">add</span>
                    New Goal
                </a>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($goals as $goal)
                    @php
                        $pct     = $goal->progressPercentage();
                        $expired = $goal->isExpired();
                        $unit    = $goal->category->unit();
                    @endphp
                    <div class="flex flex-col rounded-xl border {{ $goal->is_completed ? 'border-emerald-200 dark:border-emerald-800' : ($expired ? 'border-red-200 dark:border-red-900' : 'border-zinc-200 dark:border-zinc-700') }} bg-white p-5 shadow-sm dark:bg-zinc-900">

                        {{-- Top row --}}
                        <div class="mb-3 flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-medium
                                        {{ $goal->is_completed
                                            ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400'
                                            : ($expired
                                                ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400'
                                                : 'border-zinc-200 bg-zinc-50 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400') }}">
                                        {{ $goal->is_completed ? 'Completed' : ($expired ? 'Overdue' : $goal->period->label()) }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[11px] font-medium text-indigo-700 dark:border-indigo-800 dark:bg-indigo-950/40 dark:text-indigo-400">
                                        {{ $goal->category->label() }}
                                    </span>
                                </div>
                                <h3 class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $goal->title }}</h3>
                            </div>
                            <div class="flex shrink-0 gap-1">
                                <a href="{{ route('goals.edit', $goal) }}" wire:navigate
                                   class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800 dark:hover:text-zinc-300">
                                    <span class="material-symbols-outlined text-base leading-none">edit</span>
                                </a>
                            </div>
                        </div>

                        {{-- Progress --}}
                        <div class="mb-3">
                            <div class="mb-1 flex items-center justify-between">
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">Progress</span>
                                <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                    {{ number_format($goal->current_value, $goal->current_value == floor($goal->current_value) ? 0 : 1) }}
                                    / {{ number_format($goal->target_value, $goal->target_value == floor($goal->target_value) ? 0 : 1) }}
                                    {{ $unit }}
                                </span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <div class="h-full rounded-full transition-all
                                    {{ $goal->is_completed ? 'bg-emerald-500' : ($expired ? 'bg-red-400' : 'bg-indigo-500') }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="mt-0.5 text-right text-[11px] font-medium {{ $goal->is_completed ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400 dark:text-zinc-600' }}">{{ $pct }}%</div>
                        </div>

                        {{-- Quick update progress form --}}
                        @unless($goal->is_completed)
                            <form method="POST" action="{{ route('goals.progress', $goal) }}" class="mb-3 flex gap-2">
                                @csrf @method('PATCH')
                                <input type="number" name="current_value" step="0.01" min="0"
                                       value="{{ $goal->current_value }}"
                                       class="flex-1 rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                                <button type="submit"
                                        class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">
                                    Update
                                </button>
                            </form>
                        @endunless

                        {{-- Footer --}}
                        <div class="mt-auto flex items-center justify-between border-t border-zinc-100 pt-3 dark:border-zinc-800">
                            @if($goal->target_date)
                                <span class="flex items-center gap-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    <span class="material-symbols-outlined text-sm leading-none">event</span>
                                    {{ $goal->target_date->format('M j, Y') }}
                                </span>
                            @else
                                <span class="text-xs text-zinc-400 dark:text-zinc-600">No deadline</span>
                            @endif
                            @if($goal->is_completed)
                                <span class="flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                    <span class="material-symbols-outlined text-sm leading-none">check_circle</span>
                                    Done
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-layouts::app>
