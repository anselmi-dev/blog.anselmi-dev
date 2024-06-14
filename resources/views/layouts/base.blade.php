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
            'resources/css/app.css',
            'resources/js/app.js'
        ])
    @show

    @stack('styles')

    @livewireStyles
</head>

<body
    x-init="() => {
        {{-- $el.classList.remove('fade-out') --}}
    }"
    class="h-full | bg-zinc-50 dark:bg-zinc-900/90 | fade-in">
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <svg
        style="position: absolute; height: 89%; opacity: .02; right: 100px; top: 50px"
        viewBox="207.36978118272356 114.99999999999997 135.2604376345529 130.27551443502497">
        <g data-paper-data="{&quot;isIcon&quot;:&quot;true&quot;,&quot;iconType&quot;:&quot;icon&quot;,&quot;rawIconId&quot;:&quot;3964630&quot;,&quot;selectedEffects&quot;:{&quot;container&quot;:&quot;&quot;,&quot;transformation&quot;:&quot;&quot;,&quot;pattern&quot;:&quot;&quot;},&quot;isDetailed&quot;:false,&quot;fillRule&quot;:&quot;evenodd&quot;,&quot;bounds&quot;:{&quot;x&quot;:207.36978118272356,&quot;y&quot;:114.99999999999997,&quot;width&quot;:135.2604376345529,&quot;height&quot;:130.27551443502497},&quot;iconStyle&quot;:&quot;standalone&quot;,&quot;suitableAsStandaloneIcon&quot;:true}"
            fill-rule="evenodd">
            <path
                d="M298.37379,135.8567l43.9625,91.63361c0.45585,1.22384 0.37878,2.5827 -0.21247,3.74724c-0.72144,1.32969 -2.03529,2.23405 -3.53477,2.43377l-87.50005,11.58941c-0.20552,0.0197 -0.41258,0.0197 -0.6181,0h-1.89294l-0.32837,-0.15453h-0.11589l-38.63137,-22.23235c-0.05428,-0.04964 -0.11241,-0.09484 -0.17384,-0.13521c-0.14409,-0.08364 -0.27988,-0.1806 -0.40563,-0.28973l-0.32837,-0.28973l-0.32837,-0.34768l-0.17384,-0.32837l-0.23179,-0.38631c-0.07842,-0.13116 -0.14931,-0.26656 -0.21247,-0.40563c-0.05795,-0.13521 -0.15453,-0.28973 -0.15453,-0.42495c-0.04752,-0.13907 -0.08634,-0.28104 -0.11589,-0.42495c-0.00927,-0.15433 -0.00927,-0.30925 0,-0.46358c-0.01043,-0.14796 -0.01043,-0.2963 0,-0.44426c-0.00966,-0.14796 -0.00966,-0.2963 0,-0.44426c-0.00966,-0.15433 -0.00966,-0.30925 0,-0.46358c0.02299,-0.14989 0.05524,-0.29824 0.09658,-0.44426c0.05795,-0.15453 0.15453,-0.38631 0.15453,-0.46358v-0.21247l43.5955,-99.2633c0.0593,-0.12246 0.13038,-0.23894 0.21247,-0.34768c0.07649,-0.14583 0.16032,-0.28761 0.2511,-0.42495l0.28973,-0.32837l0.32837,-0.367l0.34768,-0.2511c0.12033,-0.1072 0.24956,-0.20417 0.38631,-0.28973l0.367,-0.19316l0.44426,-0.19316c0.13251,-0.04752 0.2681,-0.08615 0.40563,-0.11589l0.46358,-0.11589c0.141,-0.02009 0.28394,-0.02009 0.42495,0h1.41004l0.44426,0.19316l0.38631,0.13521h0.11589l38.63137,18.29195l0.19316,0.11589l0.44426,0.27042l0.27042,0.19316c0.20474,0.15337 0.39249,0.32818 0.56015,0.52152l0.13521,0.15453l0.367,0.50221l0.13521,0.2511zM279.15469,161.23751l8.8659,-20.99615l-25.05245,-11.84052zM276.23802,168.13321l-19.50884,-39.59716l-38.0519,86.65017l39.57784,-4.5585zM275.56197,208.73479l19.62473,-2.2213l-9.90894,-19.9531l2.74282,-7.24338l13.57892,27.48622l27.37033,14.64129l-34.57508,-72.06682l-36.29417,85.9548l67.99121,-8.98179l-27.19649,-14.56403l-25.76712,2.91667zM223.96977,220.28556l24.31845,14.00387l7.43654,-17.61591z"
                data-paper-data="{&quot;isPathIcon&quot;:true}"></path>
        </g>
    </svg>

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
