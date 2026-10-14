@php
    use App\Support\Localization;
    use App\Support\SiteMenu;

    $menuGroups = SiteMenu::groups();
    $featuredPosts = SiteMenu::featuredPosts();

    $navLinks = [];

    if ($hasPublishedPosts ?? false) {
        $navLinks[] = ['label' => __('Blog'), 'href' => localized_route('blog')];
    }

    $navLinks[] = ['label' => __('Sobre mí'), 'href' => localized_route('about')];

    $alternateLocale = Localization::isEnglish() ? 'es' : 'en';
@endphp

<x-site-frame
    {{ $attributes->class(['z-30 bg-brand-lime transition-colors duration-300']) }}
    data-site-header
    data-reveal="fade-down"
    data-reveal-stagger="0.07"
    data-reveal-duration="0.85"
>
    <header
        class="relative z-30"
        x-data="{ menuOpen: false }"
        x-on:keydown.escape.window="if (menuOpen) { menuOpen = false; $refs.menuTrigger?.focus() }"
        x-on:livewire:navigating.window="menuOpen = false"
    >
        <div
            class="relative z-0 flex min-h-[4.5rem] items-center justify-between gap-3 px-4 pt-3 sm:min-h-[5.25rem] sm:px-6 lg:min-h-[5.5rem] lg:px-8 lg:pt-4"
        >
            <a
                href="{{ localized_route('home') }}"
                wire:navigate
                data-reveal-item
                class="inline-flex items-center text-zinc-900 transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
            >
                <x-logo.app class="size-8" animated />
            </a>

            <nav
                class="items-center gap-7 text-sm font-medium text-zinc-900 md:flex lg:gap-10 hidden"
                aria-label="{{ __('Navegación principal') }}"
            >
                <button
                    type="button"
                    data-reveal-item
                    x-ref="menuTrigger"
                    x-on:click.stop="menuOpen = ! menuOpen"
                    x-bind:aria-expanded="menuOpen"
                    aria-controls="site-menu"
                    class="flex cursor-pointer items-center gap-2 rounded-md py-1 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                >
                    <span>{{ __('Explorar') }}</span>
                    <span class="inline-flex size-7 items-center justify-center rounded-full border border-zinc-900" aria-hidden="true">
                        <x-icon
                            name="chevron-down"
                            class="size-3.5 shrink-0 transition-transform duration-300 ease-out"
                            x-bind:class="{ 'rotate-180': menuOpen }"
                        />
                    </span>
                </button>

                @foreach ($navLinks as $item)
                    <x-header.nav-link
                        data-reveal-item
                        :href="$item['href']"
                        navigate
                    >
                        {{ $item['label'] }}
                    </x-header.nav-link>
                @endforeach
            </nav>

            <div class="flex items-center gap-2 sm:gap-3" data-reveal-item>
                <a
                    href="{{ Localization::switchUrl($alternateLocale) }}"
                    hreflang="{{ $alternateLocale }}"
                    lang="{{ $alternateLocale }}"
                    class="inline-flex size-11 items-center justify-center rounded-full border border-zinc-900 text-xs font-semibold uppercase tracking-wide text-zinc-900 transition hover:bg-zinc-900/5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:size-12 sm:text-sm"
                    aria-label="{{ $alternateLocale === 'en' ? 'View this page in English' : 'Ver esta página en español' }}"
                >
                    {{ $alternateLocale }}
                </a>

                <button
                    type="button"
                    x-on:click.stop="menuOpen = ! menuOpen"
                    x-bind:aria-expanded="menuOpen"
                    aria-controls="site-menu"
                    class="flex cursor-pointer items-center justify-center rounded-full border border-zinc-900 p-2.5 transition hover:bg-zinc-900/5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 md:hidden"
                    x-bind:aria-label="menuOpen ? @js(__('Cerrar menú')) : @js(__('Abrir menú'))"
                >
                    <span x-show="! menuOpen"><x-icon name="menu" class="size-6" /></span>
                    <span x-show="menuOpen" x-cloak><x-icon name="x" class="size-6" /></span>
                </button>

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
                    <span class="max-[360px]:sr-only sm:inline">{{ __('Contacto') }}</span>
                </button>
            </div>
        </div>

        <x-header.mega-menu :groups="$menuGroups" :featured-posts="$featuredPosts" />
    </header>

    <div data-reveal-item>
        <div data-page-transition>
            @yield('header-content')
        </div>
    </div>
</x-site-frame>
