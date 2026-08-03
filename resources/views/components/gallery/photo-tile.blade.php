@props([
    'imageUrl',
    'category',
    'title',
    'href' => '#',
    'featured' => false,
    'showPlay' => false,
    'wide' => false,
    /** Si es true, el tile es un <button> (p. ej. abrir lightbox con wire:click). */
    'asButton' => false,
])

@php
    $shell = 'group relative min-h-0 overflow-hidden rounded-xl border border-zinc-200/90 bg-zinc-100 text-left shadow-sm ring-1 ring-zinc-950/[0.04] transition-shadow duration-300 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:border-zinc-800 dark:bg-zinc-900 dark:ring-white/[0.06] dark:hover:shadow-lg dark:hover:shadow-black/20';
    $frame = $wide
        ? 'relative aspect-[4/3] min-h-[13rem] w-full h-full sm:aspect-video sm:min-h-[15rem] lg:min-h-[17rem]'
        : 'relative aspect-[3/4] min-h-[15rem] w-full h-full sm:min-h-[17rem] lg:min-h-[18rem]';
@endphp

@if ($asButton)
    <button type="button" {{ $attributes->class([$shell, 'block w-full cursor-pointer']) }}>
@else
    <a href="{{ $href }}" {{ $attributes->class([$shell, 'block']) }}>
@endif
    <div class="{{ $frame }}">
        <img
            src="{{ $imageUrl }}"
            alt="{{ $title }} — {{ $category }}"
            class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-[1.03]"
            loading="lazy"
            decoding="async"
        />
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-black/10 dark:from-black/85 dark:via-black/35"
            aria-hidden="true"
        ></div>

        @if ($showPlay)
            <div class="absolute left-3 top-3 z-[2] sm:left-4 sm:top-4" aria-hidden="true">
                <span
                    class="inline-flex size-10 items-center justify-center rounded-full bg-white/95 text-zinc-900 shadow-md ring-1 ring-zinc-900/10 backdrop-blur-[2px] dark:bg-white/90"
                >
                    <svg class="ml-0.5 size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M8 5v14l11-7L8 5z" />
                    </svg>
                </span>
            </div>
        @endif

        @if ($featured)
            <div class="absolute right-3 top-3 z-[2] sm:right-4 sm:top-4">
                <span
                    class="inline-flex rounded-full border border-white/25 bg-white/15 px-2.5 py-1 text-[0.625rem] font-semibold uppercase tracking-widest text-white backdrop-blur-sm"
                >
                    Destacado
                </span>
            </div>
        @endif

        <div class="absolute inset-x-0 bottom-0 z-[2] p-4 sm:p-5">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-white/85">
                {{ $category }}
            </p>
            <h3 class="mt-1.5 max-w-[95%] text-base font-bold leading-snug text-white sm:text-lg">
                {{ $title }}
            </h3>
            <div class="mt-3 flex -space-x-2" aria-hidden="true">
                @foreach (range(1, 3) as $i)
                    <span
                        class="inline-flex size-7 rounded-full border-2 border-white/80 bg-gradient-to-br from-zinc-200 to-zinc-400 ring-1 ring-black/10 dark:from-zinc-600 dark:to-zinc-800"
                    ></span>
                @endforeach
            </div>
        </div>
    </div>
@if ($asButton)
    </button>
@else
    </a>
@endif
