@extends('layouts.base')

@section('title', '{{ $category->name }}')
@section('description', '{{ $category->short_description }}')

@section('content')
    <x-containers.default>
    </x-containers.default>
@stop
