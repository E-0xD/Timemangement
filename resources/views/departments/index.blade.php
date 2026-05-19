<x-layouts::app :title="__('Departments & Semesters')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-slate-900 dark:text-zinc-100">Departments &amp; Semesters</h1>
                <p class="mt-0.5 text-sm text-slate-500 dark:text-zinc-400">Manage your departments, semesters, and courses</p>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">
                <span class="material-symbols-outlined text-base leading-none">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- ── Departments ─────────────────────────────────────────── --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-zinc-100">Departments</h2>
                    <button onclick="document.getElementById('dept-form').classList.toggle('hidden')"
                            class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-base leading-none">add</span>
                        New
                    </button>
                </div>

                {{-- Create Department Form --}}
                <div id="dept-form" class="hidden rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <form method="POST" action="{{ route('departments.storeDepartment') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Computer Science"
                                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">Code</label>
                            <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. CS"
                                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">Description <span class="text-slate-400 font-normal">(optional)</span></label>
                            <textarea name="description" rows="2" maxlength="500" placeholder="Optional…"
                                      class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100">{{ old('description') }}</textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Create</button>
                            <button type="button" onclick="document.getElementById('dept-form').classList.add('hidden')"
                                    class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                        </div>
                    </form>
                </div>

                {{-- Departments List --}}
                @if($departments->isEmpty())
                    <div class="rounded-lg border border-dashed border-zinc-300 p-8 text-center dark:border-zinc-600">
                        <span class="material-symbols-outlined mb-2 text-4xl text-zinc-300 dark:text-zinc-600">corporate_fare</span>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">No departments yet. Create one to get started.</p>
                    </div>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($departments as $dept)
                            <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-900 dark:text-zinc-100 truncate">{{ $dept->name }}</p>
                                        <p class="text-sm text-slate-500 dark:text-zinc-400">{{ $dept->code }}</p>
                                        @if($dept->description)
                                            <p class="mt-1 text-xs text-slate-500 dark:text-zinc-400">{{ $dept->description }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('departments.destroyDepartment', $dept) }}"
                                          onsubmit="return confirm('Delete this department and all its courses?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-zinc-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                            <span class="material-symbols-outlined text-xl leading-none">delete</span>
                                        </button>
                                    </form>
                                </div>

                                {{-- Courses in this department --}}
                                <div class="mt-3 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-slate-500 dark:text-zinc-400">{{ $dept->courses->count() }} course{{ $dept->courses->count() === 1 ? '' : 's' }}</p>
                                        <button onclick="document.getElementById('course-form-{{ $dept->id }}').classList.toggle('hidden')"
                                                class="inline-flex items-center gap-0.5 text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">
                                            <span class="material-symbols-outlined text-sm leading-none">add</span> Add course
                                        </button>
                                    </div>

                                    {{-- Add Course Form --}}
                                    <div id="course-form-{{ $dept->id }}" class="hidden mb-3 rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800">
                                        <form method="POST" action="{{ route('departments.storeCourse') }}" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="department_id" value="{{ $dept->id }}" />
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 dark:text-zinc-300 mb-1">Course Name</label>
                                                    <input type="text" name="name" required placeholder="e.g. Data Structures"
                                                           class="w-full rounded-lg border border-zinc-300 bg-white px-2 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 dark:text-zinc-300 mb-1">Code</label>
                                                    <input type="text" name="code" required placeholder="e.g. CS201"
                                                           class="w-full rounded-lg border border-zinc-300 bg-white px-2 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 dark:text-zinc-300 mb-1">Lecturer <span class="text-slate-400 font-normal">(optional)</span></label>
                                                    <input type="text" name="lecturer" placeholder="Lecturer name"
                                                           class="w-full rounded-lg border border-zinc-300 bg-white px-2 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 dark:text-zinc-300 mb-1">Color</label>
                                                    <input type="color" name="color" value="#6366f1"
                                                           class="h-8 w-full cursor-pointer rounded-lg border border-zinc-300 dark:border-zinc-600" />
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 dark:text-zinc-300 mb-1">Semester <span class="text-slate-400 font-normal">(optional)</span></label>
                                                <select name="semester_id"
                                                        class="w-full rounded-lg border border-zinc-300 bg-white px-2 py-1.5 text-xs text-slate-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100">
                                                    <option value="">— None —</option>
                                                    @foreach($semesters as $sem)
                                                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex gap-2 pt-1">
                                                <button type="submit" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-700">Add</button>
                                                <button type="button" onclick="document.getElementById('course-form-{{ $dept->id }}').classList.add('hidden')"
                                                        class="rounded-lg border border-zinc-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                    @if($dept->courses->isNotEmpty())
                                        <div class="flex flex-col gap-1">
                                            @foreach($dept->courses as $course)
                                                <div class="flex items-center gap-2 rounded-md px-2 py-1.5">
                                                    <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $course->color }}"></span>
                                                    <span class="flex-1 text-sm text-slate-700 dark:text-zinc-200">{{ $course->name }}</span>
                                                    <span class="text-xs text-slate-400 dark:text-zinc-500">{{ $course->code }}</span>
                                                    @if($course->semester)
                                                        <span class="rounded bg-indigo-50 px-1.5 py-0.5 text-xs text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400">{{ $course->semester->name }}</span>
                                                    @endif
                                                    <form method="POST" action="{{ route('departments.destroyCourse', $course) }}"
                                                          onsubmit="return confirm('Delete this course?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-zinc-300 hover:text-red-500 dark:text-zinc-600 dark:hover:text-red-400 transition-colors">
                                                            <span class="material-symbols-outlined text-base leading-none">close</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Semesters ────────────────────────────────────────────── --}}
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-zinc-100">Semesters</h2>
                    <button onclick="document.getElementById('sem-form').classList.toggle('hidden')"
                            class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-base leading-none">add</span>
                        New
                    </button>
                </div>

                {{-- Create Semester Form --}}
                <div id="sem-form" class="hidden rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800">
                    <form method="POST" action="{{ route('departments.storeSemester') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. First Semester 2025/2026"
                                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" required
                                       class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                                @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" required
                                       class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100" />
                                @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Create</button>
                            <button type="button" onclick="document.getElementById('sem-form').classList.add('hidden')"
                                    class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                        </div>
                    </form>
                </div>

                {{-- Semesters List --}}
                @if($semesters->isEmpty())
                    <div class="rounded-lg border border-dashed border-zinc-300 p-8 text-center dark:border-zinc-600">
                        <span class="material-symbols-outlined mb-2 text-4xl text-zinc-300 dark:text-zinc-600">calendar_month</span>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">No semesters yet. Create one to get started.</p>
                    </div>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($semesters as $sem)
                            <div class="rounded-lg border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-900 dark:text-zinc-100 truncate">{{ $sem->name }}</p>
                                        <p class="text-sm text-slate-500 dark:text-zinc-400">
                                            {{ $sem->start_date->format('M d, Y') }} — {{ $sem->end_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('departments.destroySemester', $sem) }}"
                                          onsubmit="return confirm('Delete this semester?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-zinc-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                            <span class="material-symbols-outlined text-xl leading-none">delete</span>
                                        </button>
                                    </form>
                                </div>

                                {{-- Courses in this semester --}}
                                @if($sem->courses->isNotEmpty())
                                    <div class="mt-3 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                                        <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 mb-2">{{ $sem->courses->count() }} course{{ $sem->courses->count() === 1 ? '' : 's' }}</p>
                                        <div class="flex flex-col gap-1">
                                            @foreach($sem->courses as $course)
                                                <div class="flex items-center gap-2 rounded-md px-2 py-1.5">
                                                    <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $course->color }}"></span>
                                                    <span class="flex-1 text-sm text-slate-700 dark:text-zinc-200">{{ $course->name }}</span>
                                                    <span class="text-xs text-slate-400 dark:text-zinc-500">{{ $course->code }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <p class="mt-2 text-xs text-slate-400 dark:text-zinc-500">No courses assigned to this semester yet.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>
