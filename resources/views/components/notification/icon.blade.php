{{--
    Ícono de una notificación según su variante. Con :variant fijo dibuja uno;
    con alpine="expresión" dibuja todos y muestra el que coincide (para la pila
    de avisos, que se arma en el navegador).
    El kind "badge" es la versión chica de la píldora superior.
--}}
@props([
    'variant' => null,
    'alpine' => null,
    'kind' => 'tile',
])

@php
    $tile = [
        'pending' => '<path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22" fill="currentColor"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/>',
        'warning' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" fill="currentColor"/><path d="M12 9v4" stroke="var(--notification-tile)"/><path d="M12 17h.01" stroke="var(--notification-tile)"/>',
        'success' => '<circle cx="12" cy="12" r="10" fill="currentColor"/><path d="m8.5 12 2.5 2.5 4.5-5" stroke="var(--notification-tile)"/>',
        'error' => '<circle cx="12" cy="12" r="10" fill="currentColor"/><path d="m15 9-6 6" stroke="var(--notification-tile)"/><path d="m9 9 6 6" stroke="var(--notification-tile)"/>',
        'neutral' => '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/>',
    ];

    $badge = [
        'pending' => '<path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>',
        'warning' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
        'success' => '<path d="M20 6 9 17l-5-5"/>',
        'error' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'neutral' => '<circle cx="12" cy="12" r="4"/>',
    ];

    $paths = $kind === 'badge' ? $badge : $tile;
    $variants = $alpine ? array_keys($paths) : [array_key_exists($variant, $paths) ? $variant : 'neutral'];
@endphp

@foreach ($variants as $name)
    <svg
        {{ $attributes->class(['notification-svg', 'notification-spin' => $kind === 'badge' && $name === 'pending']) }}
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2.25"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
        @if ($alpine) x-show="{{ $alpine }} === @js($name)" @endif
    >{!! $paths[$name] !!}</svg>
@endforeach
