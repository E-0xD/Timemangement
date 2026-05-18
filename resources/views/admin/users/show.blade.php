<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.users.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300">Users</a>
            <span>/</span>
            <span class="text-zinc-700 dark:text-zinc-200">{{ $user->name }}</span>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Profile card --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 lg:col-span-1">
                <div class="flex flex-col items-center text-center gap-3">
                    @if($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                             class="h-16 w-16 rounded-full object-cover" />
                    @else
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-xl font-bold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                            {{ $user->initials() }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ $user->name }}</h2>
                        <p class="text-sm text-zinc-400">{{ $user->email }}</p>
                    </div>
                    <span @class([
                        'rounded-full px-2.5 py-1 text-xs font-medium',
                        'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' => $user->isAdmin(),
                        'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' => !$user->isAdmin(),
                    ])>{{ ucfirst($user->role->value) }}</span>
                </div>

                <div class="mt-5 space-y-2 text-sm">
                    @if($user->school)
                        <div class="flex justify-between">
                            <span class="text-zinc-500 dark:text-zinc-400">School</span>
                            <span class="text-zinc-900 dark:text-zinc-100">{{ $user->school }}</span>
                        </div>
                    @endif
                    @if($user->department)
                        <div class="flex justify-between">
                            <span class="text-zinc-500 dark:text-zinc-400">Department</span>
                            <span class="text-zinc-900 dark:text-zinc-100">{{ $user->department->name }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">XP Points</span>
                        <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ number_format($user->xp_points) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Study Streak</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $user->study_streak }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500 dark:text-zinc-400">Joined</span>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $user->created_at->format('M j, Y') }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex flex-col gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" wire:navigate
                       class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Edit User
                    </a>
                    @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                                {{ $user->isAdmin() ? 'Demote to Student' : 'Promote to Admin' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Permanently delete this user?')"
                                    class="w-full rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950/30">
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Stats + bio --}}
            <div class="flex flex-col gap-6 lg:col-span-2">

                {{-- Stats grid --}}
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 text-center">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalTasks }}</p>
                        <p class="mt-0.5 text-xs text-zinc-400">Tasks</p>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 text-center">
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $completedTasks }}</p>
                        <p class="mt-0.5 text-xs text-zinc-400">Completed</p>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 text-center">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ round($studyMinutes / 60, 1) }}h</p>
                        <p class="mt-0.5 text-xs text-zinc-400">Study Time</p>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 text-center">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $totalGoals }}</p>
                        <p class="mt-0.5 text-xs text-zinc-400">Goals</p>
                    </div>
                </div>

                {{-- Bio --}}
                @if($user->bio)
                    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="mb-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Bio</h3>
                        <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $user->bio }}</p>
                    </div>
                @endif

                {{-- Courses --}}
                @if($user->courses->isNotEmpty())
                    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <h3 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Courses ({{ $user->courses->count() }})</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->courses as $course)
                                <span class="rounded-full border border-zinc-200 px-2.5 py-0.5 text-xs text-zinc-600 dark:border-zinc-700 dark:text-zinc-400"
                                      style="border-color: {{ $course->color }}; color: {{ $course->color }}">
                                    {{ $course->code ?? $course->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-layouts::app>
