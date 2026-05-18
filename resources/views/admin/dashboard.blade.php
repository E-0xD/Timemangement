<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Admin Dashboard</h1>
            <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">System overview and user statistics</p>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Total Users</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($totalUsers) }}</p>
                <p class="mt-1 text-xs text-emerald-500">+{{ $newThisWeek }} this week</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Sessions Today</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($todaySessions) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">study sessions logged</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Study Groups</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($totalGroups) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">active groups</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Total Tasks</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($totalTasks) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">across all users</p>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="flex gap-3">
            <a href="{{ route('admin.users.index') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <span class="material-symbols-outlined text-base leading-none">manage_accounts</span>
                Manage Users
            </a>
        </div>

        {{-- Recent registrations --}}
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Recent Registrations</h2>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($recentUsers as $user)
                    <div class="flex items-center gap-4 px-5 py-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                            {{ $user->initials() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                            <p class="truncate text-xs text-zinc-400">{{ $user->email }}</p>
                        </div>
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' => $user->isAdmin(),
                            'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' => !$user->isAdmin(),
                        ])>
                            {{ $user->role->value }}
                        </span>
                        <span class="shrink-0 text-xs text-zinc-400 dark:text-zinc-600">{{ $user->created_at->diffForHumans() }}</span>
                        <a href="{{ route('admin.users.show', $user) }}" wire:navigate
                           class="shrink-0 text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">View</a>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-zinc-400">No users yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-layouts::app>
