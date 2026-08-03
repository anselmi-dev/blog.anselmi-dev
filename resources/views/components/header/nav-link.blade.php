@props([
    'href' => '#',
    'navigate' => false,
    'variant' => 'nav',
])

@php
    $classes = match ($variant) {
        'menu' => 'block px-4 py-2.5 text-sm font-medium text-zinc-800 hover:bg-zinc-50',
        'dropdown' => 'block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50',
        default => 'rounded-md py-1 transition hover:text-zinc-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900',
    };
@endphp

<a
    href="{{ $href }}"
    @if ($navigate) wire:navigate @endif
    {{ $attributes->class([$classes]) }}
>{{ $slot }}</a>
