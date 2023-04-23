@extends('layouts.base')

@section('title', 'The title of the page')
@section('description', 'Description of the page')

@section('content')
    <x-containers.default>
        <livewire:blog.post-list :tag="$tag"/>
    </x-containers.default>
@stop
