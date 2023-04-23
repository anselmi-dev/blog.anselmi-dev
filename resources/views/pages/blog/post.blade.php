@extends('layouts.base')

@section('title', $post->title)
@section('description', $post->description)

@section('content')
    <main class="flex flex-col gap-16 mb-16">
        <x-containers.default>

            <x-post.container>

                <div class="flex sticky top-4 z-1 h-min">
                    <div class="flex flex-col gap-5">
                        <x-buttons.circle href="{{ route('home') }}">
                            <x-icons.arrow-right class="h-6 w-6 transition"/>
                        </x-buttons.circle>

                        <x-buttons.circle href="{{ route('home') }}">
                            <x-icons.twitter class="h-6 w-6 transition"/>
                        </x-buttons.circle>

                        <x-buttons.circle href="{{ route('home') }}">
                            <x-icons.facebook class="h-6 w-6 transition"/>
                        </x-buttons.circle>
                    </div>
                </div>
                <div>
                    <x-post.header :post="$post" />

                    <div class="my-10 text-gray-900 dark:text-gray-100">
                        {!! $post->content !!}
                    </div>
                </div>

            </x-post.container>
        </x-containers.default>

        <x-containers.content>
            <x-blog.related-posts></x-blog.related-posts>
        </x-containers.content>
    </main>
@stop
