<!DOCTYPE html>
<html
    class="h-full"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('darkMode') || localStorage.setItem('darkMode', 'system'),
        toggleDarkMode () {
            if (this.darkMode == 'dark') {
                this.darkMode = 'light';
            } else if (this.darkMode == 'light') {
                this.darkMode = 'dark';
            } else {
                this.darkMode = 'dark';
            }
        },
        openPanel: false,
        togglePanel() {
            if (this.openPanel) {
                return this.close()
            }

            this.$refs.button.focus()

            this.openPanel = !this.openPanel
        },
        closePanel(focusAfter) {
            if (!this.openPanel) return

            this.openPanel = false

            focusAfter && focusAfter.focus()
        },
        open: false,
        toggle() {
            if (this.open) {
                return this.close()
            }

            this.$refs.button.focus()

            this.open = true
        },
        close(focusAfter) {
            if (!this.open) return

            this.open = false

            focusAfter && focusAfter.focus()
        }
    }"
    x-init="
        $watch('darkMode', val => localStorage.setItem('darkMode', val))
    "
    x-bind:class="{'dark': darkMode === 'dark' || (darkMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)}"
>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none
        }
    </style>

    @section('vite')
        @vite([
            'resources/css/app.scss',
            'resources/js/app.js'
        ])
    @show

    @stack('styles')

    @livewireStyles
</head>

<body class="h-full | bg-zinc-50 dark:bg-zinc-900/90 | fade-in">
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    @section('body')
        <div class="flex flex-row w-full h-full _space-x-5 font-roboto">
            @section('navigation')
                @include('layouts.parts.navigation')
            @show

            <div id="app" class="flex flex-col h-full flex-1 w-full">

                @section('header')
                    @include('layouts.parts.header')
                @show

                {{-- CONTENT --}}
                <main id="app-content" class="flex-1 z-0 relative">
                    @yield('content')

                    @isset($slot)
                        {{ $slot }}
                    @endisset
                </main>
                {{-- /CONTENT --}}

                @section('footer')
                    {{-- Comment this include to remove footer section --}}
                    @include('layouts.parts.footer')
                @show
            </div>
        </div>
    @show

    {{-- @livewireScripts --}}
    @livewireScriptConfig

    @stack('scripts')

</body>

</html>
