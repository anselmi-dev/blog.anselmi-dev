@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Servicios
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Mis herramientas
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            Stack que uso día a día para diseñar, construir y desplegar productos web.
        </p>
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <div
        class="mx-auto grid max-w-8xl grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.06"
    >
        @foreach ($tools as $tool)
            <a
                href="{{ $tool['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                data-reveal-item
                class="group flex min-h-[11rem] flex-col justify-between rounded-2xl border border-zinc-200 bg-brand-lime-50 p-5 transition hover:border-zinc-900/25 hover:bg-brand-lime-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:p-6"
            >
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <p class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">
                            {{ $tool['category'] }}
                        </p>
                        @if (! empty($tool['icon']))
                            <span
                                class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl border border-zinc-200 bg-white"
                                aria-hidden="true"
                            >
                                <img
                                    src="https://cdn.simpleicons.org/{{ $tool['icon'] }}/171717"
                                    alt=""
                                    class="size-5"
                                    width="20"
                                    height="20"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </span>
                        @endif
                    </div>
                    <h2 class="mt-3 text-xl font-bold tracking-tight text-zinc-900">
                        {{ $tool['name'] }}
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                        {{ $tool['tagline'] }}
                    </p>
                </div>
                <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-zinc-900">
                    Visitar sitio
                    <x-icon name="arrow-up-right" class="size-4 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </span>
            </a>
        @endforeach
    </div>
</div>
