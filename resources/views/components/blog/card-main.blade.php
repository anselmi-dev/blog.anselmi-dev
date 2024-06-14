<a {{ $attributes->merge(['class' => "w-full relative rounded z-0"]) }} href="{{ $post->url }}" wire:navigate>
    <div class="absolute left-0 top-0 w-full h-full z-10"
        style="background-image: linear-gradient(180deg,transparent,rgba(0,0,0,.7));"></div>
    @if ($post->cover)
        <img src="{{ $post->cover->getUrl('thumb')  }}"
            class="absolute left-0 top-0 w-full h-full rounded z-0 object-cover">
    @endif
    <div class="p-4 absolute bottom-0 left-0 z-20">
        @foreach ($post->tags as $tag)
            <span class="px-4 py-1 bg-black text-gray-200 inline-flex items-center justify-center mb-2">
                {{ $tag->name }}
            </span>
        @endforeach
        <h2 class="text-3xl font-semibold text-gray-100 leading-tight">
            {{ $post->title }}
        </h2>
        <div class="flex mt-3">
            <dl>
                <dt class="sr-only">
                    {{ __('Published on') }}
                </dt>
                <dd class="text-xs leading-1  dark:text-gray-400">
                    <time datetime="{{ $post->published_at }}" class="flex items-center">
                        <x-heroicon-s-chevron-double-right class="w-3" /> {{ $post->published_at_for_humans }}
                    </time>
                </dd>
            </dl>
        </div>
    </div>
</a>
