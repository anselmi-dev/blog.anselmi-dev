@extends('layouts.base')

@section('title', null)
@section('description', null)

@section('content')
    <x-containers.default>
        <livewire:blog.post-list/>
    </x-containers.default>
@stop
