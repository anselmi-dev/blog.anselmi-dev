{{--
    Fondo de cuadrados que suben girando y se vuelven círculos (basado en
    codepen.io/mohaiman/pen/MQqMyo). Va fijo detrás de todo el contenido; se
    activa en Filament (Ajustes → Sitio → Animaciones). Estilos en app.css.
--}}
@props([
    /** Opacidad de los cuadrados, de 10 a 100 (%). */
    'opacity' => 75,
    /** Multiplicador de velocidad: 1 es la original, 2 el doble de rápido. */
    'speed' => 1,
])

@php
    $opacity = is_numeric($opacity) ? max(10, min(100, (float) $opacity)) : 75;
    $speed = is_numeric($speed) ? max(0.25, min(3, (float) $speed)) : 1;

    /** @var list<array{left: string, size: string, delay: string, duration: string}> $squares */
    $squares = [
        ['left' => '25%', 'size' => '80px', 'delay' => '0s', 'duration' => '25s'],
        ['left' => '10%', 'size' => '20px', 'delay' => '2s', 'duration' => '12s'],
        ['left' => '70%', 'size' => '20px', 'delay' => '4s', 'duration' => '25s'],
        ['left' => '40%', 'size' => '60px', 'delay' => '0s', 'duration' => '18s'],
        ['left' => '65%', 'size' => '20px', 'delay' => '0s', 'duration' => '25s'],
        ['left' => '75%', 'size' => '110px', 'delay' => '3s', 'duration' => '25s'],
        ['left' => '35%', 'size' => '150px', 'delay' => '7s', 'duration' => '25s'],
        ['left' => '50%', 'size' => '25px', 'delay' => '15s', 'duration' => '45s'],
        ['left' => '20%', 'size' => '15px', 'delay' => '2s', 'duration' => '35s'],
        ['left' => '85%', 'size' => '150px', 'delay' => '0s', 'duration' => '11s'],
    ];
@endphp

<div data-rising-squares class="rising-squares" style="--squares-opacity: {{ $opacity }}%; --squares-speed: {{ $speed }};" aria-hidden="true">
    @foreach ($squares as $square)
        <span style="--left: {{ $square['left'] }}; --size: {{ $square['size'] }}; --delay: {{ $square['delay'] }}; --duration: {{ $square['duration'] }};"></span>
    @endforeach
</div>
