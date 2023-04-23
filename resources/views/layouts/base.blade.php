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
        }
    }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    x-bind:class="{'dark': darkMode === 'dark' || (darkMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)}"
>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        [x-cloak] {
            display: none
        }
    </style>
    @section('styles')
        @vite([
            'resources/css/app.css',
            'resources/js/app.js'
        ])
    @show

    @livewireStyles
</head>

<body class="h-full | bg-zinc-50 dark:bg-black">
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    @section('body')
        <div id="app" class="flex flex-col h-full">
            @section('navigation')
                {{-- Comment this include to remove navigation --}}
                @include('layouts.parts.navigation')

            @show

            @section('header')
                {{-- Comment this include to remove header section --}}
                @include('layouts.parts.header')
            @show

            {{-- CONTENT --}}
            <div class="flex-1">
                @yield('content')
            </div>
            {{-- /CONTENT --}}

            @section('footer')
                {{-- Comment this include to remove footer section --}}
                @include('layouts.parts.footer')
            @show
        </div>
    @show

    @livewireScripts
</body>

</html>
