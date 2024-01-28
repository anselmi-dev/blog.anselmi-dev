<x-containers.content>
    <div class="relative columns-2 sm:columns-3 lg:columns-4 gap-4 lg:gap-8 space-y-4">
        @foreach ($photos as $photo)
            <x-gallery.card class="aspect-w-16 aspect-h-9" src="storage/{{ $photo->path }}"/>
        @endforeach

        <a href="blog-single.html" class="block text-dark font-bold text-xl mb-3.5">
            <span class="bg-gradient-to-r from-black/50 to-black/40 bg-[length:0px_10px] bg-left-bottom bg-no-repeat transition-[background-size] duration-500 hover:bg-[length:100%_3px] group-hover:bg-[length:100%_10px]">
            Stylish Kitchen And Dining Room With Functional Ideas
            </span>
            </a>
    </div>
</x-containers.content>
