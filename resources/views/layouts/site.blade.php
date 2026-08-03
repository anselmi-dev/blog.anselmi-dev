<!DOCTYPE html>
<html id="top" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.site-head')
    @stack('meta')
</head>
<body
    class="min-h-screen bg-white font-sans text-zinc-900 antialiased"
    @if (! empty($themeColor ?? null))
        style="--color-brand-lime: {{ $themeColor }}; --color-brand-lime-50: color-mix(in srgb, {{ $themeColor }} 35%, white); --color-brand-lime-100: color-mix(in srgb, {{ $themeColor }} 65%, white); --color-brand-lime-200: color-mix(in srgb, {{ $themeColor }} 80%, white);"
    @endif
>

    <div class="flex flex-col min-h-screen px-4 pt-6 pb-4 sm:px-6 sm:pt-8 lg:px-8 lg:pt-10">
        <x-site-header/>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-site-footer/>
    </div>

    <livewire:contact-modal />

    @livewireScripts
</body>
</html>
