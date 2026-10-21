@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            {{ __('¿Qué escucho?') }}
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            {{ __('Mi playlist') }}
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            {{ __('Las canciones que me acompañan mientras diseño, programo o simplemente desconecto.') }}
        </p>
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    @if (filled($playlistEmbedUrl))
        <section
            class="mx-auto mb-10 max-w-8xl"
            data-reveal="fade-up"
            data-reveal-scroll
        >
            <div class="mb-3 flex items-center justify-between gap-3">
                <p class="text-lg font-normal uppercase leading-none tracking-tight text-zinc-500 lg:text-xl">
                    {{ __('Playlist completa') }}
                </p>
                <a
                    href="{{ $playlistUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-8 items-center gap-1 rounded-full border border-zinc-200 px-3 text-sm text-zinc-500 transition hover:border-brand-lime hover:bg-brand-lime hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                >
                    {{ __('Abrir en Spotify') }}
                    <x-icon name="external-link" class="size-4" />
                </a>
            </div>
            <div class="overflow-hidden rounded-xl bg-zinc-900/80 ring-1 ring-white/10">
                <iframe
                    class="block w-full"
                    style="border-radius: 12px"
                    src="{{ $playlistEmbedUrl }}"
                    width="100%"
                    height="452"
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                    sandbox="allow-scripts allow-same-origin allow-presentation allow-popups"
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                    title="Spotify — {{ __('mi playlist') }}"
                ></iframe>
            </div>
        </section>
    @endif

    @if (count($songs) === 0 && blank($playlistEmbedUrl))
        <p class="mx-auto max-w-8xl text-sm text-zinc-500">
            {{ __('Todavía no hay canciones en la playlist.') }}
        </p>
    @elseif (count($songs) > 0)
        <ol
            class="mx-auto flex max-w-8xl flex-col gap-3"
            data-reveal="fade-up"
            data-reveal-scroll
            data-reveal-stagger="0.05"
        >
            @foreach ($songs as $index => $song)
                <li
                    data-reveal-item
                    class="rounded-2xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4"
                    x-data="{ playing: false }"
                >
                    <div class="flex items-center gap-3 sm:gap-4">
                        <span class="hidden w-6 shrink-0 text-right font-folio-mono text-xs text-zinc-400 sm:block">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </span>

                        @if (filled($song['image']))
                            <img
                                src="{{ $song['image'] }}"
                                alt="{{ $song['title'] }} — {{ $song['artist'] }}"
                                class="size-14 shrink-0 rounded-lg object-cover ring-1 ring-black/5 sm:size-16"
                                width="64"
                                height="64"
                                loading="{{ $index < 4 ? 'eager' : 'lazy' }}"
                                decoding="async"
                            />
                        @else
                            <span
                                class="inline-flex size-14 shrink-0 items-center justify-center rounded-lg bg-zinc-900/80 text-white sm:size-16"
                                aria-hidden="true"
                            >
                                <x-icon name="music" class="size-5" />
                            </span>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-base font-semibold text-zinc-900 sm:text-lg">{{ $song['title'] }}</p>
                            <p class="truncate text-sm text-zinc-500">
                                {{ $song['artist'] }}@if (filled($song['album'])) · {{ $song['album'] }}@endif
                            </p>
                            @if (filled($song['note']))
                                <p class="mt-1 hidden text-sm leading-relaxed text-zinc-600 sm:block">{{ $song['note'] }}</p>
                            @endif
                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            @if (filled($song['embed_url']))
                                <button
                                    type="button"
                                    x-on:click="playing = ! playing"
                                    :aria-expanded="playing"
                                    class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-brand-lime hover:bg-brand-lime hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                                    :class="{ 'border-brand-lime bg-brand-lime text-zinc-900': playing }"
                                    aria-label="{{ __('Reproducir :title', ['title' => $song['title']]) }}"
                                >
                                    <x-icon name="play" class="size-4" />
                                </button>
                            @endif
                            @if (filled($song['spotify_url']))
                                <a
                                    href="{{ $song['spotify_url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex size-9 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-brand-lime hover:bg-brand-lime hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                                    aria-label="{{ __('Abrir :title en Spotify', ['title' => $song['title']]) }}"
                                >
                                    <x-icon name="external-link" class="size-4" />
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (filled($song['note']))
                        <p class="mt-3 text-sm leading-relaxed text-zinc-600 sm:hidden">{{ $song['note'] }}</p>
                    @endif

                    @if (filled($song['embed_url']))
                        <template x-if="playing">
                            <div class="mt-3 overflow-hidden rounded-xl">
                                <iframe
                                    class="block w-full"
                                    style="border-radius: 12px"
                                    src="{{ $song['embed_url'] }}"
                                    width="100%"
                                    height="152"
                                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                    sandbox="allow-scripts allow-same-origin allow-presentation allow-popups"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    title="Spotify — {{ $song['title'] }}"
                                ></iframe>
                            </div>
                        </template>
                    @endif
                </li>
            @endforeach
        </ol>
    @endif
</div>
