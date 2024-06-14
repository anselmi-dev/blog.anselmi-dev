@section('title', $post->title)

@section('description', $post->description)

<div class="flex flex-col mt-10 mx-auto">
    <x-containers.content>
        <x-post.container>
            <div class="flex sticky top-4 z-1 h-min">
                <div class="flex flex-col gap-3">
                    <x-buttons.circle href="{{ route('blog.index') }}" wire:navigate>
                        <x-icons.arrow-right class="h-6 w-6 transition"/>
                    </x-buttons.circle>

                    <livewire:likes-button :post="$post"/>
                </div>
            </div>
            <div>
                <x-post.header :post="$post" />
                <div class="my-10 text-gray-900 dark:text-gray-100">
                    {!! $post->content !!}
                </div>
            </div>

        </x-post.container>

        <x-blog.related-posts></x-blog.related-posts>

    </x-containers.content>
</div>
