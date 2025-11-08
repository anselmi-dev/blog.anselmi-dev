@props([
    'type' => 'button'
])
@php($tag = $attributes->has('href') ? 'a' : 'button')

<{{ $tag }}
    {{ $attributes->merge(['class' => "x-box-shadow-circle"]) }}>
    {{ $slot }}
</{{ $tag }}>
