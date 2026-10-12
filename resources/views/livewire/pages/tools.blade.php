@push('meta')
    <link href="https://fonts.bunny.net/css?family=fraunces:400i,500i,600" rel="stylesheet" />
    <noscript><style>[data-page-loader] { display: none !important; }</style></noscript>
@endpush

@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            {{ __('Servicios') }}
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            {{ __('Mis herramientas') }}
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            {{ __('Stack que uso día a día para diseñar, construir y desplegar productos web.') }}
        </p>
    </x-hero-content>
@endsection

@php
    /** Paletas de etiqueta: [fondo, manchas, texto, contenido del frasco]. Se repiten en orden. */
    $palettes = [
        ['#1f4d33', '#3f7a52', '#f5efe0', '#c99a4b'],
        ['#7a8f2a', '#a9b84a', '#1f2a12', '#b4893e'],
        ['#e3dccb', '#c9bfa6', '#2d3b2a', '#8f9b4b'],
        ['#a8462f', '#c96a4c', '#f8ecdc', '#d29a54'],
        ['#26405f', '#3f5f85', '#eef1f4', '#a8774a'],
        ['#5e2f45', '#8a4a66', '#f6e8ef', '#c08a5a'],
    ];
@endphp

<div class="pb-16 sm:pb-20 lg:pb-24">
    {{-- Pantalla de carga: barra central de 0 a 100 % --}}
    <div
        data-page-loader
        class="fixed inset-0 z-[100] flex items-center justify-center bg-brand-lime"
        role="progressbar"
        aria-busy="true"
        aria-label="{{ __('Cargando') }}"
        aria-valuemin="0"
        aria-valuemax="100"
    >
        <div class="flex w-[min(60vw,18rem)] flex-col items-center gap-4">
            <div class="h-px w-full overflow-hidden bg-zinc-900/15">
                <div data-page-loader-bar class="h-full w-full origin-left bg-zinc-900" style="transform: scaleX(0)"></div>
            </div>
            <p class="font-folio-mono text-xs tabular-nums tracking-widest text-zinc-900">
                <span data-page-loader-value>0</span>%
            </p>
        </div>
    </div>

    @if (count($tools) > 0)
        <section
            data-tools-wheel
            data-joined-header
            class="relative mx-auto max-w-8xl"
            style="height: calc(100svh + {{ max(0, count($tools) - 1) }} * 38svh);"
            aria-label="{{ __('Mis herramientas') }}"
        >
            <div
                data-tools-wheel-stage
                tabindex="0"
                class="sticky top-0 h-svh cursor-grab touch-pan-y overflow-hidden rounded-b-xl bg-brand-lime transition-colors duration-300 sm:rounded-b-2xl outline-none focus-visible:ring-2 focus-visible:ring-zinc-900/30"
                role="region"
                aria-roledescription="{{ __('carrusel') }}"
                aria-label="{{ __('Frascos con las herramientas que uso') }}"
            >
                <div data-tools-wheel-ring class="tools-wheel__ring">
                    @foreach ($tools as $index => $tool)
                        <div data-tools-wheel-item class="tools-wheel__item">
                            <div class="size-full transition-[filter] duration-500 [[data-active]>&]:drop-shadow-[0_24px_30px_rgb(60_40_20/0.25)]">
                                <x-tools.jar
                                    class="size-full text-[calc(var(--jar-w)*0.11)]"
                                    :name="$tool['name']"
                                    :category="$tool['category']"
                                    :icon="$tool['icon'] ?? null"
                                    :palette="$palettes[$index % count($palettes)]"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pointer-events-none absolute inset-x-0 bottom-0 flex flex-col items-center gap-4 px-6 pb-8 text-center sm:pb-10">
                    @foreach ($tools as $index => $tool)
                        <div
                            data-tools-wheel-detail
                            class="pointer-events-auto flex max-w-md flex-col items-center"
                            aria-live="polite"
                            @if ($index > 0) hidden @endif
                        >
                            <p class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">{{ $tool['category'] }}</p>
                            <h2 class="mt-1 font-[Fraunces,ui-serif,Georgia,serif] text-3xl italic text-zinc-900 sm:text-4xl">{{ $tool['name'] }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $tool['tagline'] }}</p>
                            @if (filled($tool['url']))
                                <a
                                    href="{{ $tool['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-zinc-900 underline decoration-zinc-300 underline-offset-4 transition hover:decoration-zinc-900"
                                >
                                    {{ __('Visitar sitio') }}
                                    <x-icon name="arrow-up-right" class="size-4" />
                                </a>
                            @endif
                        </div>
                    @endforeach

                    <div class="pointer-events-auto flex items-center gap-4">
                        <button
                            type="button"
                            data-tools-wheel-prev
                            class="inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-900/20 text-zinc-900 transition hover:border-zinc-900 hover:bg-zinc-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                            aria-label="{{ __('Herramienta anterior') }}"
                        >
                            <x-icon name="arrow-left" class="size-4" />
                        </button>
                        <span data-tools-wheel-counter class="min-w-16 font-folio-mono text-xs tabular-nums tracking-widest text-zinc-500">
                            01 / {{ str_pad((string) count($tools), 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <button
                            type="button"
                            data-tools-wheel-next
                            class="inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-900/20 text-zinc-900 transition hover:border-zinc-900 hover:bg-zinc-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                            aria-label="{{ __('Herramienta siguiente') }}"
                        >
                            <x-icon name="arrow-right" class="size-4" />
                        </button>
                    </div>
                </div>

                <p class="pointer-events-none absolute inset-x-0 top-5 text-center font-folio-mono text-[0.65rem] uppercase tracking-widest text-zinc-400 sm:top-6">
                    {{ __('Scrolleá o arrastrá para girar') }}
                </p>
            </div>
        </section>
    @endif
</div>
