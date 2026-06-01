<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('focus.index') }}"
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 text-zinc-400 transition-colors hover:bg-zinc-50 hover:text-zinc-600 dark:border-zinc-700 dark:hover:bg-zinc-800">
                <span class="material-symbols-outlined text-base leading-none">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Timer Settings</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Customise focus and break durations</p>
            </div>
        </div>

        {{-- Settings card --}}
        <div
            x-data="{
                work: parseInt(localStorage.getItem('focus_work') || '25'),
                short: parseInt(localStorage.getItem('focus_short') || '5'),
                long: parseInt(localStorage.getItem('focus_long') || '15'),
                saved: false,

                save() {
                    localStorage.setItem('focus_work', this.work);
                    localStorage.setItem('focus_short', this.short);
                    localStorage.setItem('focus_long', this.long);
                    this.saved = true;
                    setTimeout(() => { this.saved = false; }, 2000);
                }
            }"
            class="w-full max-w-lg rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
        >
            <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">Pomodoro Durations</h2>
            </div>

            <div class="space-y-5 px-5 py-5">
                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Focus session length
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" x-model.number="work" min="5" max="90" step="5"
                            class="flex-1 accent-indigo-600">
                        <span class="w-16 rounded-lg border border-zinc-200 bg-zinc-50 px-2 py-1.5 text-center text-sm font-medium text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                            x-text="work + ' min'"></span>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Short break length
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" x-model.number="short" min="1" max="30" step="1"
                            class="flex-1 accent-indigo-600">
                        <span class="w-16 rounded-lg border border-zinc-200 bg-zinc-50 px-2 py-1.5 text-center text-sm font-medium text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                            x-text="short + ' min'"></span>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Long break length
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="range" x-model.number="long" min="5" max="60" step="5"
                            class="flex-1 accent-indigo-600">
                        <span class="w-16 rounded-lg border border-zinc-200 bg-zinc-50 px-2 py-1.5 text-center text-sm font-medium text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                            x-text="long + ' min'"></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <p x-show="saved"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="opacity-0 translate-y-1"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   class="flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400">
                    <span class="material-symbols-outlined text-base leading-none">check_circle</span>
                    Saved
                </p>
                <span x-show="!saved"></span>
                <div class="flex gap-2">
                    <a href="{{ route('focus.index') }}"
                       class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        Back to Timer
                    </a>
                    <button @click="save()"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                        Save Settings
                    </button>
                </div>
            </div>
        </div>

        {{-- Info note --}}
        <p class="max-w-lg text-xs text-zinc-400 dark:text-zinc-600">
            Settings are saved in your browser's local storage. Changes take effect the next time you start a timer session.
        </p>

    </div>
</x-layouts::app>
