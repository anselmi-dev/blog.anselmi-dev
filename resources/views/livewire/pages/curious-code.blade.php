@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            {{ __('Curiosidades') }}
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            {{ __('Código curioso') }}
        </h1>
        <p class="mt-4 max-w-4xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            {{ __('Proyectos de otras personas que me llamaron la atención y quiero dar a conocer.') }}
        </p>
        <p class="mt-2 max-w-4xl text-sm leading-relaxed text-zinc-500">
            {{ __('No son proyectos míos: son una forma de mostrar mi curiosidad por lo que hacen otros desarrolladores. Cada ficha enlaza al proyecto original.') }}
        </p>
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <div class="mx-auto max-w-8xl">
        <div
            class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 4xl:grid-cols-4"
            data-reveal="fade-up"
            data-reveal-scroll
            data-reveal-stagger="0.08"
        >
            @foreach ($projects as $slug => $project)
                <x-cards.projects
                    data-reveal-item
                    :href="localized_route('curious-code.show', $slug)"
                    wire:navigate
                    :title="$project['title']"
                    :description="$project['excerpt']"
                    :index="$project['index']"
                    :color="$project['color']"
                    :tags="$project['tags']"
                    :image="$project['image'] ?? null"
                />
            @endforeach
        </div>
    </div>
</div>
