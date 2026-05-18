@props([
    'title',
    'description' => null,
])

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">{{ $title }}</h2>
    @if ($description)
        <p class="mt-1.5 text-sm text-slate-500">{{ $description }}</p>
    @endif
</div>
