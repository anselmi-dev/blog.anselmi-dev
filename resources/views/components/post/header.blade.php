<header class="flex flex-col mb-10 gap-3">
    <div class="flex flex-col gap-3">
        <time datetime="{{ $post->published_at_for_humans }}" class="flex items-center dark:text-gray-500">
            <x-heroicon-s-chevron-double-right class="w-3" /> {{ $post->published_at_for_humans }}
        </time>
        <h1 class="text-4xl font-bold tracking-tight text-zinc-800 dark:text-zinc-100 sm:text-5xl mt-0">
            {{ $post->title }}
        </h1>
    </div>
    
    <x-tag.list :tags="$post->tags"/>
</header>
