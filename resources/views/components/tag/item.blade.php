@props([
    'tag',
    'count' => null
])

<a class="bg-gray-app hover:bg-app-primary rounded-sm px-2 mb-2 text-white | mr-1 text-sm font-medium uppercase leading-6 group/tag"
    href="{{ $tag->url }}"
    title="{{ $tag->description ?? $tag->name }}"
    wire:navigate
    >
    <span class="flex items-center space-x-1 relative">
        @if ($count)
            <span @class([
                "rounded-full text-xxs text-center opacity-70"
            ])>[<span>{{ $count }}</span>]</span>
        @endif

        <span>{{ $tag->name }}</span>
        <x-heroicon-o-arrow-long-right class="h-5 w-0 group-hover/tag:w-5 transition-all"/>
    </span>
</a>
