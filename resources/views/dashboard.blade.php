<x-layouts::app :title="__('Dashboard')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        {{-- Page header --}}
        <x-page-header
            :title="__('Dashboard')"
            :subtitle="now()->format('l, F j Y')"
        >
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined" style="font-size:18px">add</span>
                New Task
            </a>
        </x-page-header>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 px-4 py-3 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">
                <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats row --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <x-stat-card
                label="Pending"
                :value="$pendingCount"
                icon="pending_actions"
                color="indigo"
                :href="route('tasks.index', ['status' => 'pending'])"
            />
            <x-stat-card
                label="In Progress"
                :value="$inProgressCount"
                icon="autorenew"
                color="blue"
                :href="route('tasks.index', ['status' => 'in_progress'])"
            />
            <x-stat-card
                label="Due Today"
                :value="$dueTodayCount"
                icon="today"
                color="amber"
            />
            <x-stat-card
                label="Overdue"
                :value="$overdueCount"
                icon="warning"
                color="red"
            />
        </div>

        {{-- Main content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Upcoming tasks --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-zinc-100">Upcoming Tasks</h2>
                        <a href="{{ route('tasks.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                            View all
                        </a>
                    </div>

                    @if ($upcomingTasks->isEmpty())
                        <div class="flex flex-col items-center justify-center py-14 text-center">
                            <span class="material-symbols-outlined text-zinc-300 dark:text-zinc-600" style="font-size:48px">task_alt</span>
                            <p class="mt-3 text-sm font-medium text-slate-500 dark:text-zinc-400">No upcoming tasks</p>
                            <a href="{{ route('tasks.create') }}" class="mt-3 text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Create your first task</a>
                        </div>
                    @else
                        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($upcomingTasks as $task)
                                @php
                                    $isOverdue  = $task->due_date && $task->due_date->isPast() && !$task->due_date->isToday();
                                    $isToday    = $task->due_date && $task->due_date->isToday();
                                    $isTomorrow = $task->due_date && $task->due_date->isTomorrow();

                                    $dateLabel = match(true) {
                                        $isToday    => 'Today',
                                        $isTomorrow => 'Tomorrow',
                                        $isOverdue  => 'Overdue',
                                        default     => $task->due_date->format('M j'),
                                    };
                                    $dateClass = match(true) {
                                        $isOverdue  => 'text-red-600 dark:text-red-400',
                                        $isToday    => 'text-amber-600 dark:text-amber-400',
                                        default     => 'text-slate-400 dark:text-zinc-500',
                                    };
                                @endphp
                                <li class="flex items-center gap-3 px-5 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    {{-- Priority dot --}}
                                    @php
                                        $dotColors = ['urgent' => 'bg-red-500', 'high' => 'bg-orange-400', 'medium' => 'bg-yellow-400', 'low' => 'bg-green-400'];
                                        $dotColor  = $dotColors[$task->priority->value] ?? 'bg-zinc-300';
                                    @endphp
                                    <span class="shrink-0 w-2 h-2 rounded-full {{ $dotColor }}"></span>

                                    {{-- Title --}}
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium text-slate-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 truncate block transition-colors">
                                            {{ $task->title }}
                                        </a>
                                        @if ($task->course)
                                            <span class="text-xs text-slate-400 dark:text-zinc-500">{{ $task->course->name }}</span>
                                        @endif
                                    </div>

                                    {{-- Badges --}}
                                    <div class="flex items-center gap-2 shrink-0">
                                        <x-task-badge :value="$task->status" />
                                        <span class="text-xs font-medium {{ $dateClass }}">{{ $dateLabel }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Right sidebar --}}
            <div class="flex flex-col gap-5">

                {{-- Study streak --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-indigo-500 dark:text-indigo-400" style="font-size:20px">local_fire_department</span>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-zinc-100">Study Streak</h3>
                    </div>
                    <p class="text-5xl font-bold text-indigo-600 dark:text-indigo-400">{{ auth()->user()->study_streak ?? 0 }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-zinc-400">consecutive days</p>
                </div>

                {{-- Recently completed --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-zinc-100">Recently Completed</h3>
                    </div>
                    @if ($recentlyCompleted->isEmpty())
                        <p class="px-5 py-6 text-xs text-slate-400 dark:text-zinc-500 text-center">No completed tasks yet.</p>
                    @else
                        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach ($recentlyCompleted as $task)
                                <li class="flex items-center gap-2.5 px-5 py-3">
                                    <span class="material-symbols-outlined text-green-500 dark:text-green-400 shrink-0" style="font-size:16px">check_circle</span>
                                    <a href="{{ route('tasks.show', $task) }}" class="text-sm text-slate-600 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 truncate transition-colors">
                                        {{ $task->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Quick links --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-zinc-100 mb-3">Quick Actions</h3>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">add_task</span>
                            New task
                        </a>
                        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">autorenew</span>
                            In-progress tasks
                        </a>
                        <a href="{{ route('tasks.index', ['priority' => 'urgent']) }}" class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">priority_high</span>
                            Urgent tasks
                        </a>
                        <a href="{{ route('profile.edit') }}" wire:navigate class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-zinc-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px">manage_accounts</span>
                            Edit profile
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-layouts::app>
