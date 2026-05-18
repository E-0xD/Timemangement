<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('site.name', 'StudyFlow') }} — Study smarter, achieve more</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles
</head>
<body class="min-h-screen bg-indigo-50 text-slate-900 font-sans antialiased">

{{-- Navigation --}}
<nav class="sticky top-0 z-50 bg-white border border-slate-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <x-app-logo-icon class="size-7 text-indigo-600" />
            <span class="font-bold text-lg tracking-tight text-slate-900">{{ config('site.name', 'StudyFlow') }}</span>
        </div>
        <div class="hidden md:flex items-center gap-8 text-sm text-slate-500">
            <a href="#features"     class="hover:text-slate-900 transition-colors">Features</a>
            <a href="#how-it-works" class="hover:text-slate-900 transition-colors">How it works</a>
        </div>
        <div class="flex items-center gap-2.5">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-slate-500 hover:text-slate-900 text-sm font-medium transition-colors">
                    Sign in
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-colors">
                    Get started free
                </a>
            @endauth
        </div>
    </div>
</nav>

<main>

{{-- Hero --}}
<section class="bg-white py-20 md:py-28">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-medium">
            <span class="material-symbols-outlined" style="font-size:14px">school</span>
            Built for university students
        </div>
        <h1 class="text-5xl md:text-6xl font-bold tracking-tight leading-[1.1] mb-6 text-slate-900">
            Study smarter,<br>achieve more
        </h1>
        <p class="text-lg text-slate-500 leading-relaxed mb-8 max-w-xl mx-auto">
            The all-in-one study platform for task management, focus sessions,
            gamification, and team collaboration — beautifully organised.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="px-7 py-3.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-base transition-colors">
                Start for free
            </a>
            <a href="{{ route('login') }}"
               class="px-7 py-3.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-base transition-colors">
                Sign in
            </a>
        </div>
        <p class="mt-4 text-xs text-slate-400">No credit card required · Free forever for students</p>
    </div>
</section>

{{-- Dashboard preview --}}
<section class="bg-indigo-50 py-16">
    <div class="max-w-5xl mx-auto px-6">
        <p class="text-center text-xs text-slate-500 mb-8 font-medium uppercase tracking-wider">See it in action</p>
        <div class="border border-slate-200 rounded-lg overflow-hidden shadow-sm bg-white">
            {{-- Browser chrome --}}
            <div class="bg-slate-100 px-4 py-3 flex items-center gap-2 border border-slate-200">
                <div class="size-3 rounded-full bg-slate-300"></div>
                <div class="size-3 rounded-full bg-slate-300"></div>
                <div class="size-3 rounded-full bg-slate-300"></div>
                <div class="ml-4 bg-white border border-slate-200 rounded px-3 py-1 text-xs text-slate-400 flex-1 max-w-64">
                    studyflow.app/dashboard
                </div>
            </div>
            {{-- App layout --}}
            <div class="flex" style="min-height:320px">
                {{-- Sidebar --}}
                <div class="w-44 bg-indigo-50 border border-slate-200 p-3 flex flex-col gap-0.5 flex-shrink-0">
                    <div class="flex items-center gap-2 px-2 py-2 rounded-lg bg-indigo-600 mb-1">
                        <span class="material-symbols-outlined text-white" style="font-size:14px">grid_view</span>
                        <span class="text-xs text-white font-medium">Dashboard</span>
                    </div>
                    @foreach(['task_alt' => 'Tasks', 'timer' => 'Focus Timer', 'bar_chart' => 'Analytics', 'calendar_month' => 'Timetable', 'group' => 'Study Groups', 'flag' => 'Goals'] as $icon => $navLabel)
                    <div class="flex items-center gap-2 px-2 py-2 rounded-lg">
                        <span class="material-symbols-outlined text-slate-400" style="font-size:14px">{{ $icon }}</span>
                        <span class="text-xs text-slate-500">{{ $navLabel }}</span>
                    </div>
                    @endforeach
                    <div class="mt-auto flex items-center gap-2 px-2 py-2 border border-slate-200 rounded-lg">
                        <div class="size-5 rounded bg-indigo-600 flex-shrink-0"></div>
                        <span class="text-[11px] text-slate-500">Alex K. · Lv 24</span>
                    </div>
                </div>
                {{-- Main content --}}
                <div class="flex-1 p-4 space-y-3 bg-white">
                    {{-- Stats row --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="border border-slate-200 rounded-lg p-3">
                            <div class="text-xs text-slate-500 mb-1">XP Points</div>
                            <div class="text-lg font-bold text-indigo-600">2,450</div>
                            <div class="text-xs text-green-600">+120 today</div>
                        </div>
                        <div class="border border-slate-200 rounded-lg p-3">
                            <div class="text-xs text-slate-500 mb-1">Study Streak</div>
                            <div class="text-lg font-bold text-slate-900">14 days</div>
                            <div class="text-xs text-slate-400">Personal best</div>
                        </div>
                        <div class="border border-slate-200 rounded-lg p-3">
                            <div class="text-xs text-slate-500 mb-1">Tasks Done</div>
                            <div class="text-lg font-bold text-slate-900">8 / 10</div>
                            <div class="text-xs text-slate-400">Today</div>
                        </div>
                    </div>
                    {{-- Focus chart --}}
                    <div class="border border-slate-200 rounded-lg p-3">
                        <div class="text-xs text-slate-500 mb-3">Focus Hours — This Week</div>
                        <div class="flex items-end gap-1.5 h-14">
                            @foreach([55, 78, 42, 90, 68, 50, 82] as $h)
                            <div class="flex-1 rounded-t bg-indigo-200" style="height:{{ $h }}%"></div>
                            @endforeach
                        </div>
                        <div class="flex gap-1.5 mt-1">
                            @foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $d)
                            <div class="flex-1 text-center text-[9px] text-slate-400">{{ $d }}</div>
                            @endforeach
                        </div>
                    </div>
                    {{-- Task list --}}
                    <div class="space-y-2">
                        @foreach([
                            ['done' => true,  'text' => 'Read Chapter 7',   'tag' => 'Math'],
                            ['done' => true,  'text' => 'Lab Report Draft', 'tag' => 'Biology'],
                            ['done' => false, 'text' => 'Essay Outline',    'tag' => 'English'],
                            ['done' => false, 'text' => 'Problem Set 4',    'tag' => 'Physics'],
                        ] as $t)
                        <div class="flex items-center gap-2.5">
                            <div class="size-4 rounded border flex-shrink-0 flex items-center justify-center
                                        {{ $t['done'] ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300' }}">
                                @if($t['done'])
                                <span class="material-symbols-outlined text-white" style="font-size:10px;line-height:1">check</span>
                                @endif
                            </div>
                            <span class="text-xs flex-1 {{ $t['done'] ? 'line-through text-slate-400' : 'text-slate-700' }}">{{ $t['text'] }}</span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded border border-indigo-100 bg-indigo-50 text-indigo-600">{{ $t['tag'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="bg-white py-12">
    <div class="max-w-5xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([
            ['value' => '2,400+', 'label' => 'Active students'],
            ['value' => '98%',    'label' => 'Task completion rate'],
            ['value' => '4.8/5',  'label' => 'Student rating'],
            ['value' => '3.2M',   'label' => 'Focus minutes logged'],
        ] as $stat)
        <div>
            <div class="text-3xl font-bold text-slate-900 mb-1 tabular-nums">{{ $stat['value'] }}</div>
            <div class="text-sm text-slate-500">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- Features --}}
<section id="features" class="bg-indigo-50 py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <p class="text-xs font-medium uppercase tracking-wider text-indigo-600 mb-3">Features</p>
            <h2 class="text-4xl font-bold text-slate-900 mb-4">
                One platform for your entire academic life
            </h2>
            <p class="text-slate-500 max-w-lg mx-auto leading-relaxed">
                Tasks, focus sessions, collaboration, and analytics — all in one distraction-free space.
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['icon' => 'task_alt',      'title' => 'Task Management',    'desc' => 'Organise assignments with priorities, due dates, subtask checklists, recurring tasks, and file attachments.'],
                ['icon' => 'timer',          'title' => 'Focus Timer',        'desc' => 'Pomodoro sessions that auto-log work, build streaks, and reward you with XP points after every session.'],
                ['icon' => 'bar_chart',      'title' => 'Smart Analytics',    'desc' => 'Weekly heatmaps, productivity summaries, and achievement badges that reveal exactly how you study.'],
                ['icon' => 'calendar_month', 'title' => 'Visual Timetable',   'desc' => 'Plan your week with a visual schedule for classes, study blocks, labs, and recurring commitments.'],
                ['icon' => 'group',          'title' => 'Study Groups',       'desc' => 'Create or join groups, share resources, coordinate on assignments, and keep each other accountable.'],
                ['icon' => 'flag',           'title' => 'Goals & Milestones', 'desc' => 'Set academic goals, break them into milestones, track progress, and celebrate every win together.'],
            ] as $f)
            <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
                <div class="size-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-indigo-600" style="font-size:20px">{{ $f['icon'] }}</span>
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">{{ $f['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Gamification spotlight --}}
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
        {{-- XP / leaderboard card --}}
        <div class="border border-slate-200 rounded-lg p-6 shadow-sm space-y-5">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-lg bg-indigo-600 flex items-center justify-center text-sm font-bold text-white">24</div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Level 24 Scholar</div>
                            <div class="text-xs text-slate-500">2,450 / 3,000 XP</div>
                        </div>
                    </div>
                    <span class="text-xs text-indigo-600 font-medium">550 XP to go</span>
                </div>
                <div class="h-2 bg-indigo-50 border border-indigo-100 rounded overflow-hidden">
                    <div class="h-full bg-indigo-600" style="width:82%"></div>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg border border-slate-200">
                <span class="material-symbols-outlined text-indigo-600" style="font-size:22px">local_fire_department</span>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-slate-900">14-Day Study Streak</div>
                    <div class="text-xs text-slate-500">1 more day unlocks a new badge</div>
                </div>
                <span class="text-2xl font-bold text-indigo-600 tabular-nums">14</span>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-3">Achievements</div>
                <div class="grid grid-cols-4 gap-2">
                    @foreach([
                        ['icon' => 'my_location', 'name' => 'Sharp Focus', 'earned' => true],
                        ['icon' => 'menu_book',    'name' => 'Bookworm',    'earned' => true],
                        ['icon' => 'bolt',         'name' => 'Speed Run',   'earned' => true],
                        ['icon' => 'emoji_events', 'name' => 'Top Scholar', 'earned' => false],
                    ] as $badge)
                    <div class="flex flex-col items-center gap-1">
                        <div class="size-12 rounded-lg border flex items-center justify-center
                                    {{ $badge['earned'] ? 'border-indigo-100 bg-indigo-50' : 'border-slate-200 bg-slate-50 opacity-40' }}">
                            <span class="material-symbols-outlined {{ $badge['earned'] ? 'text-indigo-600' : 'text-slate-400' }}" style="font-size:20px">{{ $badge['icon'] }}</span>
                        </div>
                        <span class="text-[9px] text-slate-500 text-center leading-tight">{{ $badge['name'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase tracking-wider mb-3">Weekly Leaderboard</div>
                <div class="space-y-1.5">
                    @foreach([
                        ['rank' => 1, 'name' => 'Sarah M.', 'xp' => '4,120', 'self' => false],
                        ['rank' => 2, 'name' => 'You',       'xp' => '2,450', 'self' => true],
                        ['rank' => 3, 'name' => 'James K.',  'xp' => '2,180', 'self' => false],
                    ] as $entry)
                    <div class="flex items-center gap-3 py-1.5 px-2 rounded-lg {{ $entry['self'] ? 'bg-indigo-50 border border-indigo-100' : '' }}">
                        <span class="text-sm font-bold text-indigo-600 w-4 tabular-nums">{{ $entry['rank'] }}</span>
                        <span class="text-xs flex-1 {{ $entry['self'] ? 'text-slate-900 font-medium' : 'text-slate-500' }}">{{ $entry['name'] }}</span>
                        <span class="text-xs text-slate-500 tabular-nums">{{ $entry['xp'] }} XP</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Text --}}
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-indigo-600 mb-3">Gamification</p>
            <h2 class="text-4xl font-bold text-slate-900 mb-5 leading-tight">
                Turn studying into a game you want to win
            </h2>
            <p class="text-slate-500 text-lg leading-relaxed mb-7">
                Earn XP for every task you complete, build daily streaks, unlock achievement
                badges, and compete on weekly leaderboards with your classmates.
            </p>
            <ul class="space-y-3">
                @foreach([
                    'XP system that rewards consistent study habits',
                    'Achievement badges for academic milestones',
                    'Weekly leaderboards with classmates',
                    'Streak tracking keeps you coming back daily',
                ] as $point)
                <li class="flex items-center gap-3 text-sm text-slate-700">
                    <span class="material-symbols-outlined text-indigo-600 flex-shrink-0" style="font-size:16px">check_circle</span>
                    {{ $point }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- How it works --}}
<section id="how-it-works" class="bg-indigo-50 py-20">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-14">
            <p class="text-xs font-medium uppercase tracking-wider text-indigo-600 mb-3">Getting started</p>
            <h2 class="text-4xl font-bold text-slate-900 mb-4">Up and running in under 2 minutes</h2>
            <p class="text-slate-500 max-w-sm mx-auto">No setup headaches. Sign up and start studying smarter straight away.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['step' => '01', 'title' => 'Create your account',  'desc' => 'Sign up for free in seconds — no credit card, no complex setup required.'],
                ['step' => '02', 'title' => 'Add tasks & schedule', 'desc' => 'Import your timetable, add assignments, and set your academic goals in one place.'],
                ['step' => '03', 'title' => 'Study and level up',   'desc' => 'Log focus sessions, complete tasks, earn XP, and watch your performance improve.'],
            ] as $step)
            <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm text-center">
                <div class="size-12 rounded-lg bg-indigo-600 flex items-center justify-center mx-auto mb-4 text-xl font-bold text-white font-mono">
                    {{ $step['step'] }}
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-indigo-600 py-20">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <span class="material-symbols-outlined text-indigo-300 mb-4 block" style="font-size:48px">school</span>
        <h2 class="text-4xl font-bold text-white mb-4">Ready to study smarter?</h2>
        <p class="text-indigo-200 mb-8 max-w-sm mx-auto leading-relaxed">
            Join thousands of students already using StudyFlow to get ahead.
        </p>
        <a href="{{ route('register') }}"
           class="inline-flex items-center gap-2 px-8 py-4 rounded-lg bg-white hover:bg-indigo-50 text-indigo-600 font-semibold text-base transition-colors">
            Create your free account
            <span class="material-symbols-outlined" style="font-size:18px">arrow_forward</span>
        </a>
    </div>
</section>

</main>

{{-- Footer --}}
<footer class="bg-white border border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
        <div class="flex items-center gap-2.5">
            <x-app-logo-icon class="size-5 text-slate-400" />
            <span>{{ config('site.name', 'StudyFlow') }} · {{ date('Y') }}</span>
        </div>
        <div class="flex items-center gap-6">
            <a href="{{ route('login') }}"    class="hover:text-slate-900 transition-colors">Sign in</a>
            <a href="{{ route('register') }}" class="hover:text-slate-900 transition-colors">Sign up</a>
        </div>
    </div>
</footer>

@fluxScripts
</body>
</html>