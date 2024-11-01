@props(['tags'])

<div {{ $attributes->merge(['class' => "flex flex-wrap"]) }}>
    @foreach ($tags as $tag)
        <x-tag.item :tag="$tag"/>
    @endforeach
</div>
