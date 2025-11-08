@extends('layouts.base')

@section('title', null)
@section('description', null)

@section('content')
    <x-containers.default class="h-full flex items-center">
        <div class="flex items-center justify-center flex-wrap gap-y-4 gap-x-2">
            @foreach ($tags as $tag)
                <x-blog.tags.tag :tag="$tag" :posts_count="$tag->posts_count"/>
            @endforeach
        </div>
    </x-containers.default>
@stop
