@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            {{ __('Código curioso') }} · {{ $project['index'] ?? '' }}
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-5xl">
            {{ $project['title'] ?? '' }}
        </h1>
        @if (! empty($project['excerpt']))
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
                {{ $project['excerpt'] }}
            </p>
        @endif
        <p class="mt-4 max-w-2xl rounded-2xl border border-zinc-900/10 bg-white/50 px-4 py-3 text-sm leading-relaxed text-zinc-600">
            {{ __('Este no es un proyecto mío: lo comparto porque me pareció curioso y quiero darlo a conocer. Todo el crédito es de su autor.') }}
        </p>
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
        data-reveal-duration="0.85"
    >
        <a
            href="{{ localized_route('curious-code') }}"
            wire:navigate
            data-reveal-item
            class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 transition hover:text-zinc-900"
        >
            <x-icon name="arrow-left" class="size-4 shrink-0" />
            {{ __('Volver a código curioso') }}
        </a>
    </div>

    @if (($project['demo'] ?? null) === 'ascii-ocean')
        <div class="my-8" data-ascii-ocean-root>
            <div class="-mx-4 overflow-hidden bg-black sm:-mx-6 lg:-mx-8">
                <canvas
                    data-ascii-ocean
                    role="img"
                    aria-label="{{ __('Tiburones y peces dibujados en ASCII') }}"
                    class="block h-[min(72vh,36rem)] w-full touch-none font-folio-mono"
                ></canvas>
            </div>
            <div class="mx-auto mt-3 max-w-8xl">
                <p class="text-xs text-zinc-500">
                    {{ __('Elegí hasta cuatro especies: la primera queda más grande. La quinta reemplaza a la más antigua. Mové el mouse sobre el agua o hacé clic para chapotear.') }}
                </p>
                <div class="mt-3 flex flex-wrap gap-2" role="group" aria-label="{{ __('Especies de tiburón') }}">
                    @foreach ([
                        'classic' => 'Clásico',
                        'hammerhead' => 'Martillo',
                        'zebra' => 'Cebra',
                        'tiger' => 'Tigre',
                        'greathammer' => 'Gran martillo',
                        'mako' => 'Mako',
                        'thresher' => 'Zorro',
                        'nurse' => 'Nodriza',
                    ] as $kind => $label)
                        <button
                            type="button"
                            data-ascii-ocean-kind="{{ $kind }}"
                            aria-pressed="{{ $kind === 'classic' ? 'true' : 'false' }}"
                            class="rounded-full border border-zinc-300 px-3 py-1 text-xs font-semibold text-zinc-700 transition hover:border-zinc-900 aria-pressed:border-zinc-900 aria-pressed:bg-zinc-900 aria-pressed:text-white"
                        >
                            {{ __($label) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif (! empty($project['ascii_art']))
        <div
            class="mx-auto my-8 max-w-8xl"
            data-reveal="fade-up"
            data-reveal-duration="0.85"
        >
            <div data-reveal-item>
                <div
                    role="img"
                    aria-label="{{ __('Ilustración ASCII de un tiburón') }}"
                    class="overflow-x-auto rounded-2xl border border-zinc-800 bg-zinc-950 p-4 shadow-inner"
                >
                    <pre
                        aria-hidden="true"
                        class="m-0 w-fit select-none whitespace-pre font-mono leading-[1.15] text-emerald-400"
                        style="font-size: clamp(6px, 1.9vw, 13px);"
                    >{{ $project['ascii_art'] }}</pre>
                </div>
                <p class="mt-2 text-xs text-zinc-500">
                    {{ __('Mi propia versión en ASCII, inspirada en la idea del proyecto: no reproduce su código ni su arte.') }}
                </p>
            </div>
        </div>
    @endif

    <div
        class="mx-auto max-w-8xl"
        data-reveal="fade-up"
        data-reveal-stagger="0.1"
        data-reveal-duration="0.85"
    >
        @if (! empty($project['description']))
            <div data-reveal-item class="space-y-4 text-base leading-relaxed text-zinc-600">
                @foreach ($project['description'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @endif

        <div data-reveal-item class="mt-10 flex flex-col gap-6 border-t border-zinc-200 pt-6 sm:flex-row sm:items-end sm:justify-between">
            <dl class="grid grid-cols-2 gap-8">
                <div>
                    <dt class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">{{ __('Autor') }}</dt>
                    <dd class="mt-1.5 text-sm font-semibold text-zinc-900">
                        @if (! empty($project['author_url']))
                            <a
                                href="{{ $project['author_url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="underline decoration-zinc-300 underline-offset-4 transition hover:decoration-zinc-900"
                            >
                                {{ $project['author'] ?? '—' }}
                            </a>
                        @else
                            {{ $project['author'] ?? '—' }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">{{ __('Lenguaje') }}</dt>
                    <dd class="mt-1.5 text-sm font-semibold text-zinc-900">{{ $project['language'] ?? '—' }}</dd>
                </div>
            </dl>

            @if (! empty($project['repo_url']))
                <a
                    href="{{ $project['repo_url'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex w-fit items-center gap-2 rounded-full bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700"
                >
                    {{ __('Ver proyecto original') }}
                    <x-icon name="arrow-up-right" class="size-4 shrink-0" />
                </a>
            @endif
        </div>
    </div>
</div>
