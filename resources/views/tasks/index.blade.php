<x-layouts::app :title="__('Tasks')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        <x-page-header :title="__('Tasks')" :subtitle="$tasks->total() . ' task' . ($tasks->total() === 1 ? '' : 's') . ' total'">
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined" style="font-size:18px">add</span>
                New Task
            </a>
        </x-page-header>

        {{-- Flash --}}
        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">
                <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 mb-5">
            <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap items-end gap-3">

                {{-- Search --}}
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-slate-500 dark:text-zinc-400 mb-1">Search</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-zinc-500" style="font-size:16px">search</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search tasks..."
                            class="w-full pl-8 pr-3 py-1.5 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>

                {{-- Status --}}
                <div class="min-w-[130px]">
                    <label class="block text-xs font-medium text-slate-500 dark:text-zinc-400 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-1.5 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All statuses</option>
                        @foreach (\App\Enums\TaskStatus::cases() as $case)
                            <option value="{{ $case->value }}" @selected(request('status') === $case->value)>{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Priority --}}
                <div class="min-w-[120px]">
                    <label class="block text-xs font-medium text-slate-500 dark:text-zinc-400 mb-1">Priority</label>
                    <select name="priority" class="w-full px-3 py-1.5 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All priorities</option>
                        @foreach (\App\Enums\TaskPriority::cases() as $case)
                            <option value="{{ $case->value }}" @selected(request('priority') === $case->value)>{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-slate-500 dark:text-zinc-400 mb-1">Category</label>
                    <select name="category" class="w-full px-3 py-1.5 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All categories</option>
                        @foreach (\App\Enums\TaskCategory::cases() as $case)
                            <option value="{{ $case->value }}" @selected(request('category') === $case->value)>{{ $case->label() }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Course --}}
                @if ($courses->isNotEmpty())
                    <div class="min-w-[150px]">
                        <label class="block text-xs font-medium text-slate-500 dark:text-zinc-400 mb-1">Course</label>
                        <select name="course_id" class="w-full px-3 py-1.5 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All courses</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Filter
                    </button>
                    @if (request()->hasAny(['status', 'priority', 'category', 'course_id', 'search']))
                        <a href="{{ route('tasks.index') }}" class="px-4 py-1.5 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-300 text-sm font-medium rounded-lg transition-colors">
                            Clear
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- Task table --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                        <tr>
                            <th class="px-4 py-3 w-10"></th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Task</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide hidden sm:table-cell">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide hidden md:table-cell">Priority</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide hidden lg:table-cell">Due Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide hidden xl:table-cell">Course</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse ($tasks as $task)
                            @php
                                $isOverdue = $task->due_date
                                    && $task->due_date->isPast()
                                    && !$task->due_date->isToday()
                                    && $task->status !== \App\Enums\TaskStatus::Completed
                                    && $task->status !== \App\Enums\TaskStatus::Cancelled;
                                $isDone = $task->status === \App\Enums\TaskStatus::Completed;
                            @endphp
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 transition-colors">

                                {{-- Toggle complete --}}
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                                        @csrf
                                        <button type="submit" title="{{ $isDone ? 'Mark as pending' : 'Mark as complete' }}" class="text-slate-300 dark:text-zinc-600 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            <span class="material-symbols-outlined" style="font-size:20px">
                                                {{ $isDone ? 'check_circle' : 'radio_button_unchecked' }}
                                            </span>
                                        </button>
                                    </form>
                                </td>

                                {{-- Title + subtasks count --}}
                                <td class="px-4 py-3 max-w-xs">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium {{ $isDone ? 'line-through text-slate-400 dark:text-zinc-500' : 'text-slate-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400' }} transition-colors truncate block">
                                        {{ $task->title }}
                                    </a>
                                    @if ($task->subtasks_count > 0)
                                        <span class="text-xs text-slate-400 dark:text-zinc-500">{{ $task->subtasks_count }} subtask{{ $task->subtasks_count !== 1 ? 's' : '' }}</span>
                                    @endif
                                </td>

                                {{-- Category --}}
                                <td class="px-4 py-3 hidden sm:table-cell">
                                    <x-task-badge :value="$task->category" />
                                </td>

                                {{-- Priority --}}
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <x-task-badge :value="$task->priority" />
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    <x-task-badge :value="$task->status" />
                                </td>

                                {{-- Due date --}}
                                <td class="px-4 py-3 hidden lg:table-cell">
                                    @if ($task->due_date)
                                        <span class="text-sm {{ $isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : 'text-slate-500 dark:text-zinc-400' }}">
                                            {{ $task->due_date->format('M j, Y') }}
                                            @if ($isOverdue)
                                                <span class="block text-xs">Overdue</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-zinc-600 text-sm">—</span>
                                    @endif
                                </td>

                                {{-- Course --}}
                                <td class="px-4 py-3 hidden xl:table-cell">
                                    @if ($task->course)
                                        <span class="text-sm text-slate-500 dark:text-zinc-400 truncate block max-w-[140px]">{{ $task->course->name }}</span>
                                    @else
                                        <span class="text-slate-300 dark:text-zinc-600 text-sm">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('tasks.edit', $task) }}" title="Edit" class="p-1.5 text-slate-400 dark:text-zinc-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined" style="font-size:16px">edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="p-1.5 text-slate-400 dark:text-zinc-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition-colors">
                                                <span class="material-symbols-outlined" style="font-size:16px">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-16 text-center">
                                    <span class="material-symbols-outlined text-zinc-300 dark:text-zinc-600 block mx-auto" style="font-size:48px">task_alt</span>
                                    <p class="mt-3 text-sm font-medium text-slate-500 dark:text-zinc-400">
                                        {{ request()->hasAny(['status', 'priority', 'category', 'search']) ? 'No tasks match your filters.' : 'No tasks yet.' }}
                                    </p>
                                    @if (!request()->hasAny(['status', 'priority', 'category', 'search']))
                                        <a href="{{ route('tasks.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                            <span class="material-symbols-outlined" style="font-size:16px">add</span>
                                            Create your first task
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($tasks->hasPages())
                <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts::app>
