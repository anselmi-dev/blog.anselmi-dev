<div>
    <x-slot name="header">
        <div class="flex items-center justify-between space-x-0.5">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Posts') }}
            </h2>
            <div>
                <x-button positive label="Nuevo" icon="plus" wire:navigate href="{{ route('admin.posts.create') }}"/>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <x-blog.list class="w-full relative">
                        @forelse ($posts as $key => $post)
                            <article
                                @class([
                                    "py-12" => $key > 0,
                                    "pb-12" => $key == 0,
                                ])
                                wire:key="{{ $post->id }}">
                                <div class="relative isolate flex flex-col gap-8 lg:flex-row">
                                    <div class="space-y-2 xl:col-span-5">
                                        <div class="space-y-1">
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
                                                aria-label=""
                                                href="{{ route('admin.posts.edit', $post->slug) }}"
                                                >
                                                <span class="flex items-center space-x-2">
                                                    <span>{{ __('Edit') }}</span>
                                                    <x-heroicon-o-arrow-long-right class="h-5 w-0 group-hover:w-5 transition-all"/>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <span class="w-full text-center my-10 py-4">SIN REGISTROS</span>
                        @endforelse

                        @if ($posts->hasMorePages() || !$posts->onFirstPage())
                            <div class="my-5 py-5 mx-auto">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    </x-blog.list>
                </div>
            </div>
        </div>
    </div>
</div>
