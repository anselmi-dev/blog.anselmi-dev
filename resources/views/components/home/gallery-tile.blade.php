@props([
    'href' => '#',
    /** Clases de grid extra: row-span-2, aspect-square, col-span-2 aspect-[2/1], etc. */
    'gridClass' => 'aspect-square',
    /** Clases del degradado (from-* to-*). Por defecto usa la paleta brand-lime del layout. */
    'gradient' => 'from-brand-lime-100 to-brand-lime-300',
])

<a
    href="{{ $href }}"
    wire:navigate
    {{ $attributes->class([
        $gridClass,
        'group relative min-h-0 cursor-zoom-in overflow-hidden rounded-2xl bg-brand-lime',
    ]) }}
>
    <div class="absolute inset-0 z-0 min-h-0 bg-gradient-to-br {{ $gradient }}"></div>
    {{ $slot }}
    <div class="absolute inset-0 z-[1] bg-black/0 transition-colors duration-300 group-hover:bg-black/30"></div>
    <div
        class="absolute right-3 top-3 z-[2] translate-y-1 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100"
    >
        <div
            class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 shadow-md md:h-7 md:w-7"
            aria-hidden="true"
        >
            <svg
                class="size-4 text-zinc-900 md:size-3.5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
            </svg>
        </div>
    </div>
</a>
