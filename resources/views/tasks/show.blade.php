<x-layouts::app :title="$task->title">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">

        @php
            $isOverdue = $task->due_date
                && $task->due_date->isPast()
                && !$task->due_date->isToday()
                && $task->status !== \App\Enums\TaskStatus::Completed
                && $task->status !== \App\Enums\TaskStatus::Cancelled;
        @endphp

        <x-page-header :title="$task->title" :back="route('tasks.index')">
            <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium
                    {{ $task->status === \App\Enums\TaskStatus::Completed
                        ? 'text-slate-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700'
                        : 'text-white bg-green-600 hover:bg-green-700'
                    }} rounded-lg transition-colors">
                    <span class="material-symbols-outlined" style="font-size:16px">
                        {{ $task->status === \App\Enums\TaskStatus::Completed ? 'undo' : 'check' }}
                    </span>
                    {{ $task->status === \App\Enums\TaskStatus::Completed ? 'Reopen' : 'Mark Complete' }}
                </button>
            </form>
            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined" style="font-size:16px">edit</span>
                Edit
            </a>
        </x-page-header>

        {{-- Flash --}}
        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">
                <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left: description + subtasks --}}
            <div class="lg:col-span-7 flex flex-col gap-5">

                {{-- Description --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-zinc-100 mb-3">Description</h2>
                    @if ($task->description)
                        <p class="text-sm text-slate-600 dark:text-zinc-300 whitespace-pre-wrap leading-relaxed">{{ $task->description }}</p>
                    @else
                        <p class="text-sm text-slate-400 dark:text-zinc-500 italic">No description added.</p>
                    @endif
                </div>

                {{-- Subtasks --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-zinc-100">
                            Subtasks
                            @if ($task->subtasks->isNotEmpty())
                                <span class="ml-1.5 text-xs font-normal text-slate-400 dark:text-zinc-500">
                                    {{ $task->subtasks->where('is_completed', true)->count() }} / {{ $task->subtasks->count() }}
                                </span>
                            @endif
                        </h2>
                        <a href="{{ route('tasks.edit', $task) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                            Manage
                        </a>
                    </div>

                    @if ($task->subtasks->isEmpty())
                        <p class="px-5 py-6 text-sm text-slate-400 dark:text-zinc-500 text-center">No subtasks.</p>
                    @else
                        {{-- Completion bar --}}
                        @php
                            $completedRatio = $task->subtasks->count() > 0
                                ? ($task->subtasks->where('is_completed', true)->count() / $task->subtasks->count()) * 100
                                : 0;
                        @endphp
                        <div class="px-5 pt-3 pb-1">
                            <div class="h-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" style="width: {{ $completedRatio }}%"></div>
                            </div>
                        </div>

                        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($task->subtasks as $subtask)
                                <li class="flex items-center gap-3 px-5 py-3">
                                    <form method="POST" action="{{ route('tasks.subtasks.toggle', [$task, $subtask]) }}">
                                        @csrf
                                        <button type="submit" class="text-slate-300 dark:text-zinc-600 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors shrink-0">
                                            <span class="material-symbols-outlined" style="font-size:20px">
                                                {{ $subtask->is_completed ? 'check_circle' : 'radio_button_unchecked' }}
                                            </span>
                                        </button>
                                    </form>
                                    <span class="text-sm {{ $subtask->is_completed ? 'line-through text-slate-400 dark:text-zinc-500' : 'text-slate-700 dark:text-zinc-300' }}">
                                        {{ $subtask->title }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

            {{-- Right: task metadata --}}
            <div class="lg:col-span-5">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 flex flex-col gap-4">

                    {{-- Status --}}
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Status</span>
                        <x-task-badge :value="$task->status" />
                    </div>

                    {{-- Priority --}}
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Priority</span>
                        <x-task-badge :value="$task->priority" />
                    </div>

                    {{-- Category --}}
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Category</span>
                        <x-task-badge :value="$task->category" />
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-700">

                    {{-- Course --}}
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide shrink-0">Course</span>
                        <span class="text-sm text-slate-700 dark:text-zinc-300 text-right">
                            {{ $task->course?->name ?? '—' }}
                        </span>
                    </div>

                    {{-- Due date --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Due Date</span>
                        @if ($task->due_date)
                            <span class="text-sm font-medium {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-slate-700 dark:text-zinc-300' }}">
                                {{ $task->due_date->format('M j, Y') }}
                                @if ($task->due_time)
                                    <span class="text-slate-400 dark:text-zinc-500 font-normal"> at {{ \Carbon\Carbon::parse($task->due_time)->format('g:i A') }}</span>
                                @endif
                            </span>
                        @else
                            <span class="text-sm text-slate-400 dark:text-zinc-500">No due date</span>
                        @endif
                    </div>

                    @if ($task->due_date)
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Time Left</span>
                            <span class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : 'text-slate-600 dark:text-zinc-300' }}">
                                {{ $task->due_date->diffForHumans() }}
                            </span>
                        </div>
                    @endif

                    {{-- Recurring --}}
                    @if ($task->is_recurring)
                        <div class="flex items-center gap-1.5 text-slate-500 dark:text-zinc-400 text-xs">
                            <span class="material-symbols-outlined" style="font-size:14px">repeat</span>
                            Recurring task
                        </div>
                    @endif

                    <hr class="border-zinc-200 dark:border-zinc-700">

                    {{-- Timestamps --}}
                    <div class="space-y-1.5 text-xs text-slate-400 dark:text-zinc-500">
                        <p>Created {{ $task->created_at->diffForHumans() }}</p>
                        @if ($task->completed_at)
                            <p>Completed {{ $task->completed_at->diffForHumans() }}</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>

    </div>
</x-layouts::app>
