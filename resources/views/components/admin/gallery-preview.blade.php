{{--
    Ficha cuadrada de un ítem de galería en el panel: la miniatura de la foto
    o la portada del video; si no hay imagen, un ícono del tipo.
    $preview tiene la forma de Gallery::adminPreview().
    Estilos en línea con las variables de color de Filament: el panel no
    compila las clases de Tailwind de vistas propias (no tiene tema).
--}}
@props([
    'preview',
    'size' => 40,
])

@php
    [$icon, $color] = match ($preview['kind']) {
        'photo' => ['heroicon-o-photo', 'info'],
        'video' => ['heroicon-o-video-camera', 'primary'],
        'quote' => ['heroicon-o-chat-bubble-bottom-center-text', 'warning'],
        default => ['heroicon-o-exclamation-triangle', 'danger'],
    };
    $box = "display:inline-flex;flex-shrink:0;width:{$size}px;height:{$size}px;border-radius:0.5rem;overflow:hidden;position:relative;align-items:center;justify-content:center;";
@endphp

@if (filled($preview['image']))
    <span style="{{ $box }}box-shadow:0 0 0 1px rgb(0 0 0 / 0.1);" title="{{ $preview['label'] }}">
        <img src="{{ $preview['image'] }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover;" />
        @if ($preview['kind'] === 'video')
            <span style="position:absolute;right:2px;bottom:2px;display:inline-flex;width:16px;height:16px;align-items:center;justify-content:center;border-radius:4px;background:rgb(0 0 0 / 0.6);color:#fff;">
                <x-filament::icon icon="heroicon-m-play" style="width:12px;height:12px;" />
            </span>
        @endif
    </span>
@else
    <span style="{{ $box }}color:var(--{{ $color }}-600);background:color-mix(in oklab, var(--{{ $color }}-500) 12%, transparent);box-shadow:inset 0 0 0 1px color-mix(in oklab, var(--{{ $color }}-600) 25%, transparent);" title="{{ $preview['label'] }}">
        <x-filament::icon :icon="$icon" style="width:{{ round($size / 2) }}px;height:{{ round($size / 2) }}px;" />
    </span>
@endif
