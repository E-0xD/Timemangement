<x-layouts::app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">Files</h1>
                <p class="mt-0.5 text-sm text-zinc-500 dark:text-zinc-400">Your uploaded documents and resources</p>
            </div>
        </div>

        @session('success')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400">{{ $value }}</div>
        @endsession

        {{-- Upload form --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-4 text-sm font-semibold text-zinc-700 dark:text-zinc-300">Upload File</h2>
            <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data"
                  class="flex flex-wrap items-end gap-3">
                @csrf

                <div class="flex-1 min-w-56">
                    <label class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">File <span class="text-red-500">*</span></label>
                    <input type="file" name="file" required
                           class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1 file:text-xs file:font-medium file:text-indigo-700 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" />
                    @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="w-40">
                    <label class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">Course</label>
                    <select name="course_id"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        <option value="">None</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Upload
                </button>
            </form>
            <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-600">Max 25 MB. Allowed: PDF, Word, PowerPoint, Excel, text, images, ZIP, CSV.</p>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('files.index') }}" class="flex flex-wrap gap-3">
            <select name="file_type"
                    class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                <option value="">All types</option>
                @foreach($fileTypes as $type)
                    <option value="{{ $type->value }}" {{ $fileType === $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                @endforeach
            </select>
            <select name="course_id"
                    class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 focus:border-indigo-500 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                <option value="">All courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                Filter
            </button>
            @if($fileType || $courseId)
                <a href="{{ route('files.index') }}" wire:navigate
                   class="rounded-lg border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-500 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800">
                    Clear
                </a>
            @endif
        </form>

        {{-- File list --}}
        @if($files->isEmpty())
            <div class="flex flex-1 flex-col items-center justify-center py-16">
                <span class="material-symbols-outlined mb-3 text-5xl text-zinc-300 dark:text-zinc-700">folder_open</span>
                <p class="text-base font-medium text-zinc-500 dark:text-zinc-400">No files yet</p>
                <p class="mt-1 text-sm text-zinc-400 dark:text-zinc-600">Upload a file to get started</p>
            </div>
        @else
            @php
                $typeIcons = [
                    'pdf'   => 'picture_as_pdf',
                    'docx'  => 'description',
                    'image' => 'image',
                    'pptx'  => 'slideshow',
                    'txt'   => 'article',
                    'other' => 'attach_file',
                ];
                $typeColors = [
                    'pdf'   => 'text-red-500',
                    'docx'  => 'text-blue-500',
                    'image' => 'text-emerald-500',
                    'pptx'  => 'text-orange-500',
                    'txt'   => 'text-zinc-500',
                    'other' => 'text-zinc-400',
                ];
            @endphp
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($files as $file)
                        @php $ft = $file->file_type?->value ?? 'other'; @endphp
                        <div class="flex items-center gap-4 px-5 py-4">
                            <span class="material-symbols-outlined text-2xl {{ $typeColors[$ft] ?? 'text-zinc-400' }} leading-none shrink-0">
                                {{ $typeIcons[$ft] ?? 'attach_file' }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $file->original_name }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5 text-xs text-zinc-400 dark:text-zinc-600">
                                    <span>{{ $file->file_type?->label() ?? 'File' }}</span>
                                    <span>{{ $file->humanSize() }}</span>
                                    @if($file->course)
                                        <span class="rounded-full border border-zinc-200 px-1.5 py-0.5 text-zinc-600 dark:border-zinc-700 dark:text-zinc-400">{{ $file->course->name }}</span>
                                    @endif
                                    <span>{{ $file->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex shrink-0 gap-2">
                                <a href="{{ route('files.download', $file) }}"
                                   class="inline-flex h-8 w-8 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 hover:text-indigo-600 dark:hover:bg-zinc-800 dark:hover:text-indigo-400">
                                    <span class="material-symbols-outlined text-base leading-none">download</span>
                                </a>
                                <form method="POST" action="{{ route('files.destroy', $file) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this file?')"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-zinc-400 hover:bg-zinc-100 hover:text-red-500 dark:hover:bg-zinc-800 dark:hover:text-red-400">
                                        <span class="material-symbols-outlined text-base leading-none">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>{{ $files->links() }}</div>
        @endif

    </div>
</x-layouts::app>
