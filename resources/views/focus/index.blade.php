<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Focus Timer</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                    Studied <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ floor($todayMinutes / 60) }}h {{ $todayMinutes % 60 }}m</span> today
                    &middot; {{ $todaySessions->count() }} session{{ $todaySessions->count() === 1 ? '' : 's' }}
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 lg:grid-cols-[1fr_360px]">

            {{-- ── Timer Panel ──────────────────────────────────────────────── --}}
            <div
                x-data="{
                    workDuration: 25,
                    shortBreakDuration: 5,
                    longBreakDuration: 15,
                    phase: 'idle',
                    remaining: 0,
                    total: 1,
                    running: false,
                    interval: null,
                    pomodorosDone: 0,
                    showSettings: false,
                    selectedCourse: '',
                    selectedTask: '',

                    get progress() {
                        return this.total > 0 ? (this.total - this.remaining) / this.total : 0;
                    },
                    get displayTime() {
                        const m = Math.floor(this.remaining / 60);
                        const s = this.remaining % 60;
                        return String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
                    },
                    get dashOffset() {
                        return 283 * (1 - this.progress);
                    },
                    get phaseColour() {
                        if (this.phase === 'focus') return '#4F46E5';
                        if (this.phase === 'short_break') return '#10B981';
                        if (this.phase === 'long_break') return '#F59E0B';
                        return '#71717A';
                    },
                    get phaseLabel() {
                        const map = { idle: 'Ready', focus: 'Focus', short_break: 'Short Break', long_break: 'Long Break' };
                        return map[this.phase] || '';
                    },
                    get canStartBreak() { return this.phase === 'idle' || this.phase === 'focus'; },

                    startPhase(phase) {
                        clearInterval(this.interval);
                        this.phase = phase;
                        if (phase === 'focus')       this.remaining = this.total = this.workDuration * 60;
                        else if (phase === 'short_break') this.remaining = this.total = this.shortBreakDuration * 60;
                        else                          this.remaining = this.total = this.longBreakDuration * 60;
                        this.running = true;
                        this.tick();
                    },

                    tick() {
                        this.interval = setInterval(() => {
                            if (this.remaining > 0) {
                                this.remaining--;
                            } else {
                                clearInterval(this.interval);
                                this.running = false;
                                this.onComplete();
                            }
                        }, 1000);
                    },

                    pause() {
                        clearInterval(this.interval);
                        this.running = false;
                    },

                    resume() {
                        if (this.remaining > 0) { this.running = true; this.tick(); }
                    },

                    reset() {
                        clearInterval(this.interval);
                        this.running = false;
                        this.phase = 'idle';
                        this.remaining = 0;
                        this.total = 1;
                    },

                    onComplete() {
                        this.beep();
                        if (this.phase === 'focus') {
                            this.pomodorosDone++;
                            document.getElementById('sess-type').value = 'pomodoro';
                            document.getElementById('sess-duration').value = this.workDuration;
                            document.getElementById('sess-course').value = this.selectedCourse;
                            document.getElementById('sess-task').value = this.selectedTask;
                            document.getElementById('sess-form').submit();
                        } else {
                            this.phase = 'idle';
                        }
                    },

                    beep() {
                        try {
                            const ctx = new (window.AudioContext || window.webkitAudioContext)();
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();
                            osc.connect(gain);
                            gain.connect(ctx.destination);
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(523, ctx.currentTime);
                            gain.gain.setValueAtTime(0.4, ctx.currentTime);
                            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.2);
                            osc.start(ctx.currentTime);
                            osc.stop(ctx.currentTime + 1.2);
                        } catch(e) {}
                    }
                }"
                class="flex flex-col gap-4"
            >
                {{-- Timer card --}}
                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

                    {{-- Phase tabs --}}
                    <div class="mb-6 flex gap-2 rounded-lg bg-zinc-100 p-1 dark:bg-zinc-800">
                        <button @click="startPhase('focus')"
                            :class="phase === 'focus' ? 'bg-white shadow-sm dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300'"
                            class="flex-1 rounded-md py-1.5 text-sm transition-all">
                            Focus
                        </button>
                        <button @click="startPhase('short_break')"
                            :class="phase === 'short_break' ? 'bg-white shadow-sm dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300'"
                            class="flex-1 rounded-md py-1.5 text-sm transition-all">
                            Short Break
                        </button>
                        <button @click="startPhase('long_break')"
                            :class="phase === 'long_break' ? 'bg-white shadow-sm dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300'"
                            class="flex-1 rounded-md py-1.5 text-sm transition-all">
                            Long Break
                        </button>
                    </div>

                    {{-- SVG Ring Timer --}}
                    <div class="flex flex-col items-center gap-6">
                        <div class="relative flex items-center justify-center">
                            <svg viewBox="0 0 100 100" class="h-52 w-52 -rotate-90">
                                {{-- Track --}}
                                <circle cx="50" cy="50" r="45"
                                    class="fill-none stroke-zinc-100 dark:stroke-zinc-800"
                                    stroke-width="7" />
                                {{-- Progress arc --}}
                                <circle cx="50" cy="50" r="45"
                                    fill="none"
                                    stroke-width="7"
                                    stroke-linecap="round"
                                    stroke-dasharray="283"
                                    :stroke-dashoffset="dashOffset"
                                    :stroke="phaseColour"
                                    style="transition: stroke-dashoffset 0.9s linear, stroke 0.3s;" />
                            </svg>
                            {{-- Centre display --}}
                            <div class="absolute flex flex-col items-center">
                                <span class="text-4xl font-bold tabular-nums text-zinc-900 dark:text-zinc-100"
                                    x-text="phase === 'idle' ? '--:--' : displayTime"></span>
                                <span class="mt-1 text-xs font-medium uppercase tracking-widest"
                                    :style="`color: ${phaseColour}`"
                                    x-text="phaseLabel"></span>
                                <span class="mt-1 text-xs text-zinc-400 dark:text-zinc-500"
                                    x-show="pomodorosDone > 0"
                                    x-text="pomodorosDone + ' done today'"></span>
                            </div>
                        </div>

                        {{-- Controls --}}
                        <div class="flex items-center gap-3">
                            <button
                                x-show="phase === 'idle'"
                                @click="startPhase('focus')"
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700">
                                <span class="material-symbols-outlined text-base leading-none">play_arrow</span>
                                Start Focus
                            </button>
                            <button
                                x-show="running"
                                @click="pause()"
                                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-6 py-2.5 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                                <span class="material-symbols-outlined text-base leading-none">pause</span>
                                Pause
                            </button>
                            <button
                                x-show="!running && phase !== 'idle' && remaining > 0"
                                @click="resume()"
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700">
                                <span class="material-symbols-outlined text-base leading-none">play_arrow</span>
                                Resume
                            </button>
                            <button
                                x-show="phase !== 'idle'"
                                @click="reset()"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 text-zinc-400 transition-colors hover:bg-zinc-50 hover:text-zinc-600 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                <span class="material-symbols-outlined text-base leading-none">refresh</span>
                            </button>
                        </div>
                    </div>

                    {{-- Session context (course + task) --}}
                    <div class="mt-6 grid gap-3 border-t border-zinc-100 pt-5 dark:border-zinc-800 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Study for (optional)</label>
                            <select x-model="selectedCourse"
                                class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="">— Course —</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Working on (optional)</label>
                            <select x-model="selectedTask"
                                class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="">— Task —</option>
                                @foreach($tasks as $task)
                                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Settings card --}}
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <button
                        @click="showSettings = !showSettings"
                        class="flex w-full items-center justify-between px-5 py-4 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        <span class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base leading-none text-zinc-400">tune</span>
                            Timer Settings
                        </span>
                        <span class="material-symbols-outlined text-base leading-none text-zinc-400 transition-transform"
                            :class="showSettings ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="showSettings" x-collapse class="border-t border-zinc-100 dark:border-zinc-800">
                        <div class="grid gap-4 px-5 py-4 sm:grid-cols-3">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Focus (min)</label>
                                <input type="number" x-model.number="workDuration" min="1" max="90"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Short break (min)</label>
                                <input type="number" x-model.number="shortBreakDuration" min="1" max="30"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Long break (min)</label>
                                <input type="number" x-model.number="longBreakDuration" min="1" max="60"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Today's Sessions Log ─────────────────────────────────────── --}}
            <div class="flex flex-col gap-4">
                {{-- Quick manual save --}}
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Log Session Manually</h3>
                    <form method="POST" action="{{ route('sessions.store') }}" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Type</label>
                                <select name="type"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-2 py-1.5 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                                    @foreach(\App\Enums\StudySessionType::cases() as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-zinc-500 dark:text-zinc-400">Duration (min)</label>
                                <input type="number" name="duration_minutes" value="25" min="1" max="480"
                                    class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-2 py-1.5 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            </div>
                        </div>
                        <select name="course_id"
                            class="w-full rounded-lg border border-zinc-200 bg-zinc-50 px-2 py-1.5 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">No course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="w-full rounded-lg bg-zinc-800 py-2 text-sm font-medium text-white transition-colors hover:bg-zinc-700 dark:bg-zinc-700 dark:hover:bg-zinc-600">
                            Save Session
                        </button>
                    </form>
                </div>

                {{-- Today's log --}}
                <div class="flex-1 rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-100 px-5 py-3 dark:border-zinc-800">
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Today's Sessions</h3>
                    </div>

                    @if($todaySessions->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10">
                            <span class="material-symbols-outlined mb-2 text-3xl text-zinc-300 dark:text-zinc-700">timer</span>
                            <p class="text-sm text-zinc-400 dark:text-zinc-600">No sessions yet today</p>
                        </div>
                    @else
                        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($todaySessions as $session)
                                <li class="flex items-center justify-between gap-3 px-5 py-3">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                                {{ $session->type->label() }}
                                            </span>
                                            @if($session->course)
                                                <span class="rounded-full border border-zinc-200 bg-zinc-100 px-2 py-0.5 text-[10px] text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400">
                                                    {{ $session->course->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-zinc-400 dark:text-zinc-500">
                                            {{ $session->started_at->format('H:i') }}
                                            · {{ $session->duration_minutes }} min
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('sessions.destroy', $session) }}"
                                          onsubmit="return confirm('Delete this session?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-300 transition-colors hover:bg-red-50 hover:text-red-500 dark:text-zinc-600 dark:hover:bg-red-950 dark:hover:text-red-400">
                                            <span class="material-symbols-outlined text-base leading-none">close</span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form submitted by Alpine when a Pomodoro timer completes --}}
    <form id="sess-form" method="POST" action="{{ route('sessions.store') }}" class="hidden">
        @csrf
        <input type="hidden" id="sess-type" name="type" value="pomodoro">
        <input type="hidden" id="sess-duration" name="duration_minutes" value="25">
        <input type="hidden" id="sess-course" name="course_id" value="">
        <input type="hidden" id="sess-task" name="task_id" value="">
    </form>
</x-layouts::app>
