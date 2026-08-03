@props([
    'preset' => 'fade-up',
    'stagger' => null,
    'delay' => null,
    'duration' => null,
    'scroll' => false,
    'tag' => 'div',
])

@php
    $revealAttrs = [
        'data-reveal' => $preset,
    ];

    if ($stagger !== null) {
        $revealAttrs['data-reveal-stagger'] = $stagger;
    }

    if ($delay !== null) {
        $revealAttrs['data-reveal-delay'] = $delay;
    }

    if ($duration !== null) {
        $revealAttrs['data-reveal-duration'] = $duration;
    }

    if ($scroll) {
        $revealAttrs['data-reveal-scroll'] = true;
    }
@endphp

<{{ $tag }} {{ $attributes->merge($revealAttrs) }}>
    {{ $slot }}
</{{ $tag }}>
