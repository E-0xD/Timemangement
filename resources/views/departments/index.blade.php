<x-layouts::app :title="__('Academic')">
    <div
        x-data="{
            tab: 'departments',
            showCreateDept: false,
            showEditDept: false,
            editDept: { id: null, name: '', code: '', description: '' },

            showCreateCourse: false,
            showEditCourse: false,
            editCourse: { id: null, name: '', code: '', lecturer: '', color: '#6366f1', department_id: '', semester_id: '' },

            showCreateSemester: false,
            showEditSemester: false,
            editSemester: { id: null, name: '', start_date: '', end_date: '' },

            openEditDept(dept) {
                this.editDept = { id: dept.id, name: dept.name, code: dept.code, description: dept.description || '' };
                this.showEditDept = true;
            },
            openEditCourse(course) {
                this.editCourse = {
                    id: course.id, name: course.name, code: course.code,
                    lecturer: course.lecturer || '', color: course.color || '#6366f1',
                    department_id: course.department_id, semester_id: course.semester_id || ''
                };
                this.showEditCourse = true;
            },
            openEditSemester(sem) {
                this.editSemester = { id: sem.id, name: sem.name, start_date: sem.start_date, end_date: sem.end_date };
                this.showEditSemester = true;
            },
        }"
        class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4 md:p-6"
    >

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Academic</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Manage your departments, semesters and courses</p>
            </div>
            <div class="flex items-center gap-2">
                <button x-show="tab === 'departments'" @click="showCreateDept = true"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                    <span class="material-symbols-outlined text-base leading-none">add</span>
                    New Department
                </button>
                <button x-show="tab === 'semesters'" @click="showCreateSemester = true"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                    <span class="material-symbols-outlined text-base leading-none">add</span>
                    New Semester
                </button>
                <button x-show="tab === 'courses'" @click="showCreateCourse = true"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                    <span class="material-symbols-outlined text-base leading-none">add</span>
                    New Course
                </button>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabs --}}
        <div class="flex gap-1 rounded-lg border border-zinc-200 bg-zinc-50 p-1 dark:border-zinc-700 dark:bg-zinc-800/50">
            <button @click="tab = 'departments'"
                :class="tab === 'departments' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 shadow-sm font-semibold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-md px-4 py-2 text-sm transition-all">
                <span class="material-symbols-outlined text-base leading-none">account_balance</span>
                Departments
                <span class="rounded-full bg-zinc-200 px-1.5 py-0.5 text-[10px] font-medium dark:bg-zinc-600">{{ $departments->count() }}</span>
            </button>
            <button @click="tab = 'semesters'"
                :class="tab === 'semesters' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 shadow-sm font-semibold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-md px-4 py-2 text-sm transition-all">
                <span class="material-symbols-outlined text-base leading-none">date_range</span>
                Semesters
                <span class="rounded-full bg-zinc-200 px-1.5 py-0.5 text-[10px] font-medium dark:bg-zinc-600">{{ $semesters->count() }}</span>
            </button>
            <button @click="tab = 'courses'"
                :class="tab === 'courses' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-zinc-100 shadow-sm font-semibold' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700'"
                class="flex flex-1 items-center justify-center gap-2 rounded-md px-4 py-2 text-sm transition-all">
                <span class="material-symbols-outlined text-base leading-none">menu_book</span>
                Courses
                <span class="rounded-full bg-zinc-200 px-1.5 py-0.5 text-[10px] font-medium dark:bg-zinc-600">{{ $courses->count() }}</span>
            </button>
        </div>

        {{-- ── Departments Tab ─────────────────────────────────────────── --}}
        <div x-show="tab === 'departments'" class="space-y-3">
            @forelse($departments as $dept)
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-3 px-5 py-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $dept->name }}</span>
                                <span class="rounded bg-indigo-50 px-1.5 py-0.5 text-[11px] font-medium text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">{{ $dept->code }}</span>
                            </div>
                            @if($dept->description)
                                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $dept->description }}</p>
                            @endif
                            @if($dept->courses->count())
                                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">{{ $dept->courses->count() }} course{{ $dept->courses->count() === 1 ? '' : 's' }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                @click="openEditDept({{ json_encode(['id' => $dept->id, 'name' => $dept->name, 'code' => $dept->code, 'description' => $dept->description]) }})"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800">
                                <span class="material-symbols-outlined text-base leading-none">edit</span>
                            </button>
                            <form method="POST" action="{{ route('academic.departments.destroy', $dept) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($dept->name) }}? This will also remove its courses.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/40">
                                    <span class="material-symbols-outlined text-base leading-none">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-14 text-center dark:border-zinc-700 dark:bg-zinc-900/50">
                    <span class="material-symbols-outlined mb-3 text-zinc-300 dark:text-zinc-700" style="font-size: 40px">account_balance</span>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">No departments yet</p>
                    <button @click="showCreateDept = true"
                        class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-base leading-none">add</span>
                        Add Department
                    </button>
                </div>
            @endforelse
        </div>

        {{-- ── Semesters Tab ───────────────────────────────────────────── --}}
        <div x-show="tab === 'semesters'" class="space-y-3">
            @forelse($semesters as $sem)
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-3 px-5 py-4">
                        <div class="min-w-0">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $sem->name }}</span>
                            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ \Carbon\Carbon::parse($sem->start_date)->format('M j, Y') }}
                                – {{ \Carbon\Carbon::parse($sem->end_date)->format('M j, Y') }}
                            </p>
                            @if($sem->courses->count())
                                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-600">{{ $sem->courses->count() }} course{{ $sem->courses->count() === 1 ? '' : 's' }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                @click="openEditSemester({{ json_encode(['id' => $sem->id, 'name' => $sem->name, 'start_date' => $sem->start_date, 'end_date' => $sem->end_date]) }})"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800">
                                <span class="material-symbols-outlined text-base leading-none">edit</span>
                            </button>
                            <form method="POST" action="{{ route('academic.semesters.destroy', $sem) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($sem->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/40">
                                    <span class="material-symbols-outlined text-base leading-none">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-14 text-center dark:border-zinc-700 dark:bg-zinc-900/50">
                    <span class="material-symbols-outlined mb-3 text-zinc-300 dark:text-zinc-700" style="font-size: 40px">date_range</span>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">No semesters yet</p>
                    <button @click="showCreateSemester = true"
                        class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-base leading-none">add</span>
                        Add Semester
                    </button>
                </div>
            @endforelse
        </div>

        {{-- ── Courses Tab ─────────────────────────────────────────────── --}}
        <div x-show="tab === 'courses'" class="space-y-3">
            @forelse($courses as $course)
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-3 px-5 py-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-9 w-9 shrink-0 rounded-lg" style="background-color: {{ $course->color ?? '#6366f1' }}"></div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $course->name }}</span>
                                    <span class="rounded bg-zinc-100 px-1.5 py-0.5 text-[11px] font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">{{ $course->code }}</span>
                                </div>
                                <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $course->department?->name ?? '—' }}
                                    @if($course->semester)
                                        · {{ $course->semester->name }}
                                    @endif
                                    @if($course->lecturer)
                                        · {{ $course->lecturer }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                @click="openEditCourse({{ json_encode(['id' => $course->id, 'name' => $course->name, 'code' => $course->code, 'lecturer' => $course->lecturer, 'color' => $course->color ?? '#6366f1', 'department_id' => $course->department_id, 'semester_id' => $course->semester_id]) }})"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800">
                                <span class="material-symbols-outlined text-base leading-none">edit</span>
                            </button>
                            <form method="POST" action="{{ route('academic.courses.destroy', $course) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($course->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/40">
                                    <span class="material-symbols-outlined text-base leading-none">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50 py-14 text-center dark:border-zinc-700 dark:bg-zinc-900/50">
                    <span class="material-symbols-outlined mb-3 text-zinc-300 dark:text-zinc-700" style="font-size: 40px">menu_book</span>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">No courses yet</p>
                    <button @click="showCreateCourse = true"
                        class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-base leading-none">add</span>
                        Add Course
                    </button>
                </div>
            @endforelse
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             MODALS
        ════════════════════════════════════════════════════════════════ --}}

        {{-- Create Department --}}
        <div x-show="showCreateDept" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showCreateDept = false" style="display: none;">
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">New Department</h3>
                    <button @click="showCreateDept = false" class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"><span class="material-symbols-outlined text-base leading-none">close</span></button>
                </div>
                <form method="POST" action="{{ route('academic.departments.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required maxlength="255"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" required maxlength="50"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Description</label>
                        <textarea name="description" rows="2" maxlength="500"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Create</button>
                        <button type="button" @click="showCreateDept = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Department --}}
        <div x-show="showEditDept" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showEditDept = false" style="display: none;">
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Edit Department</h3>
                    <button @click="showEditDept = false" class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"><span class="material-symbols-outlined text-base leading-none">close</span></button>
                </div>
                <form method="POST" :action="`{{ url('academic/departments') }}/${editDept.id}`" class="space-y-4 p-5">
                    @csrf @method('PATCH')
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editDept.name" required maxlength="255"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" x-model="editDept.code" required maxlength="50"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Description</label>
                        <textarea name="description" rows="2" maxlength="500" x-model="editDept.description"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Save Changes</button>
                        <button type="button" @click="showEditDept = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Create Semester --}}
        <div x-show="showCreateSemester" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showCreateSemester = false" style="display: none;">
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">New Semester</h3>
                    <button @click="showCreateSemester = false" class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"><span class="material-symbols-outlined text-base leading-none">close</span></button>
                </div>
                <form method="POST" action="{{ route('academic.semesters.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required maxlength="255"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">End Date <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" required
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Create</button>
                        <button type="button" @click="showCreateSemester = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Semester --}}
        <div x-show="showEditSemester" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showEditSemester = false" style="display: none;">
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Edit Semester</h3>
                    <button @click="showEditSemester = false" class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"><span class="material-symbols-outlined text-base leading-none">close</span></button>
                </div>
                <form method="POST" :action="`{{ url('academic/semesters') }}/${editSemester.id}`" class="space-y-4 p-5">
                    @csrf @method('PATCH')
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="editSemester.name" required maxlength="255"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" x-model="editSemester.start_date" required
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">End Date <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" x-model="editSemester.end_date" required
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Save Changes</button>
                        <button type="button" @click="showEditSemester = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Create Course --}}
        <div x-show="showCreateCourse" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showCreateCourse = false" style="display: none;">
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">New Course</h3>
                    <button @click="showCreateCourse = false" class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"><span class="material-symbols-outlined text-base leading-none">close</span></button>
                </div>
                <form method="POST" action="{{ route('academic.courses.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Course Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required maxlength="255"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" required maxlength="50"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Colour</label>
                            <input type="color" name="color" value="#6366f1"
                                class="h-10 w-full cursor-pointer rounded-lg border border-zinc-300 bg-white p-1 dark:border-zinc-600 dark:bg-zinc-800">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Lecturer</label>
                        <input type="text" name="lecturer" maxlength="255"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Department <span class="text-red-500">*</span></label>
                        <select name="department_id" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">— Select department —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Semester</label>
                        <select name="semester_id"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">— No semester —</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Create</button>
                        <button type="button" @click="showCreateCourse = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Course --}}
        <div x-show="showEditCourse" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showEditCourse = false" style="display: none;">
            <div class="w-full max-w-md rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Edit Course</h3>
                    <button @click="showEditCourse = false" class="inline-flex h-7 w-7 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800"><span class="material-symbols-outlined text-base leading-none">close</span></button>
                </div>
                <form method="POST" :action="`{{ url('academic/courses') }}/${editCourse.id}`" class="space-y-4 p-5">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Course Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="editCourse.name" required maxlength="255"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" x-model="editCourse.code" required maxlength="50"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Colour</label>
                            <input type="color" name="color" x-model="editCourse.color"
                                class="h-10 w-full cursor-pointer rounded-lg border border-zinc-300 bg-white p-1 dark:border-zinc-600 dark:bg-zinc-800">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Lecturer</label>
                        <input type="text" name="lecturer" x-model="editCourse.lecturer" maxlength="255"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Department <span class="text-red-500">*</span></label>
                        <select name="department_id" x-model="editCourse.department_id" required
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">— Select department —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-zinc-700 dark:text-zinc-300">Semester</label>
                        <select name="semester_id" x-model="editCourse.semester_id"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">— No semester —</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700">Save Changes</button>
                        <button type="button" @click="showEditCourse = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts::app>
