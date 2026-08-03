@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Portafolio
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Proyectos
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            Selección de trabajos recientes. Cada pieza tiene su propia página con contexto, stack y capturas.
        </p>
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <div class="mx-auto max-w-8xl">
        <div
            class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4"
            data-reveal="fade-up"
            data-reveal-scroll
            data-reveal-stagger="0.08"
        >
            @foreach ($projects as $slug => $project)
                <x-cards.projects
                    data-reveal-item
                    :href="route('projects.show', $slug)"
                    wire:navigate
                    :title="$project['title']"
                    :description="$project['excerpt']"
                    :index="$project['index']"
                    :color="$project['color']"
                    :tags="$project['tags']"
                />
            @endforeach
        </div>
    </div>
</div>
