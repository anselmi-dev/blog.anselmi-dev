@extends('layouts.base')

@section('content')
    <div class="absolute top-0 bottom-0 left-0 right-0 m-auto items-center justify-center flex">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg text-gray-app dark:text-white border-r border-gray-400 dark:border-white tracking-wider">
                    @yield('code')
                </div>

                <div class="ml-4 text-lg text-gray-app dark:text-white uppercase tracking-wider">
                    @yield('message')
                </div>
            </div>
        </div>
    </div>
@stop
