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
        class="mx-auto max-w-3xl"
        data-reveal="fade-up"
        data-reveal-stagger="0.1"
        data-reveal-duration="0.85"
    >
        <a
            href="{{ localized_route('curious-code') }}"
            wire:navigate
            data-reveal-item
            class="mb-8 inline-flex items-center gap-2 text-sm font-medium text-zinc-600 transition hover:text-zinc-900"
        >
            <x-icon name="arrow-left" class="size-4 shrink-0" />
            {{ __('Volver a código curioso') }}
        </a>

        <p
            data-reveal-item
            class="mb-8 rounded-2xl border border-zinc-200 bg-zinc-50 px-5 py-4 text-sm leading-relaxed text-zinc-600"
        >
            {{ __('Este no es un proyecto mío: lo comparto porque me pareció curioso y quiero darlo a conocer. Todo el crédito es de su autor.') }}
        </p>

        {{-- Meta --}}
        <dl
            data-reveal-item
            class="mb-10 grid grid-cols-2 gap-6 border-y border-zinc-200 py-6 sm:gap-8"
        >
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

        @if (! empty($project['description']))
            <div data-reveal-item class="space-y-4 text-base leading-relaxed text-zinc-600">
                @foreach ($project['description'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @endif

        @if (! empty($project['repo_url']))
            <a
                href="{{ $project['repo_url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                data-reveal-item
                class="mt-10 inline-flex items-center gap-2 rounded-full bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700"
            >
                {{ __('Ver proyecto original') }}
                <x-icon name="arrow-up-right" class="size-4 shrink-0" />
            </a>
        @endif
    </div>
</div>
