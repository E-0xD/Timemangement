<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('groups.index') }}" wire:navigate
                   class="inline-flex h-8 w-8 items-center justify-center rounded-md text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    <span class="material-symbols-outlined text-xl leading-none">arrow_back</span>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $group->name }}</h1>
                        @if($group->is_public)
                            <span class="inline-flex items-center gap-1 rounded-full border border-zinc-200 px-2 py-0.5 text-[11px] text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                                <span class="material-symbols-outlined text-xs leading-none">public</span>Public
                            </span>
                        @endif
                    </div>
                    @if($group->description)
                        <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $group->description }}</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($myRole?->canManageMembers())
                    <a href="{{ route('groups.edit', $group) }}" wire:navigate
                       class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 px-3 py-1.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <span class="material-symbols-outlined text-base leading-none">settings</span>
                        Settings
                    </a>
                @endif
                @if($isMember && $myRole !== \App\Enums\GroupRole::Owner)
                    <form method="POST" action="{{ route('groups.leave', $group) }}">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Leave this group?')"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 px-3 py-1.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            <span class="material-symbols-outlined text-base leading-none">logout</span>
                            Leave
                        </button>
                    </form>
                @endif
                @if(!$isMember)
                    <form method="POST" action="{{ route('groups.join') }}">
                        @csrf
                        <input type="hidden" name="invite_code" value="{{ $group->invite_code }}" />
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            <span class="material-symbols-outlined text-base leading-none">add</span>
                            Join Group
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        <div class="grid gap-6 lg:grid-cols-[1fr_280px]">

            {{-- ── Message board ──────────────────────────────────────────── --}}
            <div class="flex flex-col gap-4">

                {{-- Invite code (members only) --}}
                @if($isMember && $group->invite_code)
                    <div class="flex flex-wrap items-center gap-3 rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 dark:border-zinc-700 dark:bg-zinc-800/50">
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Invite code:</span>
                        <span class="rounded-md border border-zinc-200 bg-white px-2.5 py-1 font-mono text-sm font-semibold tracking-widest text-zinc-800 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100">
                            {{ $group->invite_code }}
                        </span>
                        <span class="text-xs text-zinc-400 dark:text-zinc-600">Share this to invite others</span>
                    </div>
                @endif

                {{-- Post message --}}
                @if($isMember)
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <form method="POST" action="{{ route('groups.messages.store', $group) }}" class="flex flex-col gap-3">
                            @csrf
                            <textarea name="body" rows="3" maxlength="2000" placeholder="Write a message..."
                                      class="w-full resize-none rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                            @error('body') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                    <span class="material-symbols-outlined text-base leading-none">send</span>
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Messages --}}
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-100 px-5 py-3 dark:border-zinc-800">
                        <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Discussion</h2>
                    </div>

                    @if($messages->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12">
                            <span class="material-symbols-outlined mb-2 text-4xl text-zinc-300 dark:text-zinc-700">chat_bubble</span>
                            <p class="text-sm text-zinc-400 dark:text-zinc-600">No messages yet. Start the conversation!</p>
                        </div>
                    @else
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($messages as $msg)
                                <div class="flex gap-3 px-5 py-4">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-400">
                                        {{ strtoupper(substr($msg->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-baseline gap-2 mb-1">
                                            <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $msg->user->name }}</span>
                                            <span class="text-xs text-zinc-400 dark:text-zinc-600">{{ $msg->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                                    </div>
                                    @if($msg->user_id === Auth::id() || $myRole?->canManageMembers())
                                        <form method="POST" action="{{ route('groups.messages.destroy', [$group, $msg]) }}" class="shrink-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this message?')"
                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-300 hover:bg-zinc-100 hover:text-red-500 dark:hover:bg-zinc-800">
                                                <span class="material-symbols-outlined text-base leading-none">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if($messages->hasPages())
                            <div class="border-t border-zinc-100 px-5 py-3 dark:border-zinc-800">
                                {{ $messages->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- ── Members sidebar ────────────────────────────────────────── --}}
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 h-fit">
                <div class="border-b border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Members <span class="ml-1 text-zinc-400">({{ $members->count() }})</span></h2>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($members as $member)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-bold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $member->user->name }}</p>
                                <p class="text-xs capitalize text-zinc-400 dark:text-zinc-600">{{ $member->role->label() }}</p>
                            </div>
                            @if($myRole?->canManageMembers() && $member->user_id !== Auth::id() && $member->role !== \App\Enums\GroupRole::Owner)
                                <form method="POST" action="{{ route('groups.members.destroy', [$group, $member->user]) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Remove {{ $member->user->name }} from the group?')"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded text-zinc-300 hover:text-red-500 dark:hover:text-red-400">
                                        <span class="material-symbols-outlined text-sm leading-none">person_remove</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</x-layouts::app>
