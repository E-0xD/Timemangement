@php $model ??= null; @endphp

<div class="space-y-5">

    {{-- Name --}}
    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
            Group Name <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" required maxlength="100"
               value="{{ old('name', $model?->name) }}"
               class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Description</label>
        <textarea id="description" name="description" rows="3" maxlength="500"
                  class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">{{ old('description', $model?->description) }}</textarea>
        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Visibility --}}
    <div>
        <label class="flex cursor-pointer items-start gap-3">
            <input type="hidden" name="is_public" value="0" />
            <input type="checkbox" id="is_public" name="is_public" value="1"
                   {{ old('is_public', $model?->is_public) ? 'checked' : '' }}
                   class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500" />
            <div>
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Make group public</span>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Public groups appear in the discover section and anyone can join</p>
            </div>
        </label>
    </div>

</div>
