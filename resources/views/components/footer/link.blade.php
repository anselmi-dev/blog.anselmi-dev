@props([
    'href' => '#',
    'wireNavigate' => false,
])

<li>
    <a
        href="{{ $href }}"
        @if ($wireNavigate) wire:navigate @endif
        {{ $attributes->class([
            'transition hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white',
        ]) }}
    >{{ $slot }}</a>
</li>
