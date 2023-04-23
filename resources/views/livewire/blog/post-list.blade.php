<x-blog.list>
    @forelse ($posts as $post)
        <x-blog.card :post="$post" class="py-12"></x-blog.card>
    @empty
        <span class="w-full text-center my-10 py-4">SIN REGISTROS</span>
    @endforelse

    @if ($posts->hasMorePages() || !$posts->onFirstPage())
        <div class="my-5 py-5 w-full">
            {{ $posts->links() }}
        </div>
    @endif
</x-blog.list>
