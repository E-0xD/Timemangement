<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6">

        @php
            $cursor   = $start->copy();
            $today    = now()->format('Y-m-d');
            $prevDate = $date->copy()->subMonth();
            $nextDate = $date->copy()->addMonth();
        @endphp

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ $date->format('F Y') }}
                </h1>
                <div class="flex items-center gap-1">
                    <a href="{{ route('calendar.index', ['year' => $prevDate->year, 'month' => $prevDate->month]) }}"
                       class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-500 transition-colors hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700"
                       wire:navigate>
                        <span class="material-symbols-outlined text-lg leading-none">chevron_left</span>
                    </a>
                    <a href="{{ route('calendar.index', ['year' => now()->year, 'month' => now()->month]) }}"
                       class="rounded-md border border-zinc-200 px-2 py-0.5 text-xs font-medium text-zinc-600 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700"
                       wire:navigate>
                        Today
                    </a>
                    <a href="{{ route('calendar.index', ['year' => $nextDate->year, 'month' => $nextDate->month]) }}"
                       class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-500 transition-colors hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-700"
                       wire:navigate>
                        <span class="material-symbols-outlined text-lg leading-none">chevron_right</span>
                    </a>
                </div>
            </div>
            <a href="{{ route('calendar.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
               wire:navigate>
                <span class="material-symbols-outlined text-sm leading-none">add</span>
                Add Event
            </a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Calendar Grid --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">

            {{-- Day-of-week headers --}}
            <div class="grid grid-cols-7 border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                    <div class="py-2 text-center text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                        {{ $dayName }}
                    </div>
                @endforeach
            </div>

            {{-- Day cells --}}
            <div class="grid grid-cols-7 divide-x divide-y divide-zinc-100 dark:divide-zinc-800">
                @while($cursor <= $end)
                    @php
                        $key         = $cursor->format('Y-m-d');
                        $dayEvents   = $events->get($key, collect());
                        $isToday     = ($key === $today);
                        $isThisMonth = ((int) $cursor->month === $month);
                    @endphp
                    <div class="min-h-28 p-1.5 {{ $isThisMonth ? 'bg-white dark:bg-zinc-800' : 'bg-zinc-50 dark:bg-zinc-900' }}">
                        {{-- Day number --}}
                        <div class="mb-1 flex justify-end">
                            @if($isToday)
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs font-semibold text-white">
                                    {{ $cursor->day }}
                                </span>
                            @else
                                <span class="text-xs font-medium {{ $isThisMonth ? 'text-zinc-700 dark:text-zinc-300' : 'text-zinc-400 dark:text-zinc-600' }}">
                                    {{ $cursor->day }}
                                </span>
                            @endif
                        </div>

                        {{-- Events --}}
                        @foreach($dayEvents->take(3) as $evt)
                            <a href="{{ route('calendar.edit', $evt) }}"
                               class="mb-0.5 flex items-center gap-1 truncate rounded px-1 py-0.5 text-[11px] font-medium text-white transition-opacity hover:opacity-90"
                               style="background-color: {{ $evt->color ?: $evt->type->color() }};"
                               wire:navigate>
                                @if(!$evt->is_all_day)
                                    <span class="shrink-0 opacity-80">{{ $evt->start_datetime->format('H:i') }}</span>
                                @endif
                                <span class="truncate">{{ $evt->title }}</span>
                            </a>
                        @endforeach

                        @if($dayEvents->count() > 3)
                            <span class="text-[10px] text-zinc-400 dark:text-zinc-500">
                                +{{ $dayEvents->count() - 3 }} more
                            </span>
                        @endif
                    </div>
                    @php $cursor->addDay() @endphp
                @endwhile
            </div>
        </div>

        {{-- Legend --}}
        <div class="flex flex-wrap gap-4">
            @foreach(\App\Enums\EventType::cases() as $type)
                <div class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $type->color() }};"></span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $type->label() }}</span>
                </div>
            @endforeach
        </div>

        {{-- Empty state --}}
        @if($events->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-12 dark:border-zinc-700 dark:bg-zinc-900">
                <span class="material-symbols-outlined mb-3 text-4xl text-zinc-400 dark:text-zinc-600">calendar_month</span>
                <p class="mb-1 text-sm font-medium text-zinc-700 dark:text-zinc-300">No events this month</p>
                <p class="mb-4 text-xs text-zinc-500 dark:text-zinc-500">Add classes, exams, study sessions, and more.</p>
                <a href="{{ route('calendar.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                   wire:navigate>
                    <span class="material-symbols-outlined text-sm leading-none">add</span>
                    Add First Event
                </a>
            </div>
        @endif

    </div>
</x-layouts::app>
