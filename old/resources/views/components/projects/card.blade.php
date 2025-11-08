@props(['project'])

<a
    href="{{ $project['url'] }}"
    wire:navigate
    class="expanding-cards--card active | h-[200px] md:h-[200px] w-full bg-gray-200 flex-1 transition-all hover:flex-9 lg:hover:flex-3 hover:bg-gray-300 duration-300 ease-in-out group">

    <img src="{{ $project['bg'] }}" alt="BG Project"
        class="object-cover min-h-full min-w-full grayscale group-hover:grayscale-0 transition-all duration-300 ease-in-out">

    <div class="absolute top-0 p-5 w-full flex justify-between max-h-20">
        <div class="w-full">
            <img src="{{ $project['logo'] }}" alt="Logo Project"
                class="w-auto transition-all max-h-full block">
        </div>
    </div>

    <x-card.external-link/>

    <div class="details">
        <span class="info flex flex-col space-y-1 top-0">
            {{-- <p>{{ $project['description'] }}</p> --}}
            @isset($project['tags'])
                <div class="flex flex-wrap gap-0.5">
                    @foreach($project['tags'] as $tag)
                        <x-projects.tag>
                            {{ $tag }}
                        </x-projects.tag>
                    @endforeach
                </div>
            @endisset
        </span>
    </div>
</a>