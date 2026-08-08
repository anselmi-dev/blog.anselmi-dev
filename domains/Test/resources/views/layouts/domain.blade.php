<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Test' }}</title>
    @vite(['domains/Test/resources/css/app.css', 'domains/Test/resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans text-zinc-900 antialiased">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center px-6 py-16">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
