@props([
    'galleryHref' => localized_route('gallery'),
    'kicker' => __('07. fotografía'),
    'titleLine1' => __('Lo que veo cuando'),
    'titleItalic' => __('no estoy codando'),
    'items' => null,
])

@php
    use App\Models\Collection;
    use App\Models\Gallery;
    use App\Models\Media;

    $galleryUrl = $galleryHref ?? localized_route('gallery');

    $photos = $items ?? (Collection::query()->published()->first()
        ?->items()
        ->published()
        ->where('galleryable_type', Media::class)
        ->with('galleryable')
        ->take(12)
        ->get()
        ->map(fn (Gallery $item): array => $item->toViewArray())
        ->all() ?? []);

    // Grilla tipo masonry: el span de cada foto sale de su propio aspect
    // ratio (ancho/alto reales), no de una posición fija, así que se adapta
    // a lo que esté publicado. grid-flow-dense rellena los huecos que deja
    // mezclar spans de 1 y 2 columnas/filas.
    $spanFor = function (array $photo): string {
        $w = max(1, (int) ($photo['w'] ?? 800));
        $h = max(1, (int) ($photo['h'] ?? 1200));
        $ratio = $w / $h;
        $wide = $ratio >= 1.3;
        $tall = $ratio <= 0.72;

        if (($photo['featured'] ?? false) && ! $tall) {
            return 'col-span-2 row-span-2';
        }

        if ($wide) {
            return 'col-span-2 row-span-1';
        }

        if ($tall) {
            return 'row-span-2';
        }

        return '';
    };
@endphp

@if (filled($photos))
    <section
        id="galeria"
        class="scroll-mt-8 bg-white py-16 dark:bg-zinc-950 sm:py-20"
    >
        <div class="mx-auto max-w-8xl">
            <x-section-head
                kicker="{{ $kicker }}"
                title="{{ $titleLine1 }}"
                :actionTitle="__('Ver galería')"
                actionHref="{{ $galleryHref }}"
            />

            <div
                class="grid grid-flow-row-dense grid-cols-2 auto-rows-[120px] gap-3 sm:grid-cols-3 sm:auto-rows-[140px] lg:grid-cols-4"
                data-reveal="fade-scale"
                data-reveal-scroll
                data-reveal-stagger="0.07"
            >
                @foreach ($photos as $photo)
                    <x-home.gallery-tile
                        data-reveal-item
                        :href="$galleryUrl"
                        grid-class="{{ $spanFor($photo) }}"
                        gradient="from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900"
                    >
                        <img
                            src="{{ $photo['thumb_url'] ?? $photo['image_url'] }}"
                            alt="{{ $photo['title'] ?? '' }}"
                            class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-[1.03]"
                            loading="lazy"
                            decoding="async"
                        />
                    </x-home.gallery-tile>
                @endforeach
            </div>

            <div class="mt-6 md:hidden" data-reveal="fade-up" data-reveal-scroll>
                <a
                    href="{{ $galleryUrl }}"
                    wire:navigate
                    class="text-sm text-folio-muted-dark transition-colors hover:text-folio-fg dark:text-folio-muted dark:hover:text-zinc-100"
                >
                    {{ __('Ver galería completa') }} →
                </a>
            </div>
        </div>
    </section>
@endif
