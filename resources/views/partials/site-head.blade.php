@php
    $appName = config('app.name', 'Laravel');
    $pageTitle = filled($title ?? null) ? $title.' — '.$appName : $appName;
    $pageDescription = $description ?? 'Desarrollador web: diseño y construyo productos digitales con foco en claridad, performance y experiencia de uso.';
    $canonicalUrl = $canonical ?? url()->current();
    $shareImage = $ogImage ?? asset('android-chrome-512x512.png');
    $locale = str_replace('_', '-', app()->getLocale());
@endphp

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="{{ $pageDescription }}" />
<meta name="author" content="{{ $appName }}" />
<meta name="theme-color" content="#ffffff" />
<meta name="color-scheme" content="light" />
<meta name="format-detection" content="telephone=no" />

<title>{{ $pageTitle }}</title>

<link rel="canonical" href="{{ $canonicalUrl }}" />

{{-- Favicons / PWA --}}
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48" />
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml" />
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
<link rel="manifest" href="{{ asset('site.webmanifest') }}" />

{{-- Open Graph --}}
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ $appName }}" />
<meta property="og:locale" content="{{ $locale }}" />
<meta property="og:title" content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDescription }}" />
<meta property="og:url" content="{{ $canonicalUrl }}" />
<meta property="og:image" content="{{ $shareImage }}" />

{{-- Twitter --}}
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $pageDescription }}" />
<meta name="twitter:image" content="{{ $shareImage }}" />

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
