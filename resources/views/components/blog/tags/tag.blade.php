@props([
    'tag',
    'posts_count' => null
])

<a class="mr-3 text-sm font-medium uppercase text-primary-default hover:text-primary-default dark:hover:text-primary-default"
    href="{{ $tag->url }}"
    title="{{ $tag->name }}">
    <span>{{ $tag->name }}</span>
    @if (!is_null($posts_count))
        <span @class([
            "px-2 rounded-full",
            "bg-primary-default text-white" => $posts_count,
            "bg-gray-200 dark:bg-gray-800 text-gray-300" => !$posts_count,
        ])>{{ $posts_count }}</span>
    @endif
</a>
