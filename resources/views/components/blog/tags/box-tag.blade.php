@props([
    'tag',
    'posts_count' => null
])

<a class="bg-black px-2 mb-2 text-white | mr-2 text-sm font-medium uppercase leading-6 group"
    href="{{ $tag->url }}"
    title="{{ $tag->name }}"
    wire:navigate
    >
    <span class="flex items-center space-x-1 relative">
        @if ($posts_count)
            <span @class([
                "rounded-full text-xxs text-center opacity-70"
            ])>[<span>{{ $posts_count }}</span>]</span>
        @endif
        <span>{{ $tag->name }}</span>
        <x-heroicon-o-arrow-long-right class="h-5 w-0 group-hover:w-5 transition-all"/>
    </span>
</a>
