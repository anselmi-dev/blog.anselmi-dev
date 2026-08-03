<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    @section('header-content')
        <x-hero-content>
            <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-folio-forest dark:text-folio-muted">
                Fotografía
            </p>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 sm:text-4xl">
                Galería
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400 sm:text-lg">
                Serie de tomas y notas visuales: mismo criterio que en el resto del sitio — bordes finos, sombra suave, mucho aire.
            </p>
        </x-hero-content>
    @endsection

    <div class="mx-auto mt-10 max-w-8xl sm:mt-12">
        <div
            class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3 lg:gap-8"
            data-reveal="fade-scale"
            data-reveal-scroll
            data-reveal-stagger="0.06"
            wire:ignore
        >
            @foreach ($items as $index => $item)
                @if ($item['kind'] === 'quote')
                    <x-gallery.quote-tile
                        data-reveal-item
                        :quote="$item['quote']"
                        :attribution="$item['attribution'] ?? null"
                        :wide="($item['span'] ?? 'tall') === 'wide'"
                        @class([
                            'lg:col-span-2' => ($item['span'] ?? 'tall') === 'wide',
                            'lg:col-span-1' => ($item['span'] ?? 'tall') !== 'wide',
                        ])
                    />
                @else
                    @php
                        $wide = ($item['span'] ?? 'tall') === 'wide';
                        $url = $item['image_url'] ?? sprintf(
                            'https://picsum.photos/seed/gallery-fallback/%d/%d',
                            (int) ($item['w'] ?? 800),
                            (int) ($item['h'] ?? 1200)
                        );
                    @endphp
                    <x-gallery.photo-tile
                        data-reveal-item
                        :image-url="$url"
                        :category="$item['category']"
                        :title="$item['title']"
                        :featured="(bool) ($item['featured'] ?? false)"
                        :show-play="(bool) ($item['play'] ?? false)"
                        :wide="$wide"
                        as-button
                        wire:click.prevent="openLightbox({{ $index }})"
                        wire:key="gallery-thumb-{{ $index }}"
                        @class([
                            'lg:col-span-2' => $wide,
                            'lg:col-span-1' => ! $wide,
                        ])
                    />
                @endif
            @endforeach
        </div>

        <p
            class="mx-auto mt-12 max-w-xl text-center text-sm text-zinc-500 dark:text-zinc-400"
            data-reveal="fade-up"
            data-reveal-scroll
        >
            ¿Querés encargar sesión o licencia de uso?
            <button
                type="button"
                class="font-semibold text-zinc-800 underline decoration-zinc-300 underline-offset-2 transition hover:decoration-zinc-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:text-zinc-200 dark:decoration-zinc-600 dark:hover:decoration-zinc-400"
                x-data
                x-on:click="Livewire.dispatch('open-contact-modal')"
            >
                Escribime
            </button>
        </p>
    </div>

    @include('livewire.pages.partials.gallery-lightbox')
</div>
