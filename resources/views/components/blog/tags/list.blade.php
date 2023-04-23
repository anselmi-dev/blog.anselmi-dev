<div {{ $attributes->merge(['class' => "flex flex-wrap"]) }}>
    @foreach ($tags as $tag)
        <x-blog.tags.tag :tag="$tag">
        </x-blog.tags.tag>
    @endforeach
</div>
