@props([
    /** @var list<array{title: string, links: list<array{label: string, description: string, href: string, icon: string}>}> */
    'groups' => [],
    /** @var list<array{kind: string, title: string, excerpt: string|null, image_url: string|null, alt: string, date: string|null, date_iso: string|null, href: string}> */
    'featuredPosts' => [],
])

{{-- Panel desplegable del header (adaptado de Tailwind Plus "flyout menu"). Se abre con $data.menuOpen del <header>. --}}
<div
    id="site-menu"
    x-show="menuOpen"
    x-cloak
    x-on:click.outside="menuOpen = false"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1"
    {{ $attributes->class(['absolute inset-x-0 top-full z-40 mt-2']) }}
>
    <div class="max-h-[calc(100dvh-8rem)] overflow-y-auto rounded-2xl bg-white shadow-xl ring-1 ring-zinc-900/5">
        <div @class([
            'grid grid-cols-1 gap-x-8 gap-y-10 px-6 py-8 lg:px-8 lg:py-10',
            'lg:grid-cols-[3fr_2fr]' => count($featuredPosts) > 0,
        ])>
            <div class="grid grid-cols-2 gap-x-6 gap-y-8 sm:grid-cols-3 sm:gap-x-8">
                @foreach ($groups as $group)
                    <div>
                        <h3 class="text-sm/6 font-medium text-zinc-500">{{ $group['title'] }}</h3>
                        <div class="mt-4 flow-root sm:mt-6">
                            <div class="-my-2">
                                @foreach ($group['links'] as $link)
                                    <a
                                        href="{{ $link['href'] }}"
                                        wire:navigate
                                        class="group flex gap-x-3 rounded-lg py-2 text-sm/6 font-semibold text-zinc-900 transition hover:text-zinc-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:gap-x-4"
                                    >
                                        <x-icon :name="$link['icon']" class="mt-0.5 size-5 flex-none text-zinc-400 transition group-hover:text-zinc-900 sm:size-6" />
                                        <span class="min-w-0">
                                            <span class="block">{{ $link['label'] }}</span>
                                            <span class="hidden text-xs/5 font-normal text-zinc-500 sm:block">{{ $link['description'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if (count($featuredPosts) > 0)
                <div class="grid grid-cols-1 gap-8 border-t border-zinc-100 pt-8 sm:grid-cols-2 lg:border-0 lg:pt-0">
                    <h3 class="sr-only">{{ __('Posts destacados') }}</h3>
                    @foreach ($featuredPosts as $post)
                        <article class="relative isolate flex flex-col gap-y-4 rounded-lg has-[a:focus-visible]:outline-2 has-[a:focus-visible]:outline-offset-4 has-[a:focus-visible]:outline-zinc-900">
                            <div class="relative flex-none">
                                @if (filled($post['image_url']))
                                    <img
                                        src="{{ $post['image_url'] }}"
                                        alt="{{ $post['alt'] }}"
                                        class="aspect-2/1 w-full rounded-lg bg-zinc-100 object-cover sm:aspect-video"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                @else
                                    <div class="flex aspect-2/1 w-full items-center justify-center rounded-lg bg-brand-lime sm:aspect-video" aria-hidden="true">
                                        <x-icon name="notebook-pen" class="size-8 text-zinc-900" />
                                    </div>
                                @endif
                                <div class="absolute inset-0 rounded-lg ring-1 ring-zinc-900/10 ring-inset"></div>
                            </div>
                            <div>
                                <div class="flex items-center gap-x-3">
                                    @if (filled($post['date']))
                                        <time datetime="{{ $post['date_iso'] }}" class="text-sm/6 text-zinc-600">{{ $post['date'] }}</time>
                                    @endif
                                    <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-600">
                                        {{ $post['kind'] === 'image' ? __('Fotografía') : __('Nota') }}
                                    </span>
                                </div>
                                <h4 class="mt-2 text-sm/6 font-semibold text-zinc-900">
                                    <a href="{{ $post['href'] }}" wire:navigate class="outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        {{ $post['title'] }}
                                    </a>
                                </h4>
                                @if (filled($post['excerpt']))
                                    <p class="mt-1 line-clamp-2 text-sm/6 text-zinc-600">{{ $post['excerpt'] }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
