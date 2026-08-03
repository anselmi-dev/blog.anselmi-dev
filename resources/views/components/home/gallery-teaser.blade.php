@props([
    'galleryHref' => route('gallery'),
    'kicker' => '07. fotografía',
    'titleLine1' => 'Lo que veo cuando',
    'titleItalic' => 'no estoy codando',
])

@php
    $galleryUrl = $galleryHref ?? route('gallery');
@endphp

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
            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="row-span-2 min-h-[280px]"
                gradient="from-brand-lime-50 to-brand-lime-200"
            />

            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="aspect-square"
                gradient="from-brand-lime-100 to-brand-lime-300"
            />


            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="aspect-square"
                gradient="from-brand-lime-100 to-brand-lime-300"
            />

            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="aspect-square"
                gradient="from-brand-lime-200 to-brand-lime-400"
            />

            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="col-span-1 md:col-span-2 md:aspect-[2/1]"
                gradient="from-brand-lime to-brand-lime-300"
            />

            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="aspect-square"
                gradient="from-brand-lime-100 to-brand-lime-400"
            />

            <x-home.gallery-tile
                data-reveal-item
                :href="$galleryUrl"
                grid-class="aspect-square"
                gradient="from-brand-lime-50 to-brand-lime-200"
            />
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
