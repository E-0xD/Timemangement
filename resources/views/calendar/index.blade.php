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
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                    {{ $date->format('F Y') }}
                </h1>
                <div class="flex items-center gap-1">
                    <a href="{{ route('calendar.index', ['year' => $prevDate->year, 'month' => $prevDate->month]) }}"
                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 transition-colors hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-700"
                       wire:navigate>
                        <span class="material-symbols-outlined text-lg leading-none">chevron_left</span>
                    </a>
                    <a href="{{ route('calendar.index', ['year' => now()->year, 'month' => now()->month]) }}"
                       class="rounded-lg border border-zinc-200 px-3 py-1 text-xs font-semibold text-zinc-600 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-700"
                       wire:navigate>
                        Today
                    </a>
                    <a href="{{ route('calendar.index', ['year' => $nextDate->year, 'month' => $nextDate->month]) }}"
                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 transition-colors hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-700"
                       wire:navigate>
                        <span class="material-symbols-outlined text-lg leading-none">chevron_right</span>
                    </a>
                </div>
            </div>
            <a href="{{ route('calendar.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
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

        {{-- Calendar --}}
        <div class="overflow-hidden rounded-xl border-2 border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            {{-- Day-of-week headers --}}
            <div class="grid grid-cols-7 border-b-2 border-zinc-200 dark:border-zinc-700">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $i => $dayName)
                    <div class="py-3 text-center text-xs font-bold uppercase tracking-widest {{ $i >= 5 ? 'text-indigo-400 dark:text-indigo-500' : 'text-zinc-500 dark:text-zinc-400' }} {{ $i > 0 ? 'border-l border-zinc-200 dark:border-zinc-700' : '' }}">
                        {{ substr($dayName, 0, 3) }}
                    </div>
                @endforeach
            </div>

            {{-- Week rows --}}
            @php $weekRow = 0; @endphp
            @while($cursor <= $end)
                @php $isFirstRow = $weekRow === 0; @endphp
                <div class="grid grid-cols-7 {{ $isFirstRow ? '' : 'border-t-2 border-zinc-200 dark:border-zinc-700' }}">
                    @for ($col = 0; $col < 7; $col++)
                        @if($cursor <= $end)
                            @php
                                $key         = $cursor->format('Y-m-d');
                                $dayEvents   = $events->get($key, collect());
                                $isToday     = ($key === $today);
                                $isThisMonth = ((int) $cursor->month === $month);
                                $isWeekend   = $col >= 5;
                            @endphp
                            <div class="relative min-h-[140px] p-2 {{ $col > 0 ? 'border-l border-zinc-200 dark:border-zinc-700' : '' }} {{ $isThisMonth ? ($isWeekend ? 'bg-zinc-50/60 dark:bg-zinc-800/30' : 'bg-white dark:bg-zinc-900') : 'bg-zinc-50 dark:bg-zinc-950/60' }}">

                                {{-- Day number --}}
                                <div class="mb-1.5 flex items-center justify-end">
                                    @if($isToday)
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white shadow-sm">
                                            {{ $cursor->day }}
                                        </span>
                                    @else
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full text-sm font-semibold
                                            {{ $isThisMonth ? ($isWeekend ? 'text-indigo-400 dark:text-indigo-500' : 'text-zinc-800 dark:text-zinc-200') : 'text-zinc-300 dark:text-zinc-700' }}
                                            hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-default">
                                            {{ $cursor->day }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Events --}}
                                <div class="space-y-0.5">
                                    @foreach($dayEvents->take(3) as $evt)
                                        <a href="{{ route('calendar.edit', $evt) }}"
                                           class="flex items-center gap-1 truncate rounded-md px-1.5 py-0.5 text-[11px] font-semibold text-white shadow-sm transition-all hover:brightness-110 hover:shadow"
                                           style="background-color: {{ $evt->color ?: $evt->type->color() }};"
                                           wire:navigate>
                                            @if(!$evt->is_all_day)
                                                <span class="shrink-0 opacity-90 font-normal">{{ $evt->start_datetime->format('H:i') }}</span>
                                            @endif
                                            <span class="truncate">{{ $evt->title }}</span>
                                        </a>
                                    @endforeach

                                    @if($dayEvents->count() > 3)
                                        <span class="block pl-1 text-[10px] font-medium text-zinc-400 dark:text-zinc-500">
                                            +{{ $dayEvents->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @php $cursor->addDay() @endphp
                        @else
                            <div class="min-h-[140px] {{ $col > 0 ? 'border-l border-zinc-200 dark:border-zinc-700' : '' }} bg-zinc-50 dark:bg-zinc-950/60"></div>
                        @endif
                    @endfor
                </div>
                @php $weekRow++; @endphp
            @endwhile

        </div>

        {{-- Legend + empty state --}}
        <div class="flex flex-wrap items-center gap-4">
            @foreach(\App\Enums\EventType::cases() as $type)
                <div class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $type->color() }};"></span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $type->label() }}</span>
                </div>
            @endforeach
        </div>

        @if($events->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-10 dark:border-zinc-700 dark:bg-zinc-900/50">
                <span class="material-symbols-outlined mb-3 text-zinc-300 dark:text-zinc-600" style="font-size: 40px">calendar_month</span>
                <p class="mb-1 text-sm font-medium text-zinc-700 dark:text-zinc-300">No events this month</p>
                <p class="mb-4 text-xs text-zinc-500 dark:text-zinc-500">Add classes, exams, study sessions, and more.</p>
                <a href="{{ route('calendar.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700"
                   wire:navigate>
                    <span class="material-symbols-outlined text-sm leading-none">add</span>
                    Add Event
                </a>
            </div>
        @endif

    </div>
</x-layouts::app>
