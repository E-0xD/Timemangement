@props([
    'title'    => '',
    'subtitle' => null,
    'back'     => null,
])

<div {{ $attributes->merge(['class' => 'flex items-start justify-between mb-6 gap-4']) }}>
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-2 transition-colors">
                <span class="material-symbols-outlined" style="font-size:14px">arrow_back</span>
                Back
            </a>
        @endif
        <h1 class="text-2xl font-bold text-slate-900 dark:text-zinc-100 truncate">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-0.5 text-sm text-slate-500 dark:text-zinc-400">{{ $subtitle }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex items-center gap-2 shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
