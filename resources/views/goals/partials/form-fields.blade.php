@php $model ??= null; @endphp

<div class="space-y-5">

    {{-- Title --}}
    <div>
        <label for="title" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title" required maxlength="150"
               value="{{ old('title', $model?->title) }}"
               class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Description</label>
        <textarea id="description" name="description" rows="3" maxlength="1000"
                  class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">{{ old('description', $model?->description) }}</textarea>
        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Category + Period --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="category" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Category <span class="text-red-500">*</span>
            </label>
            <select id="category" name="category" required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                @foreach($categories as $cat)
                    <option value="{{ $cat->value }}" {{ old('category', $model?->category?->value) === $cat->value ? 'selected' : '' }}>
                        {{ $cat->label() }}
                    </option>
                @endforeach
            </select>
            @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="period" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Period <span class="text-red-500">*</span>
            </label>
            <select id="period" name="period" required
                    class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                @foreach($periods as $per)
                    <option value="{{ $per->value }}" {{ old('period', $model?->period?->value) === $per->value ? 'selected' : '' }}>
                        {{ $per->label() }}
                    </option>
                @endforeach
            </select>
            @error('period') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Target + Current value --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="target_value" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                Target Value <span class="text-red-500">*</span>
            </label>
            <input type="number" id="target_value" name="target_value" step="0.01" min="0.01" max="99999" required
                   value="{{ old('target_value', $model?->target_value) }}"
                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
            @error('target_value') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="current_value" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Current Value</label>
            <input type="number" id="current_value" name="current_value" step="0.01" min="0"
                   value="{{ old('current_value', $model?->current_value ?? 0) }}"
                   class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
            @error('current_value') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Target date --}}
    <div>
        <label for="target_date" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Target Date</label>
        <input type="date" id="target_date" name="target_date"
               value="{{ old('target_date', $model?->target_date?->format('Y-m-d')) }}"
               class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
        @error('target_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

</div>
