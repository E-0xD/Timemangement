@php $event ??= null; @endphp

<div class="divide-y divide-zinc-100 dark:divide-zinc-800">
    {{-- Validation errors --}}
    @if($errors->any())
        <div class="px-6 py-4">
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-950">
                <ul class="list-inside list-disc space-y-1 text-sm text-red-700 dark:text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Title & Type --}}
    <div class="grid gap-4 px-6 py-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="title" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Event Title <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $event?->title) }}"
                placeholder="e.g. Calculus Lecture, Final Exam…"
                required
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
            >
        </div>

        <div>
            <label for="type" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Event Type <span class="text-red-500">*</span>
            </label>
            <select
                id="type"
                name="type"
                required
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
            >
                @foreach($eventTypes as $type)
                    <option value="{{ $type->value }}" {{ old('type', $event?->type?->value) === $type->value ? 'selected' : '' }}>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="course_id" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Course (optional)
            </label>
            <select
                id="course_id"
                name="course_id"
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
            >
                <option value="">No course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $event?->course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->name }}{{ $course->code ? ' ('.$course->code.')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Dates & Times --}}
    <div
        x-data="{ isAllDay: {{ old('is_all_day', $event?->is_all_day ?? false) ? 'true' : 'false' }} }"
        class="px-6 py-5"
    >
        <div class="mb-4 flex items-center gap-3">
            <label class="flex cursor-pointer items-center gap-2">
                <input
                    type="checkbox"
                    name="is_all_day"
                    value="1"
                    x-model="isAllDay"
                    {{ old('is_all_day', $event?->is_all_day) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600"
                >
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">All-day event</span>
            </label>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="start_datetime" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    Start <span class="text-red-500">*</span>
                </label>
                <input
                    x-bind:type="isAllDay ? 'date' : 'datetime-local'"
                    id="start_datetime"
                    name="start_datetime"
                    value="{{ old('start_datetime', $event?->start_datetime?->format($event?->is_all_day ? 'Y-m-d' : 'Y-m-d\TH:i')) }}"
                    required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                >
            </div>

            <div>
                <label for="end_datetime" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    End <span class="text-red-500">*</span>
                </label>
                <input
                    x-bind:type="isAllDay ? 'date' : 'datetime-local'"
                    id="end_datetime"
                    name="end_datetime"
                    value="{{ old('end_datetime', $event?->end_datetime?->format($event?->is_all_day ? 'Y-m-d' : 'Y-m-d\TH:i')) }}"
                    required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                >
            </div>
        </div>
    </div>

    {{-- Location & Description --}}
    <div class="grid gap-4 px-6 py-5 sm:grid-cols-2">
        <div>
            <label for="location" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Location
            </label>
            <input
                type="text"
                id="location"
                name="location"
                value="{{ old('location', $event?->location) }}"
                placeholder="e.g. Room 204, Online…"
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
            >
        </div>

        <div class="flex items-end">
            <label class="flex cursor-pointer items-center gap-2">
                <input
                    type="checkbox"
                    name="is_recurring"
                    value="1"
                    {{ old('is_recurring', $event?->is_recurring ?? true) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:border-zinc-600"
                >
                <div>
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Recurring event</span>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Repeats weekly</p>
                </div>
            </label>
        </div>

        <div class="sm:col-span-2">
            <label for="description" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Description
            </label>
            <textarea
                id="description"
                name="description"
                rows="3"
                placeholder="Additional details about this event…"
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder-zinc-500"
            >{{ old('description', $event?->description) }}</textarea>
        </div>
    </div>

    {{-- Color Picker --}}
    <div
        x-data="{
            color: '{{ old('color', $event?->color ?? '') }}',
            presets: ['#3B82F6','#EF4444','#F59E0B','#8B5CF6','#10B981','#06B6D4','#4F46E5','#6B7280'],
        }"
        class="px-6 py-5"
    >
        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            Event Colour <span class="text-xs font-normal text-zinc-400">(overrides type colour)</span>
        </label>
        <div class="flex flex-wrap items-center gap-2">
            {{-- None / Default --}}
            <button
                type="button"
                @click="color = ''"
                class="h-7 w-7 rounded-full border-2 transition-all"
                :class="color === '' ? 'border-indigo-500 scale-110' : 'border-zinc-300 dark:border-zinc-600'"
                title="Use type default"
            >
                <span class="flex h-full w-full items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-600 text-[10px] font-bold text-zinc-500 dark:text-zinc-300">–</span>
            </button>

            {{-- Preset swatches --}}
            <template x-for="preset in presets" :key="preset">
                <button
                    type="button"
                    @click="color = preset"
                    class="h-7 w-7 rounded-full border-2 transition-all"
                    :class="color === preset ? 'border-indigo-500 scale-110' : 'border-transparent'"
                    :style="`background-color: ${preset};`"
                    :title="preset"
                ></button>
            </template>

            {{-- Custom picker --}}
            <label class="relative h-7 w-7 cursor-pointer overflow-hidden rounded-full border-2 border-zinc-300 dark:border-zinc-600" title="Custom colour">
                <input type="color" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" @input="color = $event.target.value" :value="color || '#4F46E5'">
                <span class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-red-400 via-green-400 to-blue-400 text-[10px] font-bold text-white">+</span>
            </label>

            {{-- Preview --}}
            <div class="ml-2 flex items-center gap-2">
                <span
                    class="inline-block h-5 w-16 rounded text-[10px] leading-5 text-center text-white font-medium"
                    :style="color ? `background-color: ${color}` : 'background-color: #6B7280'"
                    x-text="color || 'default'"
                ></span>
            </div>
        </div>

        <input type="hidden" name="color" :value="color">
    </div>
</div>
