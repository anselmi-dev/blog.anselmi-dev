<header class="flex flex-col mb-10 gap-3">
    <div class="flex flex-col gap-3">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-800 dark:text-zinc-100 sm:text-5xl mt-0">
            {{ $post->title }}
        </h1>
        <time datetime="{{ $post->published_at_for_humans }}"
            class="_order-first flex items-center text-base text-zinc-400 dark:text-zinc-500">
            {{-- <span class="h-4 w-0.5 rounded-full bg-zinc-200 dark:bg-zinc-500"></span> --}}
            <span class="_ml-3">
                {{ $post->published_at_for_humans }}
            </span>
        </time>
    </div>
    <div>
        <x-blog.tags.list :tags="$post->tags"/>
    </div>
</header>
