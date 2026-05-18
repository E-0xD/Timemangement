@props([
    'label' => '',
    'value' => 0,
    'icon'  => '',
    'color' => 'indigo',
    'href'  => null,
])

@php
$colorMap = [
    'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-950/40', 'text' => 'text-indigo-600 dark:text-indigo-400', 'icon' => 'text-indigo-500 dark:text-indigo-400'],
    'blue'   => ['bg' => 'bg-blue-50 dark:bg-blue-950/40',     'text' => 'text-blue-600 dark:text-blue-400',     'icon' => 'text-blue-500 dark:text-blue-400'],
    'amber'  => ['bg' => 'bg-amber-50 dark:bg-amber-950/40',   'text' => 'text-amber-600 dark:text-amber-400',   'icon' => 'text-amber-500 dark:text-amber-400'],
    'red'    => ['bg' => 'bg-red-50 dark:bg-red-950/40',       'text' => 'text-red-600 dark:text-red-400',       'icon' => 'text-red-500 dark:text-red-400'],
    'green'  => ['bg' => 'bg-green-50 dark:bg-green-950/40',   'text' => 'text-green-600 dark:text-green-400',   'icon' => 'text-green-500 dark:text-green-400'],
    'zinc'   => ['bg' => 'bg-zinc-100 dark:bg-zinc-700',       'text' => 'text-zinc-600 dark:text-zinc-400',     'icon' => 'text-zinc-500 dark:text-zinc-400'],
];
$c = $colorMap[$color] ?? $colorMap['indigo'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-xs font-medium text-slate-500 dark:text-zinc-400 uppercase tracking-wide">{{ $label }}</p>
            @if ($href)
                <a href="{{ $href }}" class="mt-1 block text-3xl font-bold {{ $c['text'] }} hover:underline">{{ $value }}</a>
            @else
                <p class="mt-1 text-3xl font-bold {{ $c['text'] }}">{{ $value }}</p>
            @endif
        </div>
        @if ($icon)
            <div class="shrink-0 p-2.5 rounded-lg {{ $c['bg'] }}">
                <span class="material-symbols-outlined {{ $c['icon'] }}" style="font-size:22px">{{ $icon }}</span>
            </div>
        @endif
    </div>
</div>
