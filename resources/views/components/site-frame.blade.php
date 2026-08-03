@props([
    /**
     * Rellena el alto disponible dentro de un padre flex (p. ej. body con min-h-dvh).
     */
    'stretch' => false,
    /**
     * Sin max-width: el frame ocupa todo el ancho del contenedor.
     */
    'fluid' => false,
])

{{-- px-4 pt-6 pb-4 sm:px-6 sm:pt-8 lg:px-8 lg:pt-10 --}}
<div
    @class([
        'mx-auto w-full',
        'max-w-8xl' => ! $fluid,
        'flex min-h-0 min-w-0 flex-1 flex-col' => $stretch,
    ])
>
    <div
        {{ $attributes->class([
            'relative rounded-xl sm:rounded-2xl md:rounded-2xl',
            'min-h-0 w-full min-w-0 flex-1' => $stretch,
        ]) }}
    >
        {{ $slot }}
    </div>
</div>
