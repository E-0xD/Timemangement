{{--
    Shared task form fields.
    Props available in scope:
      $task     – optional existing task (for edit)
      $courses  – collection of user's courses
      $subtasksData – JSON-encoded array for Alpine (edit only)
--}}
@php
    $isEdit       = isset($task) && $task->exists;
    $subtasksJson = $isEdit ? json_encode($subtasksData ?? []) : '[]';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    {{-- Left column: main fields --}}
    <div class="lg:col-span-7 flex flex-col gap-5">

        {{-- Title --}}
        <div>
            <flux:field>
                <flux:label>Title <span class="text-red-500">*</span></flux:label>
                <flux:input
                    name="title"
                    :value="old('title', $task->title ?? '')"
                    placeholder="e.g. Write lab report for Chemistry"
                    autofocus
                    required
                />
                <flux:error name="title" />
            </flux:field>
        </div>

        {{-- Description --}}
        <div>
            <flux:field>
                <flux:label>Description</flux:label>
                <flux:textarea
                    name="description"
                    rows="4"
                    placeholder="Add notes, instructions, or details..."
                >{{ old('description', $task->description ?? '') }}</flux:textarea>
                <flux:error name="description" />
            </flux:field>
        </div>

        {{-- Subtasks --}}
        <div
            x-data="{
                subtasks: {{ $subtasksJson }},
                newTitle: '',
                add() {
                    if (this.newTitle.trim()) {
                        this.subtasks.push({ title: this.newTitle.trim(), is_completed: false });
                        this.newTitle = '';
                    }
                },
                remove(i) { this.subtasks.splice(i, 1); }
            }"
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5"
        >
            <h3 class="text-sm font-semibold text-slate-900 dark:text-zinc-100 mb-3">Subtasks</h3>

            {{-- Existing subtasks --}}
            <template x-for="(sub, i) in subtasks" :key="i">
                <div class="flex items-center gap-2 mb-2">
                    @if ($isEdit)
                        <input type="hidden" :name="`subtasks[${i}][is_completed]`" :value="sub.is_completed ? 1 : 0" />
                        <input
                            type="checkbox"
                            x-model="sub.is_completed"
                            class="rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500"
                            title="Mark as complete"
                        />
                    @endif
                    <input
                        type="text"
                        :name="`subtasks[${i}][title]`"
                        x-model="sub.title"
                        required
                        class="flex-1 px-3 py-1.5 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Subtask title"
                    />
                    <button
                        type="button"
                        @click="remove(i)"
                        class="p-1 text-slate-400 dark:text-zinc-500 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                        title="Remove subtask"
                    >
                        <span class="material-symbols-outlined" style="font-size:18px">close</span>
                    </button>
                </div>
            </template>

            {{-- Add new subtask --}}
            <div class="flex items-center gap-2 mt-3">
                <input
                    type="text"
                    x-model="newTitle"
                    @keydown.enter.prevent="add()"
                    placeholder="Add a subtask..."
                    class="flex-1 px-3 py-1.5 text-sm bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <button
                    type="button"
                    @click="add()"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors"
                >
                    <span class="material-symbols-outlined" style="font-size:14px">add</span>
                    Add
                </button>
            </div>

            {{-- Empty state --}}
            <template x-if="subtasks.length === 0">
                <p class="mt-2 text-xs text-slate-400 dark:text-zinc-500">No subtasks added yet.</p>
            </template>
        </div>

    </div>

    {{-- Right column: metadata --}}
    <div class="lg:col-span-5 flex flex-col gap-5">

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 flex flex-col gap-4">

            {{-- Course --}}
            <flux:field>
                <flux:label>Course</flux:label>
                <select name="course_id" class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">No course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id', $task->course_id ?? '') == $course->id)>
                            {{ $course->name }}{{ $course->code ? ' (' . $course->code . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                <flux:error name="course_id" />
            </flux:field>

            {{-- Category --}}
            <flux:field>
                <flux:label>Category <span class="text-red-500">*</span></flux:label>
                <select name="category" required class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select category</option>
                    @foreach (\App\Enums\TaskCategory::cases() as $case)
                        <option value="{{ $case->value }}" @selected(old('category', $task->category?->value ?? '') === $case->value)>{{ $case->label() }}</option>
                    @endforeach
                </select>
                <flux:error name="category" />
            </flux:field>

            {{-- Priority --}}
            <flux:field>
                <flux:label>Priority <span class="text-red-500">*</span></flux:label>
                <select name="priority" required class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select priority</option>
                    @foreach (\App\Enums\TaskPriority::cases() as $case)
                        <option value="{{ $case->value }}" @selected(old('priority', $task->priority?->value ?? 'medium') === $case->value)>{{ $case->label() }}</option>
                    @endforeach
                </select>
                <flux:error name="priority" />
            </flux:field>

            {{-- Status (edit only) --}}
            @if ($isEdit)
                <flux:field>
                    <flux:label>Status</flux:label>
                    <select name="status" required class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach (\App\Enums\TaskStatus::cases() as $case)
                            <option value="{{ $case->value }}" @selected(old('status', $task->status?->value ?? '') === $case->value)>{{ $case->label() }}</option>
                        @endforeach
                    </select>
                    <flux:error name="status" />
                </flux:field>
            @endif

            {{-- Due date --}}
            <flux:field>
                <flux:label>Due Date</flux:label>
                <flux:input
                    type="date"
                    name="due_date"
                    :value="old('due_date', $task->due_date?->format('Y-m-d') ?? '')"
                />
                <flux:error name="due_date" />
            </flux:field>

            {{-- Due time --}}
            <flux:field>
                <flux:label>Due Time</flux:label>
                <flux:input
                    type="time"
                    name="due_time"
                    :value="old('due_time', $task->due_time ?? '')"
                />
                <flux:error name="due_time" />
            </flux:field>

            {{-- Recurring --}}
            <div class="flex items-center gap-2.5 pt-1">
                <input
                    type="hidden"
                    name="is_recurring"
                    value="0"
                />
                <input
                    type="checkbox"
                    id="is_recurring"
                    name="is_recurring"
                    value="1"
                    @checked(old('is_recurring', $task->is_recurring ?? false))
                    class="rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500"
                />
                <label for="is_recurring" class="text-sm text-slate-700 dark:text-zinc-300 cursor-pointer">
                    Recurring task
                </label>
            </div>

        </div>

    </div>

</div>
