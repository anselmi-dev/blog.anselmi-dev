<article {{ $attributes->whereDoesntStartWith('post') }}>
    <div class="relative isolate flex flex-col gap-8 lg:flex-row">
        <div class="space-y-5 xl:col-span-5">
            <div class="space-y-6">
                <div>
                    <dl>
                        <dt class="sr-only">
                            {{ __('Published on') }}
                        </dt>
                        <dd class="text-xs leading-1 text-gray-500 dark:text-gray-400">
                            <time datetime="{{ $post->published_at }}" class="flex items-center">
                                <x-heroicon-s-chevron-double-right class="w-3" /> {{ $post->published_at_for_humans }}
                            </time>
                        </dd>
                    </dl>

                    <h2 class="text-2xl font-oswald leading-8 tracking-tight relative">
                        <span class="font-oswald text-black dark:text-gray-100" href="{{ $post->url}}" title="{{ $post->title }}">
                            {{ $post->title }}
                        </span>
                        <svg class="rough-annotation" style="position: absolute; top: 0px; left: 0px; overflow: visible; pointer-events: none; width: 100px; height: 100px;"><path d="M46.11182293627588 70.29058579788959 C150.8009830699937 68.66634866807362, 255.37189445493735 70.89342766560216, 377.4085646215642 69.00228292681018" fill="none" stroke="currentColor" stroke-width="2" style="stroke-dashoffset: 331.304; stroke-dasharray: 331.304; animation: 500ms ease-out 0ms 1 normal forwards running rough-notation-dash;"></path></svg>
                    </h2>

                    <x-blog.tags.list class="mt-1" :tags="$post->tags"/>
                </div>
                <div class="font-roboto prose max-w-none text-black dark:text-gray-400" title="{{ $post->title }}">
                    {{ $post->description }}
                </div>
            </div>
            <div class="text-base font-medium leading-6">
                <a
                    class="group inline-block border-none px-2 py-1 text-black uppercase bg-app-default transition-all hover:text-white hover:bg-black _dark:hover:text-app-default"
                    href="{{ $post->url }}"
                    {{-- x-on:click="
                        if(!event.metaKey) {
                            event.preventDefault();
                            Alpine.navigate($el.getAttribute('href'));
                        }
                    " --}}
                    >
                    <span class="flex items-center space-x-2">
                        <span>{{ __('Read more') }}</span>
                        <x-heroicon-o-arrow-long-right class="h-5 w-0 group-hover:w-5 transition-all"/>
                    </span>
                </a>
            </div>
        </div>
    </div>
</article>
