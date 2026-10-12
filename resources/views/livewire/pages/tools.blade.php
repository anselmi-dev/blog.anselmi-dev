@push('meta')
    <link href="https://fonts.bunny.net/css?family=fraunces:400i,500i,600" rel="stylesheet" />
    @if ($showLoader)
        <noscript><style>[data-page-loader] { display: none !important; }</style></noscript>
    @endif
@endpush

@php
    $jarsData = collect($tools)->values()->map(fn (array $tool, int $index): array => [
        'name' => $tool['name'],
        'category' => $tool['category'],
        'tagline' => $tool['tagline'],
        'description' => $tool['description'] ?? null,
        'url' => $tool['url'],
        'icon' => $tool['icon'] ?? null,
        'palette' => $tool['palette'] ?? \App\Support\JarPalette::fallback($index),
        'shape' => $tool['shape'] ?? \App\Support\JarShape::fallback($index)->value,
    ])->all();
@endphp

<div class="pb-16 sm:pb-20 lg:pb-24">
    {{-- Pantalla de carga: barra central de 0 a 100 %. Se activa desde Filament (Ajustes → Sitio). --}}
    @if ($showLoader)
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
    @endif

    @if (count($tools) > 0)
        <section
            data-tools-wheel
            data-joined-header
            data-tools-wheel-jars="{{ json_encode($jarsData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}"
            class="relative mx-auto max-w-8xl"
            style="height: calc(100svh + {{ count($tools) }} * 38svh);"
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
                {{-- Escena 3D (three.js). Si WebGL no está disponible queda la versión HTML/CSS de abajo. --}}
                <div data-tools-wheel-canvas class="absolute inset-0" aria-hidden="true"></div>

                <div data-tools-wheel-ring class="tools-wheel__ring">
                    @foreach ($tools as $index => $tool)
                        <div data-tools-wheel-item class="tools-wheel__item">
                            <div class="size-full transition-[filter] duration-500 [[data-active]>&]:drop-shadow-[0_24px_30px_rgb(60_40_20/0.25)]">
                                <x-tools.jar
                                    class="size-full text-[calc(var(--jar-w)*0.11)]"
                                    :name="$tool['name']"
                                    :category="$tool['category']"
                                    :icon="$tool['icon'] ?? null"
                                    :palette="$tool['palette'] ?? \App\Support\JarPalette::fallback($index)"
                                    :shape="$tool['shape'] ?? \App\Support\JarShape::fallback($index)->value"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div data-tools-wheel-footer class="pointer-events-none absolute inset-x-0 bottom-0 flex flex-col items-center gap-4 px-6 pb-8 text-center transition-opacity duration-300 sm:pb-10">
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
                            <button
                                type="button"
                                data-tools-wheel-open="{{ $index }}"
                                hidden
                                class="mt-2 cursor-pointer text-xs font-medium text-zinc-500 underline decoration-zinc-300 underline-offset-4 transition hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                            >
                                {{ __('Ver frasco en detalle') }}
                            </button>
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

                {{-- Panel de detalle al seleccionar un frasco --}}
                <div
                    data-tools-wheel-panel
                    hidden
                    class="tools-wheel__panel absolute inset-x-4 bottom-4 z-10 rounded-2xl bg-white/85 p-6 shadow-xl ring-1 ring-zinc-900/5 backdrop-blur-md sm:inset-x-auto sm:bottom-auto sm:right-8 sm:top-1/2 sm:w-[min(24rem,40%)] sm:-translate-y-1/2 sm:p-8 lg:right-16"
                    role="dialog"
                    aria-modal="false"
                    aria-labelledby="tools-wheel-panel-title"
                >
                    <button
                        type="button"
                        data-tools-wheel-close
                        class="absolute right-4 top-4 inline-flex size-9 cursor-pointer items-center justify-center rounded-full border border-zinc-900/15 text-zinc-700 transition hover:border-zinc-900 hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                        aria-label="{{ __('Cerrar') }}"
                    >
                        <x-icon name="x" class="size-4" />
                    </button>
                    <p data-panel-index class="font-folio-mono text-[0.7rem] tabular-nums tracking-widest text-zinc-400"></p>
                    <p data-panel-category class="mt-4 font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500"></p>
                    <h2 id="tools-wheel-panel-title" data-panel-name class="mt-1 font-[Fraunces,ui-serif,Georgia,serif] text-4xl italic leading-none text-zinc-900 sm:text-5xl"></h2>
                    <p data-panel-tagline class="mt-3 text-base font-medium leading-snug text-zinc-800"></p>
                    <p data-panel-description class="mt-3 text-sm leading-relaxed text-zinc-600"></p>
                    <a
                        data-panel-link
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-6 inline-flex items-center gap-2 rounded-full bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                    >
                        {{ __('Visitar sitio') }}
                        <x-icon name="arrow-up-right" class="size-4" />
                    </a>
                </div>

                {{-- Título de la página, arriba de los frascos --}}
                <div data-tools-wheel-title class="pointer-events-none absolute inset-x-0 top-0 z-[1] px-6 pt-6 text-center transition-opacity duration-300 sm:pt-10 lg:pt-12">
                    <p class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">
                        {{ __('Servicios') }}
                    </p>
                    <h1 class="mt-2 font-[Fraunces,ui-serif,Georgia,serif] text-4xl italic leading-none text-zinc-900 sm:text-5xl lg:text-6xl">
                        {{ __('Mis herramientas') }}
                    </h1>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-zinc-600 sm:text-base">
                        {{ __('Cada frasco es una herramienta de mi stack: lo que uso día a día para diseñar, construir y desplegar productos web. Tocá uno para ver qué es y para qué la uso.') }}
                    </p>
                    <p class="mt-3 font-folio-mono text-[0.65rem] uppercase tracking-widest text-zinc-400">
                        {{ __('Scrolleá o arrastrá para girar') }}
                    </p>
                </div>
            </div>
        </section>
    @endif
</div>
