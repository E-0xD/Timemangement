<x-layouts::app>
    <div
        x-data="{
            workDuration: parseInt(localStorage.getItem('focus_work') || '25'),
            shortBreakDuration: parseInt(localStorage.getItem('focus_short') || '5'),
            longBreakDuration: parseInt(localStorage.getItem('focus_long') || '15'),
            phase: 'idle',
            remaining: 0,
            total: 1,
            running: false,
            interval: null,
            pomodorosDone: 0,
            showLogModal: false,
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

            startPhase(phase) {
                clearInterval(this.interval);
                this.phase = phase;
                if (phase === 'focus')            this.remaining = this.total = this.workDuration * 60;
                else if (phase === 'short_break') this.remaining = this.total = this.shortBreakDuration * 60;
                else                              this.remaining = this.total = this.longBreakDuration * 60;
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

            pause()  { clearInterval(this.interval); this.running = false; },
            resume() { if (this.remaining > 0) { this.running = true; this.tick(); } },
            reset()  {
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
        class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6"
    >

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Focus Timer</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                    Studied <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ floor($todayMinutes / 60) }}h {{ $todayMinutes % 60 }}m</span> today
                    &middot; {{ $todaySessions->count() }} session{{ $todaySessions->count() === 1 ? '' : 's' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button
                    @click="showLogModal = true"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <span class="material-symbols-outlined text-base leading-none">edit_note</span>
                    Log Session
                </button>
                <a href="{{ route('focus.settings') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700">
                    <span class="material-symbols-outlined text-base leading-none">tune</span>
                    Settings
                </a>
            </div>
        </div>

        @if(session('session_saved'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                Session logged successfully.
            </div>
        @endif

        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 lg:grid-cols-[1fr_360px]">

            {{-- Timer Panel --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

                {{-- Phase tabs --}}
                <div class="mb-6 flex gap-2 rounded-lg bg-zinc-100 p-1 dark:bg-zinc-800">
                    <button @click="startPhase('focus')"
                        :class="phase === 'focus' ? 'bg-white shadow-sm dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700'"
                        class="flex-1 rounded-md py-1.5 text-sm transition-all">
                        Focus
                    </button>
                    <button @click="startPhase('short_break')"
                        :class="phase === 'short_break' ? 'bg-white shadow-sm dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700'"
                        class="flex-1 rounded-md py-1.5 text-sm transition-all">
                        Short Break
                    </button>
                    <button @click="startPhase('long_break')"
                        :class="phase === 'long_break' ? 'bg-white shadow-sm dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 font-medium' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700'"
                        class="flex-1 rounded-md py-1.5 text-sm transition-all">
                        Long Break
                    </button>
                </div>

                {{-- SVG Ring Timer --}}
                <div class="flex flex-col items-center gap-6">
                    <div class="relative flex items-center justify-center">
                        <svg viewBox="0 0 100 100" class="h-56 w-56 -rotate-90">
                            <circle cx="50" cy="50" r="45"
                                class="fill-none stroke-zinc-100 dark:stroke-zinc-800"
                                stroke-width="6" />
                            <circle cx="50" cy="50" r="45"
                                fill="none"
                                stroke-width="6"
                                stroke-linecap="round"
                                stroke-dasharray="283"
                                :stroke-dashoffset="dashOffset"
                                :stroke="phaseColour"
                                style="transition: stroke-dashoffset 0.9s linear, stroke 0.3s;" />
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-5xl font-bold tabular-nums text-zinc-900 dark:text-zinc-100"
                                x-text="phase === 'idle' ? '--:--' : displayTime"></span>
                            <span class="mt-1.5 text-xs font-semibold uppercase tracking-widest"
                                :style="`color: ${phaseColour}`"
                                x-text="phaseLabel"></span>
                            <span class="mt-1 text-xs text-zinc-400 dark:text-zinc-500"
                                x-show="pomodorosDone > 0"
                                x-text="pomodorosDone + ' done today'"></span>
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div class="flex items-center gap-3">
                        <button x-show="phase === 'idle'" @click="startPhase('focus')"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-7 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700">
                            <span class="material-symbols-outlined text-base leading-none">play_arrow</span>
                            Start Focus
                        </button>
                        <button x-show="running" @click="pause()"
                            class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-7 py-2.5 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                            <span class="material-symbols-outlined text-base leading-none">pause</span>
                            Pause
                        </button>
                        <button x-show="!running && phase !== 'idle' && remaining > 0" @click="resume()"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-7 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700">
                            <span class="material-symbols-outlined text-base leading-none">play_arrow</span>
                            Resume
                        </button>
                        <button x-show="phase !== 'idle'" @click="reset()"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 text-zinc-400 transition-colors hover:bg-zinc-50 hover:text-zinc-600 dark:border-zinc-700 dark:hover:bg-zinc-800">
                            <span class="material-symbols-outlined text-base leading-none">refresh</span>
                        </button>
                    </div>
                </div>

                {{-- Session context --}}
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

            {{-- Today's Sessions Log --}}
            <div class="flex flex-col rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="border-b border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Today's Sessions</h3>
                </div>

                @if($todaySessions->isEmpty())
                    <div class="flex flex-1 flex-col items-center justify-center px-6 py-16 text-center">
                        <span class="material-symbols-outlined mb-3 text-zinc-300 dark:text-zinc-700" style="font-size:40px">timer</span>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">No sessions yet today</p>
                        <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">Start the timer or log a session manually</p>
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

        {{-- Log Session Modal --}}
        <div
            x-show="showLogModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            @click.self="showLogModal = false"
            style="display: none;"
        >
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Log Session Manually</h3>
                    <button @click="showLogModal = false"
                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800">
                        <span class="material-symbols-outlined text-base leading-none">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('sessions.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Type</label>
                            <select name="type"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                @foreach(\App\Enums\StudySessionType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Duration (min)</label>
                            <input type="number" name="duration_minutes" value="25" min="1" max="480"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Course (optional)</label>
                        <select name="course_id"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">— No course —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Task (optional)</label>
                        <select name="task_id"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">— No task —</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit"
                            class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                            Save Session
                        </button>
                        <button type="button" @click="showLogModal = false"
                            class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Cancel
                        </button>
                    </div>
                </form>
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
