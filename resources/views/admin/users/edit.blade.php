<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.users.index') }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300">Users</a>
            <span>/</span>
            <a href="{{ route('admin.users.show', $user) }}" wire:navigate class="hover:text-zinc-700 dark:hover:text-zinc-300">{{ $user->name }}</a>
            <span>/</span>
            <span class="text-zinc-700 dark:text-zinc-200">Edit</span>
        </div>

        <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Edit User</h1>

        <div class="max-w-2xl">
            <form id="update-user-form" method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')

                <div class="space-y-5 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Role <span class="text-red-500">*</span></label>
                            <select name="role"
                                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                @foreach(\App\Enums\UserRole::cases() as $role)
                                    <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                                        {{ ucfirst($role->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Department</label>
                            <select name="department_id"
                                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="">None</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">School</label>
                        <input type="text" name="school" value="{{ old('school', $user->school) }}" placeholder="University name"
                               class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Bio</label>
                        <textarea name="bio" rows="3" maxlength="500" placeholder="Short bio…"
                                  class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-4 flex gap-3">
                    <button type="submit" form="update-user-form"
                            class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.show', $user) }}" wire:navigate
                       class="rounded-lg border border-zinc-200 px-5 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-layouts::app>
