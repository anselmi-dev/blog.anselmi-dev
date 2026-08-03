@props([
    'title' => '',
])

<div>
    <p class="mb-4 text-sm font-semibold text-zinc-900">{{ $title }}</p>
    <ul class="space-y-3 text-sm">
        {{ $slot }}
    </ul>
</div>
