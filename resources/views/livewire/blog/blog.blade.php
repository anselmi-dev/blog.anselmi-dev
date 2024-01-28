@section('title', 'Blog')

@section('description', 'Blog de Carlos')

<x-containers.default>
    <x-blog.list class="w-full _max-w-4xl mx-auto relative">
        {{-- <div class="blur-lg pointer-events-none overflow-hidden absolute top-0 left-0 h-full w-full z-1 bg-black"></div> --}}
        @forelse ($posts as $key => $post)
            <x-blog.card :post="$post" :wire:key="$post->id" class="py-12"></x-blog.card>
        @empty
            <span class="w-full text-center my-10 py-4">SIN REGISTROS</span>
        @endforelse

        @if ($posts->hasMorePages() || !$posts->onFirstPage())
            <div class="my-5 py-5 mx-auto">
                {{ $posts->links() }}
            </div>
        @endif
    </x-blog.list>
</x-containers.default>
