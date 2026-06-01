<x-layouts::app :title="__('Timetable')">
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        <x-page-header
            :title="__('Weekly Timetable')"
            :subtitle="$totalCount . ' ' . ($totalCount === 1 ? 'entry' : 'entries')"
        >
            <a href="{{ route('timetable.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <span class="material-symbols-outlined" style="font-size:18px">add</span>
                Add Entry
            </a>
        </x-page-header>

        {{-- Flash --}}
        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 px-4 py-3 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">
                <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($totalCount === 0)

            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <span class="material-symbols-outlined text-zinc-300 dark:text-zinc-600" style="font-size:64px">calendar_today</span>
                <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-zinc-100">No classes scheduled</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Build your weekly timetable to keep track of all your classes.</p>
                <a href="{{ route('timetable.create') }}" class="mt-5 inline-flex items-center gap-1.5 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <span class="material-symbols-outlined" style="font-size:18px">add</span>
                    Add First Entry
                </a>
            </div>

        @else

            @php
                $gridStart  = 7 * 60;   // 07:00 in minutes
                $gridEnd    = 22 * 60;   // 22:00 in minutes
                $hourPx     = 80;        // px per hour
                $totalPx    = ($gridEnd - $gridStart) / 60 * $hourPx; // 960px
                $hours      = range(7, 21);
                $allDays    = \App\Enums\DayOfWeek::cases();
            @endphp

            {{-- Weekend toggle + legend --}}
            <div
                x-data="{ showWeekend: @json($hasWeekend) }"
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden"
            >
                {{-- Toolbar --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-zinc-400">
                        <span class="material-symbols-outlined" style="font-size:16px">info</span>
                        Click any entry to edit it
                    </div>
                    <button
                        type="button"
                        @click="showWeekend = !showWeekend"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border border-zinc-200 dark:border-zinc-700 text-slate-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
                    >
                        <span class="material-symbols-outlined" style="font-size:14px">weekend</span>
                        <span x-text="showWeekend ? 'Hide weekends' : 'Show weekends'"></span>
                    </button>
                </div>

                {{-- Grid --}}
                <div class="overflow-x-auto">
                    <div class="min-w-[520px]">

                        {{-- Day header row --}}
                        <div class="flex border-b border-zinc-200 dark:border-zinc-700">
                            {{-- Time gutter --}}
                            <div class="flex-shrink-0 w-14"></div>
                            {{-- Day labels --}}
                            @foreach ($allDays as $day)
                                <div
                                    class="flex-1 py-2.5 text-center text-xs font-semibold tracking-wide uppercase {{ $day->isWeekend() ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-500 dark:text-zinc-400' }}"
                                    @if($day->isWeekend()) x-show="showWeekend" style="display: none" @endif
                                >
                                    {{ $day->short() }}
                                </div>
                            @endforeach
                        </div>

                        {{-- Time grid body --}}
                        <div class="flex" style="height: {{ $totalPx }}px">

                            {{-- Time labels --}}
                            <div class="flex-shrink-0 w-14 relative select-none">
                                @foreach ($hours as $hour)
                                    <div
                                        class="absolute right-2 text-[10px] font-medium text-slate-400 dark:text-zinc-500 leading-none"
                                        style="top: {{ ($hour - 7) * $hourPx - 7 }}px"
                                    >
                                        {{ sprintf('%02d:00', $hour) }}
                                    </div>
                                @endforeach
                            </div>

                            {{-- Day columns --}}
                            @foreach ($allDays as $day)
                                <div
                                    class="flex-1 relative border-l border-zinc-100 dark:border-zinc-800"
                                    @if($day->isWeekend()) x-show="showWeekend" style="display: none" @endif
                                >
                                    {{-- Hour lines --}}
                                    @foreach ($hours as $hour)
                                        <div
                                            class="absolute w-full pointer-events-none"
                                            style="top: {{ ($hour - 7) * $hourPx }}px; border-top: 1px solid {{ $hour % 2 === 0 ? 'rgba(148,163,184,0.15)' : 'rgba(148,163,184,0.07)' }}"
                                        ></div>
                                    @endforeach

                                    {{-- Timetable entries --}}
                                    @foreach ($entries->get($day->value, collect()) as $entry)
                                        @php
                                            $sParts   = explode(':', $entry->start_time);
                                            $eParts   = explode(':', $entry->end_time);
                                            $startMin = (int)$sParts[0] * 60 + (int)$sParts[1];
                                            $endMin   = (int)$eParts[0] * 60 + (int)$eParts[1];
                                            $topPx    = ($startMin - $gridStart) / 60 * $hourPx;
                                            $heightPx = max(($endMin - $startMin) / 60 * $hourPx, 28);
                                        @endphp
                                        <a
                                            href="{{ route('timetable.edit', $entry) }}"
                                            class="absolute inset-x-0.5 rounded-md px-2 py-1 overflow-hidden text-white text-[11px] font-medium shadow-sm hover:brightness-110 hover:shadow-md transition-all z-10 group"
                                            style="top: {{ $topPx }}px; height: {{ $heightPx }}px; background-color: {{ $entry->color }};"
                                            title="{{ $entry->title }} ({{ substr($entry->start_time, 0, 5) }}–{{ substr($entry->end_time, 0, 5) }})"
                                        >
                                            <div class="font-semibold truncate leading-tight">{{ $entry->title }}</div>
                                            @if ($heightPx >= 32)
                                                <div class="opacity-80 text-[10px] leading-tight mt-0.5">
                                                    {{ substr($entry->start_time, 0, 5) }}–{{ substr($entry->end_time, 0, 5) }}
                                                </div>
                                            @endif
                                            @if ($heightPx >= 52 && $entry->location)
                                                <div class="opacity-70 text-[10px] truncate leading-tight">
                                                    <span class="material-symbols-outlined" style="font-size:10px;vertical-align:-1px">location_on</span>
                                                    {{ $entry->location }}
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- List view (all entries grouped by day) --}}
            <div class="mt-6">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-zinc-100 mb-3">All Entries by Day</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($allDays as $day)
                        @php $dayEntries = $entries->get($day->value, collect()); @endphp
                        @if ($dayEntries->isNotEmpty())
                            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                                <div class="px-4 py-2.5 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">{{ $day->label() }}</span>
                                </div>
                                <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                    @foreach ($dayEntries as $entry)
                                        <li>
                                            <a href="{{ route('timetable.edit', $entry) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                                <div class="mt-0.5 w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $entry->color }}"></div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-slate-900 dark:text-zinc-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                                        {{ $entry->title }}
                                                    </p>
                                                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                                                        {{ substr($entry->start_time, 0, 5) }} – {{ substr($entry->end_time, 0, 5) }}
                                                        @if ($entry->location)
                                                            · {{ $entry->location }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <span class="material-symbols-outlined text-slate-300 dark:text-zinc-600 group-hover:text-indigo-400 transition-colors flex-shrink-0" style="font-size:16px">chevron_right</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        @endif

    </div>
</x-layouts::app>
