<div {{ $attributes->merge(['class' => "flex flex-wrap"]) }}>
    @foreach ($tags as $tag)
        <x-blog.tags.box-tag :tag="$tag"/>
    @endforeach
</div>
