<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>404 — {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-dvh w-full min-w-0 flex-col bg-white font-sans text-zinc-900 antialiased">
    <div class="flex flex-col min-h-screen px-4 pt-6 pb-4 sm:px-6 sm:pt-8 lg:px-8 lg:pt-10">
    <x-site-frame
        stretch
        fluid
        data-reveal="fade-scale"
        data-reveal-duration="1"
    >
        @php($images = [
            '01.jpg',
            '02.jpg',
            '03.jpg'
        ])
        <img
            data-reveal-item
            src="{{ asset('images/404/' . $images[array_rand($images)]) }}"
            alt=""
            class="absolute inset-0 size-full object-cover object-center grayscale"
            width="1600"
            height="1200"
            decoding="async"
        />

        <a
            href="{{ route('home') }}"
            data-reveal-item
            class="absolute left-0 top-0 z-20 inline-flex items-center rounded-br-[1.5rem] bg-white px-6 py-4 text-2xl font-extrabold tracking-tight text-zinc-900 transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:rounded-br-[2.25rem] sm:px-8 sm:py-5 sm:text-3xl md:rounded-br-[2.5rem]"
        >
            {{ config('app.name', 'Site') }}
        </a>

        <p
            data-reveal-item
            class="absolute bottom-0 right-0 z-10 inline-flex items-center rounded-tl-[1.5rem] bg-white px-6 py-4 text-sm font-medium text-zinc-900 sm:rounded-tl-[2.25rem] sm:px-8 sm:py-5 sm:text-base md:rounded-tl-[2.5rem]"
        >
            404 — Página no encontrada
        </p>
    </x-site-frame>
    </div>
</body>
</html>
