<div class="pb-4 pt-4 sm:pt-6">
    @section('header-content')
        <x-hero-content>
            <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-folio-forest">
                {{ __('Fotografía') }}
            </p>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                {{ __('Galería') }}
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
                {{ __('Serie de tomas y notas visuales, agrupadas en colecciones. Abrí una para ver todo lo que tiene.') }}
            </p>
        </x-hero-content>
    @endsection

    <div class="mx-auto mt-10 max-w-8xl sm:mt-12">
        @if (count($entries) > 0)
            <div class="flex justify-end">
                <label class="inline-flex items-center gap-2 rounded-lg bg-zinc-100 px-3 py-1.5 text-sm">
                    <span class="text-zinc-400">{{ __('Ordenar') }}</span>
                    <select
                        wire:model.live="sort"
                        class="cursor-pointer appearance-none bg-transparent pr-1 font-medium text-zinc-900 focus:outline-none"
                    >
                        <option value="order">{{ __('Destacadas') }}</option>
                        <option value="recent">{{ __('Recientes') }}</option>
                        <option value="name">{{ __('Nombre') }}</option>
                    </select>
                    <x-icon name="chevron-down" class="pointer-events-none size-4 text-zinc-500" />
                </label>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($entries as $entry)
                    <x-gallery.collection-card :entry="$entry" wire:key="collection-{{ $entry['slug'] }}" />
                @endforeach
            </div>
        @else
            <p class="py-16 text-center text-sm text-zinc-500">{{ __('Todavía no hay colecciones publicadas.') }}</p>
        @endif
    </div>
</div>
