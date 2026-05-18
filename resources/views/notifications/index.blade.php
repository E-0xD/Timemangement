<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Notifications</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                    @if($unreadCount > 0)
                        You have <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $unreadCount }}</span> unread notification{{ $unreadCount === 1 ? '' : 's' }}.
                    @else
                        You're all caught up.
                    @endif
                </p>
            </div>

            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
                    >
                        <span class="material-symbols-outlined text-sm leading-none">done_all</span>
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifications list --}}
        @if($notifications->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-16 dark:border-zinc-700 dark:bg-zinc-900">
                <span class="material-symbols-outlined mb-3 text-5xl text-zinc-300 dark:text-zinc-700">notifications_none</span>
                <p class="mb-1 text-sm font-medium text-zinc-600 dark:text-zinc-400">No notifications yet</p>
                <p class="text-xs text-zinc-400 dark:text-zinc-600">We'll notify you about deadlines, achievements, and more.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($notifications as $notification)
                        @php
                            $iconMap = [
                                'deadline_reminder'    => 'alarm',
                                'assignment_reminder'  => 'assignment',
                                'exam_reminder'        => 'quiz',
                                'study_reminder'       => 'menu_book',
                                'class_reminder'       => 'school',
                                'goal_reminder'        => 'flag',
                                'daily_summary'        => 'today',
                                'weekly_summary'       => 'date_range',
                                'productivity_reminder'=> 'trending_up',
                                'achievement'          => 'emoji_events',
                                'system'               => 'info',
                            ];
                            $icon = $iconMap[$notification->type->value] ?? 'notifications';
                        @endphp
                        <li class="flex items-start gap-4 px-5 py-4 {{ $notification->is_read ? '' : 'bg-indigo-50 dark:bg-indigo-950/30' }}">
                            {{-- Icon --}}
                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $notification->is_read ? 'bg-zinc-100 dark:bg-zinc-800' : 'bg-indigo-100 dark:bg-indigo-900' }}">
                                <span class="material-symbols-outlined text-lg {{ $notification->is_read ? 'text-zinc-400 dark:text-zinc-500' : 'text-indigo-600 dark:text-indigo-400' }}">{{ $icon }}</span>
                            </div>

                            {{-- Content --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-medium {{ $notification->is_read ? 'text-zinc-700 dark:text-zinc-300' : 'text-zinc-900 dark:text-zinc-100' }}">
                                            @if(!$notification->is_read)
                                                <span class="mr-1.5 inline-block h-2 w-2 rounded-full bg-indigo-500 align-middle"></span>
                                            @endif
                                            {{ $notification->title }}
                                        </p>
                                        @if($notification->body)
                                            <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">{{ $notification->body }}</p>
                                        @endif
                                        <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">
                                            {{ $notification->created_at->diffForHumans() }}
                                            &middot; {{ $notification->type->label() }}
                                        </p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex shrink-0 items-center gap-1">
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    title="Mark as read"
                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-700 dark:hover:text-zinc-300"
                                                >
                                                    <span class="material-symbols-outlined text-base leading-none">done</span>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}"
                                              onsubmit="return confirm('Delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                title="Delete"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950 dark:hover:text-red-400"
                                            >
                                                <span class="material-symbols-outlined text-base leading-none">close</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
                <div class="flex justify-center">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif

    </div>
</x-layouts::app>
