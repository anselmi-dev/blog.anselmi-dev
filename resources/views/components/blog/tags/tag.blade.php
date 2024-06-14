@props([
    'tag',
    'posts_count' => null
])

<a class="mr-3 text-sm font-medium uppercase text-app-default hover:text-app-default dark:hover:text-app-default"
    href="{{ $tag->url }}"
    title="{{ $tag->name }}">
    <span>{{ $tag->name }}</span>
    @if (!is_null($posts_count))
        <span @class([
            "px-2 rounded-full",
            "bg-app-default text-white" => $posts_count,
            "bg-gray-200 dark:bg-gray-800 text-gray-300" => !$posts_count,
        ])>{{ $posts_count }}</span>
    @endif
</a>
