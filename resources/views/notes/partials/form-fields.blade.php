@php $model ??= null; @endphp

<div class="space-y-5">

    {{-- Title --}}
    <div>
        <label for="title" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title" required maxlength="200"
               value="{{ old('title', $model?->title) }}"
               class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Course --}}
    <div>
        <label for="course_id" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Course</label>
        <select id="course_id" name="course_id"
                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
            <option value="">No course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ old('course_id', $model?->course_id) == $course->id ? 'selected' : '' }}>
                    {{ $course->name }}{{ $course->code ? ' ('.$course->code.')' : '' }}
                </option>
            @endforeach
        </select>
        @error('course_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Content --}}
    <div>
        <label for="content" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Content</label>
        <textarea id="content" name="content" rows="18"
                  class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 font-mono leading-relaxed focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">{{ old('content', $model?->content) }}</textarea>
        @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

</div>
