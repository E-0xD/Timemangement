<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('site.name', 'StudyFlow') }} — Study smarter, achieve more</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles
    <style>
        @keyframes float {
            0%,100% { transform: translateY(0) rotate(-0.5deg); }
            50%      { transform: translateY(-18px) rotate(0.5deg); }
        }
        @keyframes blob {
            0%,100% { transform: translate(0,0) scale(1); }
            33%     { transform: translate(40px,-55px) scale(1.12); }
            66%     { transform: translate(-30px,25px) scale(0.92); }
        }
        @keyframes gradient-x {
            0%,100% { background-position: 0% 50%; }
            50%     { background-position: 100% 50%; }
        }
        @keyframes slide-up {
            from { opacity:0; transform:translateY(28px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes bar-grow {
            from { height: 0%; }
        }
        @keyframes bar-fill {
            from { transform: scaleX(0); }
            to   { transform: scaleX(1); }
        }

        .animate-float  { animation: float 7s ease-in-out infinite; }
        .blob-1 { animation: blob 20s ease-in-out infinite; }
        .blob-2 { animation: blob 26s ease-in-out infinite reverse; animation-delay:-8s; }
        .blob-3 { animation: blob 22s ease-in-out infinite; animation-delay:-15s; }

        .gradient-text {
            background: linear-gradient(130deg,#a5b4fc,#818cf8,#c4b5fd);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-x 5s ease infinite;
        }
        .glass {
            background: rgba(9,9,11,0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .reveal {
            opacity:0;
            transform:translateY(22px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .reveal.visible { opacity:1; transform:translateY(0); }
        .hero-text { animation: slide-up 0.9s cubic-bezier(0.16,1,0.3,1) both; }
        .hero-mockup { animation: slide-up 0.9s cubic-bezier(0.16,1,0.3,1) 0.18s both; }
        .glow-card {
            box-shadow: 0 0 0 1px rgba(99,102,241,0.12),
                        0 20px 60px -12px rgba(79,70,229,0.18);
        }
        .glow-card:hover {
            box-shadow: 0 0 0 1px rgba(99,102,241,0.22),
                        0 24px 70px -10px rgba(79,70,229,0.28);
        }
    </style>
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 font-sans antialiased overflow-x-hidden">

{{-- ─── Animated background ─────────────────────────────────────────── --}}
<div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
    <div class="blob-1 absolute -top-72 -left-72 w-[650px] h-[650px] rounded-full bg-indigo-600 opacity-[0.09] blur-[100px]"></div>
    <div class="blob-2 absolute top-1/2 -right-64 w-[560px] h-[560px] rounded-full bg-violet-600 opacity-[0.07] blur-[90px]"></div>
    <div class="blob-3 absolute -bottom-56 left-1/3 w-[700px] h-[700px] rounded-full bg-indigo-700 opacity-[0.06] blur-[110px]"></div>
    <div class="absolute inset-0"
         style="background-image:linear-gradient(rgba(99,102,241,0.025) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.025) 1px,transparent 1px);background-size:48px 48px;">
    </div>
</div>

{{-- ─── Navigation ──────────────────────────────────────────────────── --}}
<nav class="sticky top-0 z-50 border-b border-zinc-800/50 glass">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <x-app-logo-icon class="size-7 text-indigo-400" />
            <span class="font-bold text-lg tracking-tight text-white">{{ config('site.name', 'StudyFlow') }}</span>
        </div>
        <div class="hidden md:flex items-center gap-8 text-sm text-zinc-400">
            <a href="#features"     class="hover:text-white transition-colors">Features</a>
            <a href="#gamification" class="hover:text-white transition-colors">Gamification</a>
            <a href="#how-it-works" class="hover:text-white transition-colors">How it works</a>
        </div>
        <div class="flex items-center gap-2.5">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-all hover:shadow-lg hover:shadow-indigo-500/25">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-zinc-400 hover:text-white text-sm font-medium transition-colors">
                    Sign in
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-all hover:shadow-lg hover:shadow-indigo-500/25">
                    Get started free
                </a>
            @endauth
        </div>
    </div>
</nav>

<main>

{{-- ─── Hero ────────────────────────────────────────────────────────── --}}
<section class="relative pt-20 pb-16 md:pt-28 md:pb-24">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-14 items-center">

        {{-- Text --}}
        <div class="text-center md:text-left hero-text">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 rounded-full
                        bg-indigo-950/60 border border-indigo-800/40
                        text-indigo-300 text-xs font-medium tracking-wide">
                <span class="size-1.5 rounded-full bg-indigo-400 animate-pulse inline-block"></span>
                Built for university students
            </div>

            <h1 class="text-5xl md:text-6xl font-bold tracking-tight leading-[1.1] mb-6 text-white">
                Study smarter,<br>
                <span class="gradient-text">achieve more</span>
            </h1>

            <p class="text-lg text-zinc-400 leading-relaxed mb-8 max-w-[440px] mx-auto md:mx-0">
                The all-in-one study platform for task management, focus sessions,
                gamification, and team collaboration — beautifully organised.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                <a href="{{ route('register') }}"
                   class="px-7 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500
                          text-white font-semibold text-base transition-all
                          hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0">
                    Start for free →
                </a>
                <a href="{{ route('login') }}"
                   class="px-7 py-3.5 rounded-xl border border-zinc-700 hover:border-zinc-500
                          text-zinc-300 hover:text-white font-semibold text-base transition-all hover:-translate-y-0.5">
                    Sign in
                </a>
            </div>

            <p class="mt-4 text-xs text-zinc-600">No credit card · Free for students</p>
        </div>

        {{-- Floating dashboard mockup --}}
        <div class="hero-mockup animate-float glow-card rounded-2xl">
            <div class="bg-zinc-900 rounded-2xl border border-zinc-700/50 overflow-hidden">

                {{-- Browser chrome --}}
                <div class="bg-zinc-800/60 px-4 py-2.5 flex items-center gap-2 border-b border-zinc-700/50">
                    <div class="size-3 rounded-full bg-red-500/60"></div>
                    <div class="size-3 rounded-full bg-yellow-500/60"></div>
                    <div class="size-3 rounded-full bg-green-500/60"></div>
                    <div class="ml-3 flex items-center gap-2 bg-zinc-700/50 rounded-md px-3 py-0.5 flex-1 max-w-52 text-xs text-zinc-500">
                        <span class="size-1.5 rounded-full bg-green-500 flex-shrink-0 animate-pulse"></span>
                        studyflow.app/dashboard
                    </div>
                </div>

                {{-- App shell --}}
                <div class="flex" style="min-height:340px">

                    {{-- Sidebar --}}
                    <div class="w-44 bg-zinc-950/80 border-r border-zinc-800/50 p-3 flex flex-col gap-1 flex-shrink-0">
                        <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg bg-indigo-600/15 border border-indigo-600/25 mb-2">
                            <div class="size-3.5 rounded bg-indigo-500 flex-shrink-0"></div>
                            <span class="text-xs text-indigo-300 font-medium">Dashboard</span>
                        </div>
                        @foreach(['Tasks','Focus Timer','Analytics','Timetable','Study Groups','Goals'] as $item)
                        <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg">
                            <div class="size-3 rounded-sm bg-zinc-700/80 flex-shrink-0"></div>
                            <span class="text-[11px] text-zinc-600">{{ $item }}</span>
                        </div>
                        @endforeach
                        <div class="mt-auto flex items-center gap-2 px-2 py-2">
                            <div class="size-5 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex-shrink-0"></div>
                            <span class="text-[11px] text-zinc-500">Alex K.</span>
                            <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded-full bg-yellow-500/15 text-yellow-400 font-medium">Lv 24</span>
                        </div>
                    </div>

                    {{-- Main content --}}
                    <div class="flex-1 p-3.5 space-y-3 overflow-hidden">

                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-zinc-800/50 border border-zinc-700/40 rounded-xl p-2.5">
                                <div class="text-[10px] text-zinc-500 mb-0.5">XP Points</div>
                                <div class="text-base font-bold text-indigo-400">2,450</div>
                                <div class="text-[10px] text-green-500">+120 today</div>
                            </div>
                            <div class="bg-zinc-800/50 border border-zinc-700/40 rounded-xl p-2.5">
                                <div class="text-[10px] text-zinc-500 mb-0.5">Streak 🔥</div>
                                <div class="text-base font-bold text-orange-400">14 days</div>
                                <div class="text-[10px] text-zinc-600">Best!</div>
                            </div>
                            <div class="bg-zinc-800/50 border border-zinc-700/40 rounded-xl p-2.5">
                                <div class="text-[10px] text-zinc-500 mb-0.5">Done</div>
                                <div class="text-base font-bold text-green-400">8/10</div>
                                <div class="text-[10px] text-zinc-600">Today</div>
                            </div>
                        </div>

                        {{-- Chart --}}
                        <div class="bg-zinc-800/40 border border-zinc-700/40 rounded-xl p-3">
                            <div class="text-[10px] text-zinc-500 mb-2.5">Focus Hours — This Week</div>
                            <div class="flex items-end gap-1.5 h-16">
                                @foreach([55,78,42,90,68,50,82] as $h)
                                <div class="flex-1 rounded-t bg-indigo-600/40 hover:bg-indigo-500/60 transition-colors"
                                     style="height:{{ $h }}%;
                                            animation:bar-grow 0.9s cubic-bezier(0.34,1.56,0.64,1) both;
                                            animation-delay:{{ $loop->index * 120 }}ms;">
                                </div>
                                @endforeach
                            </div>
                            <div class="flex gap-1.5 mt-1">
                                @foreach(['M','T','W','T','F','S','S'] as $d)
                                <div class="flex-1 text-center text-[9px] text-zinc-700">{{ $d }}</div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Task list --}}
                        <div class="space-y-1.5">
                            @foreach([
                                ['done'=>true, 'text'=>'Read Chapter 7',    'tag'=>'Math'],
                                ['done'=>true, 'text'=>'Lab Report Draft', 'tag'=>'Biology'],
                                ['done'=>false,'text'=>'Essay Outline',    'tag'=>'English'],
                                ['done'=>false,'text'=>'Problem Set 4',    'tag'=>'Physics'],
                            ] as $t)
                            <div class="flex items-center gap-2 px-2 py-1 rounded-lg {{ $t['done'] ? 'opacity-40' : 'bg-zinc-800/30' }}">
                                <div class="size-3.5 rounded border flex-shrink-0 flex items-center justify-center
                                            {{ $t['done'] ? 'bg-indigo-500 border-indigo-500' : 'border-zinc-600' }}">
                                    @if($t['done'])<span class="text-white leading-none" style="font-size:8px">✓</span>@endif
                                </div>
                                <span class="text-[11px] flex-1 truncate {{ $t['done'] ? 'line-through text-zinc-600' : 'text-zinc-300' }}">{{ $t['text'] }}</span>
                                <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-zinc-700/60 text-zinc-500 flex-shrink-0">{{ $t['tag'] }}</span>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Stats bar ───────────────────────────────────────────────────── --}}
<section class="border-y border-zinc-800/50 py-10">
    <div class="max-w-5xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([
            ['value'=>'2,400+', 'label'=>'Active students'],
            ['value'=>'98%',    'label'=>'Task completion rate'],
            ['value'=>'4.8★',   'label'=>'Student rating'],
            ['value'=>'3.2M',   'label'=>'Focus minutes logged'],
        ] as $i => $stat)
        <div class="reveal" style="transition-delay:{{ $i * 80 }}ms">
            <div class="text-3xl font-bold text-white mb-1 tabular-nums">{{ $stat['value'] }}</div>
            <div class="text-sm text-zinc-500">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ─── Features ────────────────────────────────────────────────────── --}}
<section id="features" class="py-24">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14 reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-4 rounded-full
                        bg-indigo-950/60 border border-indigo-800/40 text-indigo-300 text-xs font-medium">
                Everything you need
            </div>
            <h2 class="text-4xl font-bold text-white mb-4">
                One platform for your<br>
                <span class="gradient-text">entire academic life</span>
            </h2>
            <p class="text-zinc-400 max-w-lg mx-auto leading-relaxed">
                Tasks, focus sessions, collaboration, and analytics — all in one
                beautiful, distraction-free space.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['icon'=>'clipboard-document-list', 'title'=>'Task Management',
                 'desc'=>'Organise assignments with priorities, due dates, subtask checklists, recurring tasks, and file attachments.'],
                ['icon'=>'clock', 'title'=>'Focus Timer',
                 'desc'=>'Pomodoro sessions that auto-log work, build streaks, and reward you with XP points after every session.'],
                ['icon'=>'chart-bar', 'title'=>'Smart Analytics',
                 'desc'=>'Weekly heatmaps, productivity summaries, and achievement badges that reveal exactly how you study.'],
                ['icon'=>'calendar-days', 'title'=>'Visual Timetable',
                 'desc'=>'Plan your week with a visual schedule for classes, study blocks, labs, and recurring commitments.'],
                ['icon'=>'user-group', 'title'=>'Study Groups',
                 'desc'=>'Create or join groups, share resources, coordinate on assignments, and keep each other accountable.'],
                ['icon'=>'flag', 'title'=>'Goals & Milestones',
                 'desc'=>'Set academic goals, break them into milestones, track progress, and celebrate every win together.'],
            ] as $i => $f)
            <div class="reveal group p-6 rounded-2xl border border-zinc-800/50 bg-zinc-900/50
                        hover:border-indigo-700/40 hover:bg-zinc-900/80 transition-all duration-300
                        hover:-translate-y-1.5 hover:shadow-xl hover:shadow-indigo-900/20 cursor-default"
                 style="transition-delay:{{ $i * 70 }}ms">
                <div class="size-11 rounded-xl bg-indigo-950/50 border border-indigo-900/40
                            group-hover:border-indigo-700/50 flex items-center justify-center mb-5
                            group-hover:bg-indigo-950/80 transition-all">
                    <flux:icon icon="{{ $f['icon'] }}" variant="outline" class="size-5 text-indigo-400" />
                </div>
                <h3 class="font-semibold text-white mb-2 text-base">{{ $f['title'] }}</h3>
                <p class="text-sm text-zinc-500 leading-relaxed group-hover:text-zinc-400 transition-colors">
                    {{ $f['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── Gamification spotlight ─────────────────────────────────────── --}}
<section id="gamification" class="py-20 overflow-hidden border-t border-zinc-800/50">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">

        {{-- Gamification card --}}
        <div class="reveal order-2 md:order-1" style="transition-delay:100ms">
            <div class="bg-zinc-900 border border-zinc-800/60 rounded-2xl p-6 space-y-5 glow-card">

                {{-- Level & XP --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600
                                        flex items-center justify-center text-sm font-bold text-white">24</div>
                            <div>
                                <div class="text-sm font-semibold text-white">Level 24 Scholar</div>
                                <div class="text-xs text-zinc-500">2,450 / 3,000 XP</div>
                            </div>
                        </div>
                        <span class="text-xs text-indigo-400 font-medium">550 XP to go</span>
                    </div>
                    <div class="h-2 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full"
                             style="width:82%;transform-origin:left;
                                    animation:bar-fill 1.4s cubic-bezier(0.4,0,0.2,1) both 0.4s;">
                        </div>
                    </div>
                </div>

                {{-- Streak --}}
                <div class="flex items-center gap-3 p-3.5 rounded-xl bg-orange-950/30 border border-orange-900/30">
                    <span class="text-2xl">🔥</span>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-orange-300">14-Day Study Streak!</div>
                        <div class="text-xs text-zinc-500">1 more day unlocks the Consistent Learner badge</div>
                    </div>
                    <span class="text-2xl font-bold text-orange-400 tabular-nums">14</span>
                </div>

                {{-- Badges --}}
                <div>
                    <div class="text-[10px] text-zinc-500 uppercase tracking-wider mb-3">Achievements</div>
                    <div class="grid grid-cols-4 gap-2.5">
                        @foreach([
                            ['emoji'=>'🎯','name'=>'Sharp Focus',  'earned'=>true],
                            ['emoji'=>'📚','name'=>'Bookworm',     'earned'=>true],
                            ['emoji'=>'⚡','name'=>'Speed Run',    'earned'=>true],
                            ['emoji'=>'🏆','name'=>'Top Scholar',  'earned'=>false],
                        ] as $badge)
                        <div class="flex flex-col items-center gap-1">
                            <div class="size-12 rounded-xl border flex items-center justify-center text-xl
                                        {{ $badge['earned']
                                            ? 'bg-indigo-950/60 border-indigo-800/50'
                                            : 'bg-zinc-800/30 border-zinc-700/30 grayscale opacity-35' }}">
                                {{ $badge['emoji'] }}
                            </div>
                            <span class="text-[9px] text-zinc-600 text-center leading-tight">{{ $badge['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Leaderboard --}}
                <div>
                    <div class="text-[10px] text-zinc-500 uppercase tracking-wider mb-3">Weekly Leaderboard</div>
                    <div class="space-y-1.5">
                        @foreach([
                            ['rank'=>1,'name'=>'Sarah M.', 'xp'=>'4,120','color'=>'text-yellow-400', 'self'=>false],
                            ['rank'=>2,'name'=>'You',       'xp'=>'2,450','color'=>'text-indigo-400', 'self'=>true],
                            ['rank'=>3,'name'=>'James K.',  'xp'=>'2,180','color'=>'text-orange-400','self'=>false],
                        ] as $entry)
                        <div class="flex items-center gap-3 py-1.5 px-2.5 rounded-lg
                                    {{ $entry['self'] ? 'bg-indigo-950/40 border border-indigo-900/40' : '' }}">
                            <span class="text-sm font-bold w-4 {{ $entry['color'] }}">{{ $entry['rank'] }}</span>
                            <span class="text-xs flex-1 {{ $entry['self'] ? 'text-white font-medium' : 'text-zinc-400' }}">
                                {{ $entry['name'] }}
                            </span>
                            <span class="text-xs text-zinc-500 tabular-nums">{{ $entry['xp'] }} XP</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Text --}}
        <div class="reveal order-1 md:order-2">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 rounded-full
                        bg-orange-950/50 border border-orange-800/30 text-orange-300 text-xs font-medium">
                🎮 Gamified learning
            </div>
            <h2 class="text-4xl font-bold text-white mb-5 leading-tight">
                Turn studying into<br>
                <span class="gradient-text">a game you want to win</span>
            </h2>
            <p class="text-zinc-400 text-lg leading-relaxed mb-7">
                Earn XP for every task you complete, build daily streaks, unlock achievement
                badges, and compete on weekly leaderboards with your classmates.
            </p>
            <ul class="space-y-3.5">
                @foreach([
                    'XP system that rewards consistent study habits',
                    'Achievement badges for academic milestones',
                    'Weekly leaderboards with classmates',
                    'Streak tracking keeps you coming back daily',
                ] as $i => $point)
                <li class="flex items-center gap-3 text-sm text-zinc-300" style="transition-delay:{{ $i * 60 }}ms">
                    <div class="size-5 rounded-full bg-indigo-600/20 border border-indigo-600/40
                                flex items-center justify-center flex-shrink-0">
                        <div class="size-1.5 rounded-full bg-indigo-400"></div>
                    </div>
                    {{ $point }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ─── How it works ────────────────────────────────────────────────── --}}
<section id="how-it-works" class="py-24 border-t border-zinc-800/50">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-16 reveal">
            <h2 class="text-4xl font-bold text-white mb-4">
                Up and running in<br><span class="gradient-text">under 2 minutes</span>
            </h2>
            <p class="text-zinc-400 max-w-sm mx-auto">No setup headaches. Sign up and start studying smarter straight away.</p>
        </div>

        <div class="relative grid md:grid-cols-3 gap-10">
            <div class="hidden md:block absolute top-8 left-[22%] right-[22%] h-px
                        bg-gradient-to-r from-transparent via-indigo-600/40 to-transparent pointer-events-none">
            </div>
            @foreach([
                ['step'=>'01','title'=>'Create your account',
                 'desc'=>'Sign up for free in seconds — no credit card, no complex setup required.'],
                ['step'=>'02','title'=>'Add tasks & schedule',
                 'desc'=>'Import your timetable, add assignments, and set your academic goals in one place.'],
                ['step'=>'03','title'=>'Study and level up',
                 'desc'=>'Log focus sessions, complete tasks, earn XP, and watch your performance improve.'],
            ] as $i => $step)
            <div class="reveal text-center" style="transition-delay:{{ $i * 130 }}ms">
                <div class="size-16 rounded-2xl bg-indigo-950/60 border border-indigo-800/40
                            flex items-center justify-center mx-auto mb-5
                            text-2xl font-bold text-indigo-400 font-mono tracking-tight">
                    {{ $step['step'] }}
                </div>
                <h3 class="font-semibold text-white mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── CTA ─────────────────────────────────────────────────────────── --}}
<section class="pb-24">
    <div class="max-w-4xl mx-auto px-6">
        <div class="reveal relative rounded-3xl overflow-hidden border border-indigo-800/30
                    bg-gradient-to-br from-indigo-900/30 to-violet-900/20 p-16 text-center">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/5 to-violet-600/5 pointer-events-none"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-indigo-600 opacity-[0.08] blur-3xl pointer-events-none"></div>
            <div class="relative">
                <div class="text-5xl mb-5">🎓</div>
                <h2 class="text-4xl font-bold text-white mb-4">Ready to study smarter?</h2>
                <p class="text-zinc-400 mb-8 max-w-sm mx-auto leading-relaxed">
                    Join thousands of students already using StudyFlow to get ahead.
                </p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2.5 px-8 py-4 rounded-xl
                          bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-lg
                          transition-all hover:shadow-2xl hover:shadow-indigo-500/40 hover:-translate-y-1">
                    Create your free account
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

</main>

{{-- ─── Footer ──────────────────────────────────────────────────────── --}}
<footer class="border-t border-zinc-800/50 py-10">
    <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-zinc-600">
        <div class="flex items-center gap-2">
            <x-app-logo-icon class="size-5 text-zinc-700" />
            <span>{{ config('site.name', 'StudyFlow') }} · {{ date('Y') }} · Built for students</span>
        </div>
        <div class="flex items-center gap-6">
            <a href="mailto:{{ config('site.support_email', 'support@studyflow.app') }}"
               class="hover:text-zinc-400 transition-colors">Support</a>
            <a href="{{ route('login') }}"     class="hover:text-zinc-400 transition-colors">Sign in</a>
            <a href="{{ route('register') }}"  class="hover:text-zinc-400 transition-colors">Sign up</a>
        </div>
    </div>
</footer>

{{-- Scroll-reveal observer --}}
<script>
    (function () {
        const io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.08 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
    })();
</script>

@fluxScripts
</body>
</html>