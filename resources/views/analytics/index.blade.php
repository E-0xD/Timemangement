<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Analytics</h1>
            <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Your productivity overview</p>
        </div>

        {{-- ── Summary cards ──────────────────────────────────────────────── --}}
        @php
            $totalHours   = floor($totalMinutes / 60);
            $totalMinsRem = $totalMinutes % 60;
            $weekHours    = floor($weekMinutes / 60);
            $weekMinsRem  = $weekMinutes % 60;
        @endphp
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total study time --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">All-time study</span>
                    <span class="material-symbols-outlined text-xl text-indigo-500">schedule</span>
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    {{ $totalHours }}<span class="text-base font-medium">h</span>
                    @if($totalMinsRem > 0)<span class="ml-0.5">{{ $totalMinsRem }}<span class="text-base font-medium">m</span></span>@endif
                </p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $totalSessions }} sessions total</p>
            </div>
            {{-- This week --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">This week</span>
                    <span class="material-symbols-outlined text-xl text-emerald-500">date_range</span>
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    {{ $weekHours }}<span class="text-base font-medium">h</span>
                    @if($weekMinsRem > 0)<span class="ml-0.5">{{ $weekMinsRem }}<span class="text-base font-medium">m</span></span>@endif
                </p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $weekSessions }} sessions this week</p>
            </div>
            {{-- Study streak --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Study streak</span>
                    <span class="material-symbols-outlined text-xl text-amber-500">local_fire_department</span>
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    {{ $streak }}<span class="text-base font-medium ml-0.5">days</span>
                </p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Keep it going!</p>
            </div>
            {{-- Tasks completed --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Completed (month)</span>
                    <span class="material-symbols-outlined text-xl text-blue-500">task_alt</span>
                </div>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $completedThisMonth }}</p>
                <p class="mt-1 text-xs {{ $overdue > 0 ? 'text-red-500' : 'text-zinc-500 dark:text-zinc-400' }}">
                    {{ $overdue > 0 ? $overdue.' overdue' : 'No overdue tasks' }}
                </p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">

            {{-- ── Study time bar chart ──────────────────────────────────── --}}
            <div class="lg:col-span-2 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Study Time — Last 14 Days</h2>
                <div class="flex h-36 items-end gap-1">
                    @foreach($last14 as $day)
                        @php
                            $pct     = max(($day['minutes'] / $maxMinutes) * 100, $day['minutes'] > 0 ? 4 : 0);
                            $h       = floor($day['minutes'] / 60);
                            $m       = $day['minutes'] % 60;
                            $tooltip = $day['minutes'] > 0 ? "{$h}h {$m}m" : '0m';
                        @endphp
                        <div class="group relative flex flex-1 flex-col items-center gap-1">
                            {{-- Tooltip --}}
                            <div class="pointer-events-none absolute bottom-full mb-1 hidden rounded bg-zinc-900 px-1.5 py-0.5 text-[10px] text-white group-hover:block dark:bg-zinc-100 dark:text-zinc-900">
                                {{ $tooltip }}
                            </div>
                            <div
                                class="{{ $day['minutes'] > 0 ? 'bg-indigo-500 dark:bg-indigo-600' : 'bg-zinc-100 dark:bg-zinc-800' }} w-full rounded-t transition-all hover:opacity-80"
                                style="height: {{ $pct }}%"
                            ></div>
                            <span class="text-[9px] text-zinc-400 dark:text-zinc-600 rotate-45 origin-left translate-x-1">
                                {{ explode(' ', $day['label'])[1] }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex items-center gap-4 border-t border-zinc-100 pt-3 dark:border-zinc-800">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Study minutes</span>
                    </div>
                    <span class="text-xs text-zinc-400 dark:text-zinc-600">Max: {{ floor($maxMinutes / 60) }}h {{ $maxMinutes % 60 }}m</span>
                </div>
            </div>

            {{-- ── Task breakdown ────────────────────────────────────────── --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Tasks by Status</h2>
                @php
                    $statusColours = [
                        'pending'     => ['bg' => 'bg-zinc-400',   'label' => 'Pending'],
                        'in_progress' => ['bg' => 'bg-blue-500',   'label' => 'In Progress'],
                        'completed'   => ['bg' => 'bg-emerald-500','label' => 'Completed'],
                        'cancelled'   => ['bg' => 'bg-red-400',    'label' => 'Cancelled'],
                    ];
                @endphp
                @if($taskTotal === 0)
                    <div class="flex flex-col items-center justify-center py-8">
                        <span class="material-symbols-outlined mb-2 text-3xl text-zinc-300 dark:text-zinc-700">check_box</span>
                        <p class="text-sm text-zinc-400 dark:text-zinc-600">No tasks yet</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($statusColours as $value => $meta)
                            @php $count = (int) ($taskRows[$value] ?? 0); $pct = $taskTotal > 0 ? round($count / $taskTotal * 100) : 0; @endphp
                            <div>
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $meta['label'] }}</span>
                                    <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ $count }} <span class="font-normal text-zinc-400">({{ $pct }}%)</span></span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <div class="{{ $meta['bg'] }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                        <p class="pt-1 text-xs text-zinc-400 dark:text-zinc-600">{{ $taskTotal }} tasks total</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Top courses ─────────────────────────────────────────────────── --}}
        @if($topCourses->isNotEmpty())
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Top Courses by Study Time <span class="font-normal text-zinc-400">(last 30 days)</span></h2>
                <div class="space-y-3">
                    @foreach($topCourses as $row)
                        @php
                            $pct = max(round($row->total_minutes / $maxCourseMins * 100), 2);
                            $h   = floor($row->total_minutes / 60);
                            $m   = $row->total_minutes % 60;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-36 shrink-0 truncate text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $row->course?->name ?? 'Unknown' }}
                            </div>
                            <div class="flex-1 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800" style="height: 10px;">
                                <div class="h-full rounded-full bg-indigo-500 transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-16 shrink-0 text-right text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $h > 0 ? $h.'h ' : '' }}{{ $m }}m
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-layouts::app>
