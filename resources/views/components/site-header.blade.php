@php
    $dropdownLinks = [
        ['label' => 'Mis fuentes', 'href' => route('services.fonts'), 'navigate' => true],
        ['label' => 'Mis colores', 'href' => route('services.colors'), 'navigate' => true],
        ['label' => 'Mis herramientas', 'href' => route('services.tools'), 'navigate' => true],
    ];

    if ($hasPublishedGalleryPhotos ?? false) {
        $dropdownLinks[] = ['label' => 'Galería', 'href' => route('gallery'), 'navigate' => true];
    }

    $dropdownLinks[] = ['label' => 'Proyectos', 'href' => route('projects'), 'navigate' => true];

    $navLinks = [
        ['label' => 'Blog', 'href' => route('blog'), 'navigate' => true],
    ];

    if ($hasPublishedFaqs ?? false) {
        $navLinks[] = ['label' => 'FAQ', 'href' => route('faq'), 'navigate' => true];
    }

    $navLinks[] = ['label' => 'Sobre mí', 'href' => route('about'), 'navigate' => true];

    $mobileLinks = [...$dropdownLinks, ...$navLinks];
@endphp

<x-site-frame
    {{ $attributes->class(['bg-brand-lime transition-colors duration-300']) }}
    data-reveal="fade-down"
    data-reveal-stagger="0.07"
    data-reveal-duration="0.85"
>
    <header class="relative z-1">
        <div
            class="relative z-0 flex min-h-[4.5rem] items-center justify-between gap-3 px-4 pt-3 sm:min-h-[5.25rem] sm:px-6 lg:min-h-[5.5rem] lg:px-8 lg:pt-4"
        >
            <a
                href="{{ route('home') }}"
                wire:navigate
                data-reveal-item
                class="inline-flex items-center text-zinc-900 transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
            >
                <x-logo.app class="size-8" />
            </a>

            <nav
                class="items-center gap-7 text-sm font-medium text-zinc-900 md:flex lg:gap-10 hidden lg:block"
                aria-label="Navegación principal"
            >
                <div data-reveal-item>
                    <x-dropdown label="Servicios" :items="$dropdownLinks" />
                </div>

                @foreach ($navLinks as $item)
                    <x-header.nav-link
                        data-reveal-item
                        :href="$item['href']"
                        :navigate="! empty($item['navigate'])"
                    >
                        {{ $item['label'] }}
                    </x-header.nav-link>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 sm:gap-3" data-reveal-item>
                <details class="relative md:hidden">
                    <summary
                        class="flex cursor-pointer list-none items-center justify-center rounded-full border border-zinc-900 p-2.5 marker:hidden [&::-webkit-details-marker]:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                        aria-label="Abrir menú"
                    >
                        <x-icon name="menu" class="size-6" />
                    </summary>
                    <div
                        class="fixed left-1/2 top-[4.75rem] z-30 w-[min(100vw-2rem,18rem)] -translate-x-1/2 rounded-2xl border border-zinc-200 bg-white py-3 shadow-xl sm:top-[5.5rem]"
                    >
                        @foreach ($mobileLinks as $item)
                            <x-header.nav-link
                                variant="menu"
                                :href="$item['href']"
                                :navigate="! empty($item['navigate'])"
                            >
                                {{ $item['label'] }}
                            </x-header.nav-link>
                        @endforeach
                    </div>
                </details>

                <button
                    type="button"
                    x-data
                    x-on:click="Livewire.dispatch('open-contact-modal')"
                    class="group inline-flex cursor-pointer items-center gap-2.5 rounded-full border-2 border-dashed border-zinc-900 bg-transparent px-3 py-2 text-xs font-semibold text-zinc-900 transition hover:bg-zinc-900/5 sm:px-5 sm:py-2.5 sm:text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                >
                    <span
                        class="inline-flex size-8 items-center justify-center rounded-full border border-zinc-900 bg-white"
                        aria-hidden="true"
                    >
                        <svg
                            class="size-4 origin-[50%_88%] text-zinc-900 transition-transform duration-300 ease-out will-change-transform group-hover:-translate-y-0.5 group-hover:scale-105 group-hover:-rotate-[5deg] group-focus-visible:-translate-y-0.5 group-focus-visible:scale-105 group-focus-visible:-rotate-[5deg]"
                            viewBox="0 0 32 32"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M16 26V12M16 12c0-3 2.5-5.5 6-6M16 12c0-3-2.5-5.5-6-6"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path d="M10 20c2 2 4 3 6 3s4-1 6-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="max-[360px]:sr-only sm:inline">Contacto</span>
                </button>
            </div>
        </div>
    </header>

    <div data-reveal-item>
        @yield('header-content')
    </div>
</x-site-frame>
