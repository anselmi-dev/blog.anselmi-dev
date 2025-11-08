@props(['photo'])

<div class="relative overflow-hidden rounded shadow-lg group">
    <img src="{{ $photo->image?->getUrl('thumb') }}" alt="Nature" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <h3 class="text-2xl font-bold text-white">
                {{ $photo->title }}
            </h3>
            <p class="text-white mb-1">
                {{ $photo->description }}
            </p>
            <x-tag.list :tags="$photo->tags"/>
        </div>
    </div>
</div>