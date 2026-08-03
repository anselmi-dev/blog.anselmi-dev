@php
    $projectImages = collect($project['images'] ?? [])
        ->map(function (array $image) {
            return [
                'src' => $image['src']
                    ?? sprintf(
                        'https://picsum.photos/seed/%s/%d/%d',
                        $image['seed'] ?? 'project',
                        1600,
                        900
                    ),
                'alt' => $image['alt'] ?? ($project['title'] ?? ''),
            ];
        })
        ->values()
        ->all();

    $sections = collect($project['sections'] ?? [])->values();
    $leadSection = $sections->first();
    $restSections = $sections->slice(1)->values();
@endphp

@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Proyecto · {{ $project['index'] ?? '' }}
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-5xl">
            {{ $project['title'] ?? '' }}
        </h1>
        @if (! empty($project['excerpt']))
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
                {{ $project['excerpt'] }}
            </p>
        @endif
        @if (! empty($project['tags']))
            <ul class="mt-6 flex flex-wrap gap-2">
                @foreach ($project['tags'] as $tag)
                    <li class="rounded-full border border-zinc-900/15 bg-white/60 px-3 py-1 text-xs font-semibold tracking-wide text-zinc-800">
                        {{ $tag }}
                    </li>
                @endforeach
            </ul>
        @endif
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <div
        class="mx-auto max-w-8xl"
        data-reveal="fade-up"
        data-reveal-stagger="0.1"
        data-reveal-duration="0.85"
    >
        <a
            href="{{ route('projects') }}"
            wire:navigate
            data-reveal-item
            class="mb-8 inline-flex items-center gap-2 text-sm font-medium text-zinc-600 transition hover:text-zinc-900"
        >
            <x-icon name="arrow-left" class="size-4 shrink-0" />
            Volver a proyectos
        </a>

        {{-- Meta --}}
        <dl
            data-reveal-item
            class="mb-10 grid grid-cols-2 gap-6 border-y border-zinc-200 py-6 sm:grid-cols-4 sm:gap-8"
        >
            <div>
                <dt class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">Rol</dt>
                <dd class="mt-1.5 text-sm font-semibold text-zinc-900">{{ $project['role'] ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">Año</dt>
                <dd class="mt-1.5 text-sm font-semibold text-zinc-900">{{ $project['year'] ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">Cliente</dt>
                <dd class="mt-1.5 text-sm font-semibold text-zinc-900">{{ $project['client'] ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">Sitio</dt>
                <dd class="mt-1.5 text-sm font-semibold text-zinc-900">
                    @if (! empty($project['url']))
                        <a
                            href="{{ $project['url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="underline decoration-zinc-300 underline-offset-4 transition hover:decoration-zinc-900"
                        >
                            Visitar
                        </a>
                    @else
                        Próximamente
                    @endif
                </dd>
            </div>
        </dl>

        {{-- Mobile: carrusel + lead side by side · Desktop: carrusel full, texto debajo --}}
        <div
            data-reveal-item
            class="grid grid-cols-1 items-start gap-3 sm:gap-4 lg:grid-cols-2 lg:gap-0"
        >
            @if (count($projectImages) > 0)
                <div
                    class="relative col-span-1 overflow-hidden rounded-2xl bg-brand-lime sm:rounded-3xl lg:col-span-full"
                    x-data="{
                        active: 0,
                        images: {{ Js::from($projectImages) }},
                        timer: null,
                        delay: 4000,
                        remaining: null,
                        startedAt: null,
                        paused: true,
                        hovering: false,
                        progressKey: 0,
                        get nextIndex() {
                            return (this.active + 1) % this.images.length;
                        },
                        get prevIndex() {
                            return (this.active - 1 + this.images.length) % this.images.length;
                        },
                        go(index) {
                            if (this.images.length < 2) return;
                            this.active = index;
                            this.remaining = null;
                            this.clearTimer();
                            if (! this.hovering) {
                                this.start();
                            } else {
                                this.progressKey++;
                                this.paused = true;
                            }
                        },
                        next() {
                            this.go(this.nextIndex);
                        },
                        prev() {
                            this.go(this.prevIndex);
                        },
                        start() {
                            this.clearTimer();
                            if (this.images.length < 2) return;
                            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

                            if (this.remaining === null) {
                                this.progressKey++;
                            }

                            this.paused = false;
                            this.startedAt = Date.now();
                            const wait = this.remaining ?? this.delay;

                            this.timer = setTimeout(() => {
                                this.remaining = null;
                                this.active = this.nextIndex;
                                this.start();
                            }, wait);
                        },
                        stop() {
                            if (this.timer && this.startedAt !== null) {
                                const elapsed = Date.now() - this.startedAt;
                                const base = this.remaining ?? this.delay;
                                this.remaining = Math.max(0, base - elapsed);
                            }
                            this.clearTimer();
                            this.paused = true;
                        },
                        clearTimer() {
                            if (this.timer) {
                                clearTimeout(this.timer);
                                this.timer = null;
                            }
                            this.startedAt = null;
                        },
                    }"
                    x-init="start()"
                    @mouseenter="hovering = true; stop()"
                    @mouseleave="hovering = false; start()"
                >
                    <div class="aspect-[4/5] w-full sm:aspect-[16/9]">
                        <img
                            :src="images[active].src"
                            :alt="images[active].alt"
                            src="{{ $projectImages[0]['src'] }}"
                            alt="{{ $projectImages[0]['alt'] }}"
                            class="size-full object-cover object-top transition-opacity duration-300"
                            loading="eager"
                            decoding="async"
                        />
                    </div>

                    @if (count($projectImages) > 1)
                        {{-- Side arrows --}}
                        <div class="pointer-events-none absolute inset-y-0 left-0 right-0 z-10 flex items-center justify-between px-2 sm:px-4">
                            <button
                                type="button"
                                class="pointer-events-auto inline-flex size-9 items-center justify-center rounded-full border border-zinc-900/80 bg-white/90 text-zinc-900 shadow-md backdrop-blur-sm transition hover:scale-105 hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:size-11"
                                @click.stop="prev()"
                                aria-label="Imagen anterior"
                            >
                                <x-icon name="arrow-left" class="size-4 sm:size-5" />
                            </button>
                            <button
                                type="button"
                                class="pointer-events-auto inline-flex size-9 items-center justify-center rounded-full border border-zinc-900/80 bg-white/90 text-zinc-900 shadow-md backdrop-blur-sm transition hover:scale-105 hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:size-11"
                                @click.stop="next()"
                                aria-label="Imagen siguiente"
                            >
                                <x-icon name="arrow-right" class="size-4 sm:size-5" />
                            </button>
                        </div>

                        {{-- Thumbs --}}
                        <div class="pointer-events-none absolute inset-x-0 bottom-0 z-10 bg-gradient-to-t from-black/40 to-transparent pt-10 sm:pt-24">
                            <div class="pointer-events-auto flex justify-end gap-1.5 p-2 sm:gap-3 sm:p-5">
                                @foreach ($projectImages as $index => $image)
                                    <button
                                        type="button"
                                        x-show="active !== {{ $index }}"
                                        x-cloak
                                        @click.stop="go({{ $index }})"
                                        class="relative size-9 shrink-0 overflow-hidden rounded-lg bg-white/15 shadow-lg transition hover:scale-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:size-14 sm:rounded-xl md:size-24"
                                        aria-label="Ver captura {{ $index + 1 }}"
                                    >
                                        <img
                                            src="{{ $image['src'] }}"
                                            alt="{{ $image['alt'] }}"
                                            class="size-full object-cover object-top"
                                            loading="lazy"
                                            decoding="async"
                                        />

                                        {{-- Base border --}}
                                        <span class="pointer-events-none absolute inset-0 rounded-[inherit] border-2 border-brand-lime"></span>

                                        {{-- Progress border on next slide --}}
                                        <template x-if="nextIndex === {{ $index }}">
                                            <span class="pointer-events-none absolute inset-0 text-brand-lime">
                                                <template x-for="k in [progressKey]" :key="k">
                                                    <svg
                                                        class="absolute inset-0 size-full overflow-visible"
                                                        viewBox="0 0 100 100"
                                                        preserveAspectRatio="none"
                                                        aria-hidden="true"
                                                    >
                                                        <rect
                                                            class="project-carousel-progress"
                                                            :class="{ 'is-paused': paused }"
                                                            x="1.5"
                                                            y="1.5"
                                                            width="97"
                                                            height="97"
                                                            rx="14"
                                                            ry="14"
                                                            pathLength="100"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="3.5"
                                                            stroke-linecap="round"
                                                            vector-effect="non-scaling-stroke"
                                                            :style="'animation-duration:' + delay + 'ms'"
                                                        />
                                                    </svg>
                                                </template>
                                            </span>
                                        </template>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($leadSection)
                <section class="col-span-1 flex flex-col justify-center lg:col-span-full lg:mx-auto lg:mt-14 lg:max-w-3xl">
                    <h2 class="text-base font-bold tracking-tight text-zinc-900 sm:text-2xl lg:text-3xl">
                        {{ $leadSection['title'] }}
                    </h2>
                    <div class="mt-2 space-y-2 text-xs leading-relaxed text-zinc-600 sm:mt-4 sm:space-y-3 sm:text-sm lg:mt-5 lg:space-y-4 lg:text-base">
                        @foreach ($leadSection['body'] ?? [] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>

    {{-- Rest of sections --}}
    @if ($restSections->isNotEmpty())
        <div
            class="mx-auto mt-10 max-w-3xl space-y-12 sm:mt-14 lg:mt-16"
            data-reveal="fade-up"
            data-reveal-scroll
            data-reveal-stagger="0.12"
        >
            @foreach ($restSections as $section)
                <section data-reveal-item>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
                        {{ $section['title'] }}
                    </h2>
                    <div class="mt-5 space-y-4 text-base leading-relaxed text-zinc-600">
                        @foreach ($section['body'] ?? [] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif

    {{-- Prev / Next --}}
    <nav
        class="mx-auto mt-16 grid max-w-8xl gap-3 border-t border-zinc-200 pt-10 sm:mt-20 sm:grid-cols-2 sm:gap-4"
        aria-label="Otros proyectos"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.1"
    >
        @if ($previous)
            <a
                href="{{ route('projects.show', $previous['slug']) }}"
                wire:navigate
                data-reveal-item
                class="group flex flex-col rounded-2xl border border-zinc-200 bg-brand-lime-50 px-5 py-5 transition hover:border-zinc-900/30 hover:bg-brand-lime-100 sm:px-6 sm:py-6"
            >
                <span class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">Anterior</span>
                <span class="mt-2 flex items-center gap-3 text-lg font-bold text-zinc-900">
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-full border border-zinc-900 bg-transparent transition group-hover:-translate-x-0.5">
                        <x-icon name="arrow-left" class="size-4 shrink-0" />
                    </span>
                    {{ $previous['title'] }}
                </span>
            </a>
        @else
            <div class="hidden sm:block" aria-hidden="true"></div>
        @endif

        @if ($next)
            <a
                href="{{ route('projects.show', $next['slug']) }}"
                wire:navigate
                data-reveal-item
                class="group flex flex-col rounded-2xl border border-zinc-200 bg-brand-lime-50 px-5 py-5 text-right transition hover:border-zinc-900/30 hover:bg-brand-lime-100 sm:items-end sm:px-6 sm:py-6"
            >
                <span class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">Siguiente</span>
                <span class="mt-2 flex items-center gap-3 text-lg font-bold text-zinc-900">
                    {{ $next['title'] }}
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-full border border-zinc-900 bg-transparent transition group-hover:translate-x-0.5">
                        <x-icon name="arrow-right" class="size-4 shrink-0" />
                    </span>
                </span>
            </a>
        @endif
    </nav>
</div>
