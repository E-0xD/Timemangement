<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300">Admin</a>
                    <span>/</span>
                    <span>Users</span>
                </div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Manage Users</h1>
            </div>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email…"
                   class="flex-1 min-w-48 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
            <select name="role"
                    class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                <option value="">All roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                        {{ ucfirst($role->value) }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                Search
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" wire:navigate
                   class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-500 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800">
                    Clear
                </a>
            @endif
        </form>

        {{-- Table --}}
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 text-left">
                        <th class="px-5 py-3 text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">User</th>
                        <th class="px-5 py-3 text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Role</th>
                        <th class="px-5 py-3 text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400 hidden md:table-cell">XP</th>
                        <th class="px-5 py-3 text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400 hidden lg:table-cell">Joined</th>
                        <th class="px-5 py-3 text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                                        <p class="text-xs text-zinc-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-400' => $user->isAdmin(),
                                    'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400' => !$user->isAdmin(),
                                ])>{{ $user->role->value }}</span>
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell text-zinc-700 dark:text-zinc-300 font-medium">
                                {{ number_format($user->xp_points) }}
                            </td>
                            <td class="px-5 py-3 hidden lg:table-cell text-xs text-zinc-400">
                                {{ $user->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" wire:navigate
                                       class="text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">View</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" wire:navigate
                                       class="text-xs font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400">Edit</a>
                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete {{ addslashes($user->name) }}?')"
                                                    class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $users->links() }}</div>

    </div>
</x-layouts::app>
