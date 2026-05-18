@php
    $entry ??= null;
    $isEdit = $entry?->exists === true;

    $colorPresets = [
        '#3B82F6', // blue
        '#8B5CF6', // violet
        '#EC4899', // pink
        '#EF4444', // red
        '#F97316', // orange
        '#EAB308', // yellow
        '#22C55E', // green
        '#14B8A6', // teal
        '#6366F1', // indigo
        '#6B7280', // gray
    ];

    $currentColor = old('color', $entry?->color ?? '#3B82F6');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Left column --}}
    <div class="space-y-5">

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                Title <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $entry?->title) }}"
                placeholder="e.g. CS301 – Algorithms Lecture"
                maxlength="100"
                required
                class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-500 @enderror"
            >
            @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Course --}}
        <div>
            <label for="course_id" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                Linked Course
            </label>
            <select
                id="course_id"
                name="course_id"
                class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('course_id') border-red-500 @enderror"
            >
                <option value="">No course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $entry?->course_id) == $course->id)>
                        {{ $course->name }} ({{ $course->code }})
                    </option>
                @endforeach
            </select>
            @error('course_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lecturer --}}
        <div>
            <label for="lecturer" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                Lecturer / Instructor
            </label>
            <input
                type="text"
                id="lecturer"
                name="lecturer"
                value="{{ old('lecturer', $entry?->lecturer) }}"
                placeholder="e.g. Dr. Smith"
                maxlength="100"
                class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('lecturer') border-red-500 @enderror"
            >
            @error('lecturer')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                Location / Room
            </label>
            <input
                type="text"
                id="location"
                name="location"
                value="{{ old('location', $entry?->location) }}"
                placeholder="e.g. Block C, Room 204"
                maxlength="100"
                class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('location') border-red-500 @enderror"
            >
            @error('location')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- Right column --}}
    <div class="space-y-5">

        {{-- Day of Week --}}
        <div>
            <label for="day_of_week" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                Day of Week <span class="text-red-500">*</span>
            </label>
            <select
                id="day_of_week"
                name="day_of_week"
                required
                class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('day_of_week') border-red-500 @enderror"
            >
                <option value="">Select day</option>
                @foreach (\App\Enums\DayOfWeek::cases() as $day)
                    <option value="{{ $day->value }}" @selected(old('day_of_week', $entry?->day_of_week?->value) === $day->value)>
                        {{ $day->label() }}
                    </option>
                @endforeach
            </select>
            @error('day_of_week')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Start / End Time --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                    Start Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="start_time"
                    name="start_time"
                    value="{{ old('start_time', $entry ? substr($entry->start_time, 0, 5) : '') }}"
                    required
                    class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('start_time') border-red-500 @enderror"
                >
                @error('start_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="end_time" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-1">
                    End Time <span class="text-red-500">*</span>
                </label>
                <input
                    type="time"
                    id="end_time"
                    name="end_time"
                    value="{{ old('end_time', $entry ? substr($entry->end_time, 0, 5) : '') }}"
                    required
                    class="w-full px-3 py-2 text-sm bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg text-slate-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('end_time') border-red-500 @enderror"
                >
                @error('end_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Color --}}
        <div x-data="{ color: '{{ $currentColor }}' }">
            <label class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-2">
                Display Color
            </label>
            {{-- Presets --}}
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach ($colorPresets as $preset)
                    <button
                        type="button"
                        @click="color = '{{ $preset }}'"
                        :class="color === '{{ $preset }}' ? 'ring-2 ring-offset-2 ring-indigo-500 scale-110' : 'hover:scale-105'"
                        class="w-7 h-7 rounded-full transition-all duration-150"
                        style="background-color: {{ $preset }}"
                        title="{{ $preset }}"
                    ></button>
                @endforeach
            </div>
            {{-- Custom color input --}}
            <div class="flex items-center gap-3">
                <input
                    type="color"
                    x-model="color"
                    class="h-8 w-12 rounded cursor-pointer border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-0.5"
                >
                <span class="text-sm text-slate-500 dark:text-zinc-400" x-text="color"></span>
                <input type="hidden" name="color" :value="color">
            </div>
        </div>

        {{-- Recurring --}}
        <div>
            <label class="flex items-center gap-3 cursor-pointer group">
                <input
                    type="checkbox"
                    name="is_recurring"
                    value="1"
                    @checked(old('is_recurring', $entry?->is_recurring ?? true))
                    class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-600 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-zinc-800"
                >
                <span class="text-sm font-medium text-slate-700 dark:text-zinc-300">Repeats every week</span>
            </label>
            <p class="mt-1 ml-7 text-xs text-slate-400 dark:text-zinc-500">
                Uncheck if this is a one-time class or event.
            </p>
        </div>

    </div>
</div>
