<header
    id="app-header"
    class="mx-auto max-w-7xl w-full z-1">

    <nav class="w-full flex items-center justify-between pl-6 pr-2 py-2 lg:py-4 lg:px-8 | x-text-base-color" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="{{ route('home') }}" wire:navigate class="-m-1.5 p-1.5">
                <span class="dark:text-app-default text-black">
                    <x-application-logo class='h-8 w-auto fill-current' />
                </span>
            </a>
        </div>

        <div
            class="hidden lg:flex lg:gap-x-12 px-6 | rounded-full text-base shadow shadow-zinc-800/5 ring-0 ring-zinc-900/5 bg-white dark:bg-zinc-800/90 dark:ring-white/10">
            <x-header.navbar.link wire:navigate :active="request()->routeIs('home')" href="{{ route('home') }}" title="{{ __('Home') }}">
                {{ __('Home') }}
            </x-header.navbar.link>

            <x-header.navbar.link wire:navigate :active="request()->routeIs('gallery.index')" href="{{ route('gallery.index') }}" title="{{ __('Photos') }}">
                {{ __('Photos') }}
            </x-header.navbar.link>

            <x-header.navbar.link wire:navigate :active="request()->routeIs('blog.index')" href="{{ route('blog.index') }}" title="{{ __('Blog') }}">
                {{ __('Blog') }}
            </x-header.navbar.link>
            {{--
            <x-header.navbar.link wire:navigate :active="request()->routeIs('about')" href="{{ route('about') }}" title="{{ __('About') }}">
                {{ __('About') }}
            </x-header.navbar.link>
            --}}
        </div>

        <div class="flex items-center flex-1 space-x-4">

            <div class="flex flex-1 justify-end">
                <x-header.navbar.toggle-dark />
            </div>

            <div class="flex lg:hidden">
                <button type="button"
                    class="group flex h-14 w-14 cursor-pointer items-center justify-center rounded | _shadow_bg-white_hover:bg-slate-200"
                    x-ref="button"
                    x-on:click="togglePanel()"
                    :aria-expanded="open"
                    :aria-controls="$id('panel-button')"
                    :class="openPanel ? 'z-100' : null"
                    >
                    <div class="space-y-2">
                        <span
                            :class="openPanel ? 'translate-y-1.5 rotate-45 bg-white' : null"
                            class="block h-1 w-10 origin-center rounded-full bg-slate-500 transition-transform ease-in-out"></span>
                        <span
                            :class="openPanel ? 'w-10 -translate-y-1.5 -rotate-45' : null"
                            class="block h-1 w-8 origin-center rounded-full bg-app-default transition-transform ease-in-out _group-hover:w-10 _group-hover:-translate-y-1.5 _group-hover:-rotate-45"></span>
                    </div>
                </button>
            </div>
        </div>

    </nav>

    <x-sidebar.default
        class="lg:hidden"
        x-show="openPanel"
        x-cloak>
        <x-slot name="logo">
            <a href="{{ route('home') }}" wire:navigate class="-m-1.5 p-1.5">
                <span class="dark:text-app-default text-black">
                    <x-application-logo class='h-8 w-auto fill-current' />
                </span>
            </a>
        </x-slot>

        <x-sidebar.link wire:navigate :active="request()->routeIs('home')" href="{{ route('home') }}" title="{{ __('home') }}">
            {{ __('home') }}
        </x-sidebar.link>

        <x-sidebar.link wire:navigate :active="request()->routeIs('gallery.index')" href="{{ route('gallery.index') }}" title="{{ __('Photos') }}">
            {{ __('Photos') }}
        </x-header.navbar.link>

        <x-sidebar.link wire:navigate :active="request()->routeIs('blog.index')" href="{{ route('blog.index') }}" title="{{ __('Blog') }}">
            {{ __('Blog') }}
        </x-sidebar.link>

        {{-- <x-sidebar.link wire:navigate :active="request()->routeIs('about')" href="{{ route('about') }}" title="{{ __('About') }}">
            {{ __('About') }}
        </x-sidebar.link> --}}
    </x-sidebar.default>
</header>
