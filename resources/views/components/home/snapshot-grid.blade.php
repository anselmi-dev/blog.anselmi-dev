@props([
    /** @var array<int, array{title: string, author: string, image: string}>|null */
    'books' => null,
    'mapImage' => null,
    'mapsUrl' => null,
    'mapLabel' => null,
    'spotifyEmbedUrl' => null,
    'carouselInterval' => null,
])

@php
    use App\Models\HomeSnapshot;
    use App\Models\ReadingBook;

    $snapshot = HomeSnapshot::current();
    $bookList = $books ?? ReadingBook::query()
        ->published()
        ->get()
        ->map(fn (ReadingBook $book): array => $book->toViewArray())
        ->filter(fn (array $book): bool => filled($book['title']))
        ->values()
        ->all();

    $mapImage = $mapImage ?? $snapshot->mapImageUrl();
    $mapsUrl = $mapsUrl ?? $snapshot->maps_url;
    $mapLabel = $mapLabel ?? ($snapshot->map_label ?: 'MONTEVIDEO · URUGUAY');
    $spotifyEmbedUrl = $spotifyEmbedUrl ?? $snapshot->spotify_embed_url;
    $carouselInterval = $carouselInterval ?? ($snapshot->carousel_interval ?: 4500);

    $showBooks = count($bookList) > 0;
    $showMap = filled($mapImage) || filled($mapsUrl);
    $showSpotify = filled($spotifyEmbedUrl);
@endphp

@if ($showBooks || $showMap || $showSpotify)
<section
    id="vitrina-snapshot"
    data-reveal="fade-up"
    data-reveal-scroll
    data-reveal-stagger="0.1"
>
    <div class="mx-auto max-w-8xl">
        <div class="grid grid-cols-2 gap-x-4 gap-y-4 lg:grid-cols-4 lg:gap-y-12">
            @if ($showBooks)
                {{-- Qué estoy leyendo --}}
                <div
                    class="col-span-2 sm:col-span-1 lg:col-span-1"
                    data-reveal-item
                    data-book-carousel
                    data-carousel-interval="{{ $carouselInterval }}"
                >
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-lg font-normal uppercase leading-none tracking-tight text-zinc-500 lg:text-xl">
                                Qué estoy leyendo
                            </p>

                            @if (count($bookList) > 1)
                                <div class="flex shrink-0 items-center gap-1" aria-label="Controles del carrusel">
                                    <button
                                        type="button"
                                        data-book-prev
                                        class="inline-flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-zinc-400 hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                                        aria-label="Libro anterior"
                                    >
                                        <x-icon name="chevron-left" class="size-4" />
                                    </button>
                                    <button
                                        type="button"
                                        data-book-next
                                        class="inline-flex size-8 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-zinc-400 hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                                        aria-label="Libro siguiente"
                                    >
                                        <x-icon name="chevron-right" class="size-4" />
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div
                            class="relative z-0 h-[152px] overflow-hidden rounded-xl bg-zinc-900/80 p-4 text-white shadow-sm ring-1 ring-white/5"
                            role="region"
                            aria-roledescription="carrusel"
                            aria-label="Libros que estoy leyendo"
                        >
                            @foreach ($bookList as $i => $book)
                                <div
                                    data-book-slide
                                    @class([
                                        'absolute inset-0 p-4',
                                        'opacity-0' => $i > 0,
                                    ])
                                    @if ($i > 0) aria-hidden="true" @endif
                                >
                                    <div class="relative z-10 max-w-[65%] pr-2">
                                        <p class="font-serif text-lg italic leading-snug sm:text-xl">{{ $book['title'] }}</p>
                                        <p class="mt-2 font-sans text-sm text-zinc-400">— {{ $book['author'] }}</p>
                                    </div>
                                    @if (filled($book['image']))
                                        <img
                                            src="{{ $book['image'] }}"
                                            alt="{{ $book['title'] }} — {{ $book['author'] }}"
                                            class="pointer-events-none absolute -bottom-1 -right-1 z-[1] w-[6.5rem] rotate-12 rounded-md object-cover shadow-lg ring-1 ring-black/20 sm:w-[7.25rem]"
                                            width="200"
                                            height="280"
                                            loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                                            decoding="async"
                                        />
                                    @endif
                                </div>
                            @endforeach

                            @if (count($bookList) > 1)
                                <div
                                    class="absolute bottom-3 left-4 z-20 flex items-center gap-1.5"
                                    role="tablist"
                                    aria-label="Elegir libro"
                                >
                                    @foreach ($bookList as $i => $book)
                                        <button
                                            type="button"
                                            data-book-dot
                                            class="size-1.5 rounded-full bg-white/35 transition-[background-color,transform] duration-300 hover:scale-125 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                                            aria-label="Mostrar {{ $book['title'] }}"
                                            @if ($i === 0) aria-current="true" @endif
                                        ></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($showMap)
                {{-- Dónde vivo --}}
                <div class="col-span-2 sm:col-span-1 lg:col-span-1" data-reveal-item>
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="order-2 flex w-full items-center gap-2 text-lg font-normal uppercase leading-none tracking-tight text-zinc-500 sm:order-1 lg:text-xl">
                                ¿Dónde vivo?
                            </p>
                        </div>
                        <a
                            href="{{ $mapsUrl ?: '#' }}"
                            @if ($mapsUrl) target="_blank" rel="noopener noreferrer" @endif
                            class="group relative block h-[152px] overflow-hidden rounded-xl bg-emerald-700 shadow-sm ring-1 ring-white/10"
                        >
                            @if ($mapImage)
                                <img
                                    src="{{ $mapImage }}"
                                    alt="Mapa — {{ $mapLabel }}"
                                    class="absolute inset-0 z-0 size-full min-h-full min-w-full object-cover object-bottom transition duration-300 group-hover:scale-[1.02]"
                                    width="800"
                                    height="400"
                                    loading="lazy"
                                    decoding="async"
                                />
                            @endif
                            <div
                                class="absolute right-2 top-2 rounded-md bg-white p-1.5 text-zinc-900 shadow-sm ring-1 ring-black/5"
                                aria-hidden="true"
                            >
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </div>
                            <div class="absolute bottom-3 left-3 flex max-w-[85%] items-center gap-2 text-[0.65rem] font-semibold uppercase leading-tight tracking-wide text-white drop-shadow-md">
                                <svg class="size-4 shrink-0 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $mapLabel }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            @if ($showSpotify)
                {{-- Qué escucho --}}
                <div class="col-span-2 sm:col-span-2 lg:col-span-2" data-reveal-item>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <p class="text-lg font-normal uppercase leading-none tracking-tight text-zinc-500 lg:text-xl">
                                ¿Qué escucho?
                            </p>
                        </div>
                        <div class="overflow-hidden rounded-xl bg-zinc-900/80 ring-1 ring-white/10">
                            <iframe
                                class="block w-full"
                                style="border-radius: 12px"
                                src="{{ $spotifyEmbedUrl }}"
                                width="100%"
                                height="152"
                                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                loading="lazy"
                                title="Spotify — canción actual"
                            ></iframe>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif
