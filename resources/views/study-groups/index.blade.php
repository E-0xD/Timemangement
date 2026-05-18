<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Study Groups</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Collaborate with fellow students</p>
            </div>
            <a href="{{ route('groups.create') }}" wire:navigate
               class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <span class="material-symbols-outlined text-base leading-none">add</span>
                New Group
            </a>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession
        @session('info')
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-400">{{ $value }}</div>
        @endsession

        {{-- Join by code --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Join a Group</h2>
            <form method="POST" action="{{ route('groups.join') }}" class="flex flex-wrap gap-3">
                @csrf
                <div class="flex-1 min-w-48">
                    <input type="text" name="invite_code" placeholder="Enter invite code (e.g. AB12CD34)" maxlength="12"
                           value="{{ old('invite_code') }}"
                           class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm uppercase tracking-widest text-zinc-900 placeholder:normal-case placeholder:tracking-normal focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                    @error('invite_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                    Join
                </button>
            </form>
        </div>

        {{-- My Groups --}}
        <div>
            <h2 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">My Groups</h2>
            @if($myGroups->isEmpty())
                <p class="text-sm text-zinc-400 dark:text-zinc-600">You haven't joined any groups yet.</p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($myGroups as $group)
                        <a href="{{ route('groups.show', $group) }}" wire:navigate
                           class="flex flex-col rounded-xl border border-zinc-200 bg-white p-5 shadow-sm transition-colors hover:border-indigo-300 hover:bg-indigo-50/30 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/20">
                            <div class="mb-2 flex items-start justify-between gap-2">
                                <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $group->name }}</h3>
                                @if($group->owner_id === Auth::id())
                                    <span class="shrink-0 rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-400">Owner</span>
                                @else
                                    <span class="shrink-0 rounded-full border border-zinc-200 px-2 py-0.5 text-[10px] font-medium text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">Member</span>
                                @endif
                            </div>
                            @if($group->description)
                                <p class="mb-3 text-xs text-zinc-500 line-clamp-2 dark:text-zinc-400">{{ $group->description }}</p>
                            @endif
                            <div class="mt-auto flex items-center gap-3 text-xs text-zinc-400 dark:text-zinc-600">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm leading-none">group</span>
                                    {{ $group->members_count }} {{ Str::plural('member', $group->members_count) }}
                                </span>
                                @if($group->is_public)
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm leading-none">public</span>
                                        Public
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Public Groups --}}
        @if($publicGroups->isNotEmpty())
            <div>
                <h2 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Discover Public Groups</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($publicGroups as $group)
                        <div class="flex flex-col rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                            <h3 class="mb-1 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $group->name }}</h3>
                            @if($group->description)
                                <p class="mb-3 text-xs text-zinc-500 line-clamp-2 dark:text-zinc-400">{{ $group->description }}</p>
                            @endif
                            <div class="mt-auto flex items-center justify-between pt-2">
                                <span class="flex items-center gap-1 text-xs text-zinc-400 dark:text-zinc-600">
                                    <span class="material-symbols-outlined text-sm leading-none">group</span>
                                    {{ $group->members_count }} {{ Str::plural('member', $group->members_count) }}
                                </span>
                                <form method="POST" action="{{ route('groups.join') }}">
                                    @csrf
                                    <input type="hidden" name="invite_code" value="{{ $group->invite_code }}" />
                                    <button type="submit"
                                            class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">
                                        Join
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-layouts::app>
