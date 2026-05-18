<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('site.name') }} - {{ config('site.tagline') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxStyles
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 font-sans antialiased">

    <header class="border-b border-zinc-800">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-app-logo-icon class="size-7 text-indigo-400" />
                <span class="font-semibold text-lg tracking-tight text-white">{{ config('site.name') }}</span>
            </div>
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-lg border border-zinc-700 hover:border-zinc-500 text-zinc-300 hover:text-white text-sm font-medium transition-colors">Sign in</a>
                    <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors">Get started</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="max-w-4xl mx-auto px-6 pt-24 pb-20 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 rounded-full bg-indigo-950 border border-indigo-800 text-indigo-400 text-xs font-medium uppercase tracking-wide">
                <span class="size-1.5 rounded-full bg-indigo-400 inline-block"></span>
                Student Time Management
            </div>
            <h1 class="text-5xl font-bold tracking-tight text-white mb-5 leading-tight">{{ config('site.tagline') }}</h1>
            <p class="text-xl text-zinc-400 max-w-2xl mx-auto mb-10 leading-relaxed">{{ config('site.description') }}</p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-7 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-base transition-colors">Start for free</a>
                <a href="{{ route('login') }}" class="px-7 py-3 rounded-lg border border-zinc-700 hover:border-zinc-500 text-zinc-300 hover:text-white font-semibold text-base transition-colors">Sign in</a>
            </div>
        </section>

        <section class="max-w-6xl mx-auto px-6 pb-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-xl border border-zinc-800 bg-zinc-900">
                    <div class="size-10 rounded-lg bg-indigo-950 flex items-center justify-center mb-4"><flux:icon icon="clipboard-document-list" variant="outline" class="size-5 text-indigo-400" /></div>
                    <h3 class="font-semibold text-white mb-2">Task Management</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">Organise assignments with priorities, due dates, subtask checklists, and file attachments.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-800 bg-zinc-900">
                    <div class="size-10 rounded-lg bg-indigo-950 flex items-center justify-center mb-4"><flux:icon icon="clock" variant="outline" class="size-5 text-indigo-400" /></div>
                    <h3 class="font-semibold text-white mb-2">Focus Timer</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">Pomodoro-style study sessions with automatic logging, streak tracking, and XP rewards.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-800 bg-zinc-900">
                    <div class="size-10 rounded-lg bg-indigo-950 flex items-center justify-center mb-4"><flux:icon icon="chart-bar" variant="outline" class="size-5 text-indigo-400" /></div>
                    <h3 class="font-semibold text-white mb-2">Analytics</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">Weekly and monthly productivity summaries, achievement badges, and progress heatmaps.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-800 bg-zinc-900">
                    <div class="size-10 rounded-lg bg-indigo-950 flex items-center justify-center mb-4"><flux:icon icon="calendar-days" variant="outline" class="size-5 text-indigo-400" /></div>
                    <h3 class="font-semibold text-white mb-2">Timetable</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">Visual weekly schedule for classes, labs, and recurring academic commitments.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-800 bg-zinc-900">
                    <div class="size-10 rounded-lg bg-indigo-950 flex items-center justify-center mb-4"><flux:icon icon="user-group" variant="outline" class="size-5 text-indigo-400" /></div>
                    <h3 class="font-semibold text-white mb-2">Study Groups</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">Collaborate with classmates in shared spaces with discussion and resource sharing.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-800 bg-zinc-900">
                    <div class="size-10 rounded-lg bg-indigo-950 flex items-center justify-center mb-4"><flux:icon icon="flag" variant="outline" class="size-5 text-indigo-400" /></div>
                    <h3 class="font-semibold text-white mb-2">Goals & Progress</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">Set academic milestones, track progress with milestones, and celebrate completions.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-zinc-800 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-zinc-500">
            <span>{{ date('Y') }} {{ config('site.name') }}. Built for students.</span>
            <a href="mailto:{{ config('site.support_email') }}" class="hover:text-zinc-300 transition-colors">{{ config('site.support_email') }}</a>
        </div>
    </footer>

    @fluxScripts
</body>
</html>