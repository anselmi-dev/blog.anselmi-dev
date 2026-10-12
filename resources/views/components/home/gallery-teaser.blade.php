@props([
    'galleryHref' => route('gallery'),
    'kicker' => '07. fotografía',
    'titleLine1' => 'Lo que veo cuando',
    'titleItalic' => 'no estoy codando',
    'items' => null,
])

@php
    use App\Models\Collection;
    use App\Models\Gallery;
    use App\Models\Media;

    $galleryUrl = $galleryHref ?? route('gallery');

    $photos = $items ?? (Collection::query()->published()->first()
        ?->items()
        ->published()
        ->where('galleryable_type', Media::class)
        ->with('galleryable')
        ->take(7)
        ->get()
        ->map(fn (Gallery $item): array => $item->toViewArray())
        ->all() ?? []);

    $gridClasses = [
        'row-span-2 min-h-[280px]',
        'aspect-square',
        'aspect-square',
        'aspect-square',
        'col-span-1 md:col-span-2 md:aspect-[2/1]',
        'aspect-square',
        'aspect-square',
    ];

    $gradients = [
        'from-brand-lime-50 to-brand-lime-200',
        'from-brand-lime-100 to-brand-lime-300',
        'from-brand-lime-100 to-brand-lime-300',
        'from-brand-lime-200 to-brand-lime-400',
        'from-brand-lime to-brand-lime-300',
        'from-brand-lime-100 to-brand-lime-400',
        'from-brand-lime-50 to-brand-lime-200',
    ];
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
                actionTitle="Ver galería"
                actionHref="{{ $galleryHref }}"
            />

            <div
                class="grid grid-cols-2 gap-3 md:grid-cols-4"
                data-reveal="fade-scale"
                data-reveal-scroll
                data-reveal-stagger="0.07"
            >
                @foreach ($photos as $index => $photo)
                    <x-home.gallery-tile
                        data-reveal-item
                        :href="$galleryUrl"
                        grid-class="{{ $gridClasses[$index] ?? 'aspect-square' }}"
                        gradient="{{ $gradients[$index] ?? 'from-brand-lime-100 to-brand-lime-300' }}"
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
                    Ver galería completa →
                </a>
            </div>
        </div>
    </section>
@endif
