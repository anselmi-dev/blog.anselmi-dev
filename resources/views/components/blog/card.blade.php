<article {{ $attributes->whereDoesntStartWith('post') }}>
    <div class="space-y-2 xl:grid xl:grid-cols-6 xl:items-baseline xl:space-y-0">
        <dl>
            <dt class="sr-only">
                {{ __('Published on') }}
            </dt>
            <dd class="text-base leading-6 text-gray-500 dark:text-gray-400">
                <time datetime="{{ $post->published_at }}">
                    {{ $post->published_at_for_humans }}
                </time>
            </dd>
        </dl>
        <div class="space-y-5 xl:col-span-5">
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
                    href="{{ $post->url }}">
                    {{ __('Read more') }} →
                </a>
            </div>
        </div>
    </div>
</article>
