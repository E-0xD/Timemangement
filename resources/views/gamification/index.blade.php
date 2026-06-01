<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Achievements</h1>
            <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Your XP, streak, and badges</p>
        </div>

        {{-- ── XP + Streak cards ───────────────────────────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-3">

            {{-- Level + XP --}}
            <div class="sm:col-span-2 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Level {{ $level }}</p>
                        <p class="mt-0.5 text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                            {{ number_format($user->xp_points) }} <span class="text-base font-medium text-zinc-500">XP</span>
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-950/50">
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $level }}</span>
                    </div>
                </div>
                <div class="mb-1 flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
                    <span>{{ $xpInLevel }} XP</span>
                    <span>{{ $xpToNext }} XP to Level {{ $level + 1 }}</span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <div class="h-full rounded-full bg-indigo-500 transition-all"
                         style="width: {{ round($xpInLevel / 500 * 100) }}%"></div>
                </div>
            </div>

            {{-- Study streak --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Study Streak</p>
                <p class="mt-1 text-3xl font-bold text-amber-500">{{ $user->study_streak ?? 0 }}</p>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">days in a row</p>
                <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-600">
                    Last studied: {{ $user->last_study_date?->diffForHumans() ?? 'Never' }}
                </p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- ── Earned achievements ─────────────────────────────────────── --}}
            <div class="lg:col-span-2">
                <h2 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Earned Badges
                    <span class="ml-1.5 rounded-full bg-indigo-100 px-2 py-0.5 text-xs text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-400">{{ $earned->count() }}</span>
                </h2>

                @if($earned->isEmpty())
                    <div class="flex flex-col items-center justify-center rounded-xl border border-zinc-200 bg-white py-12 dark:border-zinc-700 dark:bg-zinc-900">
                        <span class="material-symbols-outlined mb-2 text-4xl text-zinc-300 dark:text-zinc-700">military_tech</span>
                        <p class="text-sm text-zinc-400 dark:text-zinc-600">No badges yet — keep studying!</p>
                    </div>
                @else
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($earned as $achievement)
                            <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                                     style="background-color: {{ $achievement->badge_color }}20; border: 2px solid {{ $achievement->badge_color }};">
                                    <span class="material-symbols-outlined text-xl leading-none" style="color: {{ $achievement->badge_color }}">
                                        {{ $achievement->icon ?? 'military_tech' }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $achievement->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $achievement->description }}</p>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:bg-amber-950/40 dark:text-amber-400">
                                            +{{ $achievement->xp_value }} XP
                                        </span>
                                        @if($achievement->earned_at)
                                            <span class="text-[10px] text-zinc-400 dark:text-zinc-600">{{ $achievement->earned_at->format('M j, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Locked achievements --}}
                @if($locked->isNotEmpty())
                    <h2 class="mb-3 mt-6 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Locked</h2>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($locked as $achievement)
                            <div class="flex items-start gap-3 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 opacity-60">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-zinc-300 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
                                    <span class="material-symbols-outlined text-xl leading-none text-zinc-400 dark:text-zinc-500">lock</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">{{ $achievement->name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $achievement->description }}</p>
                                    <span class="mt-1 inline-block rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                        +{{ $achievement->xp_value }} XP
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Leaderboard ─────────────────────────────────────────────── --}}
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 h-fit">
                <div class="border-b border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Leaderboard</h2>
                </div>
                @if($leaderboard->isEmpty())
                    <div class="px-5 py-8 text-center text-sm text-zinc-400 dark:text-zinc-600">No XP earned yet</div>
                @else
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($leaderboard as $i => $entry)
                            <div class="flex items-center gap-3 px-5 py-3 {{ $entry->id === Auth::id() ? 'bg-indigo-50/50 dark:bg-indigo-950/20' : '' }}">
                                <span class="w-5 shrink-0 text-center text-xs font-bold
                                    {{ $i === 0 ? 'text-amber-500' : ($i === 1 ? 'text-zinc-400' : ($i === 2 ? 'text-amber-700' : 'text-zinc-400')) }}">
                                    {{ $i + 1 }}
                                </span>
                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-400">
                                    {{ strtoupper(substr($entry->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                        {{ $entry->name }}
                                        @if($entry->id === Auth::id()) <span class="text-xs text-indigo-500">(you)</span> @endif
                                    </p>
                                </div>
                                <span class="shrink-0 text-xs font-semibold text-zinc-700 dark:text-zinc-300">{{ number_format($entry->xp_points) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-layouts::app>
