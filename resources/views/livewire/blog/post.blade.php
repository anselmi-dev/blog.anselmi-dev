@section('title', $post->title)

@section('description', $post->description)

@push('scripts')
    <script>
        console.log('highlight.js loaded');
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        });
    </script>
    <style>
        pre {
            white-space: pre-wrap;
        }
    </style>

@endpush

<div class="flex flex-col mt-10 mx-auto">
    <x-containers.content>
        <x-post.container>
            {{--
            <div class="absolute h-full -left-2 md:-left-6">
                <div class="flex sticky top-4 z-1 h-min">
                    <div class="flex flex-col gap-2">
                        <x-buttons.circle href="{{ route('blog.index') }}" wire:navigate>
                            <x-icons.arrow-right class="h-6 w-6 transition"/>
                        </x-buttons.circle>

                        <livewire:likes-button :post="$post"/>
                    </div>
                </div>
            </div>
            --}}
            <div class="flex flex-col space-y-5">
                <x-post.header :post="$post" />
                <img src="https://i.pinimg.com/736x/66/4a/08/664a08babbe3b89b266523e280357d9e.jpg" alt="">
                <div class="text-gray-900 dark:text-gray-100 | space-y-5">
                    @foreach ($post->content as $content)
                        @switch($content['type'])
                            @case('view')
                                @includeIf('components.post.contents.' . $content['view'])
                                @break
                            @case('richEditor')
                                <div class="white-space: break-spaces">
                                    {!! str($content['richEditor'])->sanitizeHtml() !!}
                                </div>
                                @break
                            @case('image')
                                {{-- <img src="{{ $content['image'] }}" alt="{{ $content['alt'] }}" class="w-full h-auto"> --}}
                                @break
                            @default
                        @endswitch
                    @endforeach
                </div>
            </div>

        </x-post.container>

        {{-- <x-blog.related-posts :post="$post"></x-blog.related-posts> --}}

    </x-containers.content>
</div>
