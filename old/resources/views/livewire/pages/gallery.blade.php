<x-containers.content>
    <div class="relative columns-2 sm:columns-3 lg:columns-4 gap-4 lg:gap-8 space-y-4">
        @foreach ($photos as $photo)
            <x-gallery.card-photo :photo="$photo"/>
            {{-- <x-gallery.card class="aspect-w-16 aspect-h-9" src="{{ $photo->cover?->getUrl('thumb') }}"/> --}}
        @endforeach
    </div>
</x-containers.content>
