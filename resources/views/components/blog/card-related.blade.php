<article {{ $attributes->whereDoesntStartWith('post') }} >
    <div class="relative isolate flex flex-col gap-8 lg:flex-row">
        <div class="relative aspect-[16/9] sm:aspect-[2/1] lg:aspect-square lg:w-64 lg:shrink-0">
            <img src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=3603&amp;q=80" alt="" class="absolute inset-0 h-full w-full rounded-2xl bg-gray-50 object-cover">
            <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
        </div>
        <div class="space-y-2">
            <dl class="w-full">
                <dt class="sr-only">
                    {{ __('Published on') }}
                </dt>
                <dd class="text-base leading-6 text-gray-500 dark:text-gray-400">
                    <time datetime="{{ $post->published_at }}">
                        {{ $post->published_at->format('d M Y') }}
                    </time>
                </dd>
            </dl>
            <div class="space-y-5 xl:col-span-3">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-bold leading-8 tracking-tight">
                            <a class="text-gray-900 dark:text-gray-100" href="{{ $post->url}}">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <x-blog.tags.list :tags="$post->tags"/>
                    </div>
                    <div class="prose max-w-none text-gray-500 dark:text-gray-400" title="{{ $post->title }}">
                        {{ $post->description }}
                    </div>
                </div>
                <div class="text-base font-medium leading-6">
                    <a
                        class="text-primary-default hover:text-primary-default dark:hover:text-primary-default"
                        aria-label=""
                        href="{{ $post->url }}"
                        wire:navigate
                        >
                        {{ __('Read more') }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</article>
