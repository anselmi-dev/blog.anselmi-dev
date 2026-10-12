@props([
    'name' => '',
    'category' => '',
    'icon' => null,
    /** Paleta de la etiqueta: [fondo, manchas, texto, contenido del frasco]. */
    'palette' => ['#1f4d33', '#3f7a52', '#f5efe0', '#c99a4b'],
])

@php
    [$labelColor, $blobColor, $inkColor, $fillColor] = $palette;
    $iconHex = ltrim($inkColor, '#');
@endphp

{{-- Frasco de vidrio con tapa metálica y etiqueta de la tecnología. Todo en HTML/CSS para poder reusarlo con cualquier herramienta. --}}
<div
    {{ $attributes->class(['tools-jar relative select-none']) }}
    style="--jar-label: {{ $labelColor }}; --jar-blob: {{ $blobColor }}; --jar-ink: {{ $inkColor }}; --jar-fill: {{ $fillColor }};"
    aria-hidden="true"
>
    {{-- Tapa --}}
    <div class="tools-jar__lid absolute inset-x-[6%] top-0 h-[17%] rounded-t-[10%] rounded-b-[6%]"></div>

    {{-- Cuerpo de vidrio con el contenido --}}
    <div class="tools-jar__body absolute inset-x-0 top-[15%] bottom-0 overflow-hidden rounded-[12%_12%_14%_14%/8%_8%_9%_9%]">
        <div class="absolute inset-x-0 top-[4%] bottom-0 bg-[var(--jar-fill)]"></div>

        {{-- Etiqueta --}}
        <div class="tools-jar__label absolute inset-x-0 top-[24%] h-[52%] overflow-hidden px-[10%] py-[8%]">
            <div class="relative flex h-full flex-col justify-between">
                <p class="text-[0.62em] uppercase leading-none tracking-[0.18em] text-[var(--jar-ink)]/80">
                    {{ $category }}
                </p>
                <p class="text-[1.35em] leading-[0.95] italic text-[var(--jar-ink)]">
                    {{ $name }}
                </p>
                @if (filled($icon))
                    <img
                        src="https://cdn.simpleicons.org/{{ $icon }}/{{ $iconHex }}"
                        alt=""
                        class="size-[1.5em] self-end opacity-90"
                        width="24"
                        height="24"
                        loading="eager"
                        decoding="async"
                        draggable="false"
                        onerror="this.remove()"
                    />
                @endif
            </div>
        </div>

        {{-- Reflejos del vidrio --}}
        <div class="tools-jar__glass pointer-events-none absolute inset-0"></div>
    </div>
</div>
