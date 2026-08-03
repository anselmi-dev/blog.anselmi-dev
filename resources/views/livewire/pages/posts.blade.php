@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-folio-forest dark:text-folio-muted">
            Blog
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 sm:text-4xl">
            Notas
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400 sm:text-lg">
            Textos cortos sobre cómo trabajo, qué uso en el stack y por qué a veces conviene decir que no.
        </p>
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <section
        class="py-10 dark:bg-zinc-950 sm:py-12 lg:py-16"
        aria-label="Notas en rejilla"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.07"
    >
        <div class="mx-auto max-w-8xl">
            <div
                class="grid grid-cols-1 grid-flow-dense gap-4 sm:grid-cols-2 sm:gap-5 xl:grid-cols-12 xl:gap-6"
            >
                @foreach ($cells as $cell)
                    @if ($cell['type'] === 'intro')
                        <div
                            data-reveal-item
                            @class([
                                'flex flex-col rounded-2xl border border-zinc-200/90 bg-white p-6 shadow-sm sm:p-8 lg:p-10 dark:border-zinc-800 dark:bg-zinc-900',
                                $cell['gridClass'] ?? '',
                            ])
                        >
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <h1 class="max-w-xl text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-[2.35rem] lg:leading-[1.1] dark:text-zinc-50">
                                    {{ $cell['title'] }}
                                </h1>
                                <span
                                    class="inline-flex size-12 shrink-0 items-center justify-center rounded-full border border-zinc-200 bg-zinc-50 text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                                    aria-hidden="true"
                                >
                                    <x-icon name="arrow-right" class="size-6" />
                                </span>
                            </div>
                            <p class="mt-6 max-w-2xl text-sm leading-relaxed text-zinc-600 sm:text-base dark:text-zinc-400">
                                {{ $cell['body'] }}
                            </p>
                        </div>
                    @elseif ($cell['type'] === 'card')
                        @php
                            $e = $cell['entry'] ?? [];
                        @endphp
                        <x-posts.bento-card
                            data-reveal-item
                            :href="route('blog.show', $cell['slug'] ?? '')"
                            :kicker="$e['kicker'] ?? ''"
                            :title="$e['title'] ?? ''"
                            :excerpt="$e['excerpt'] ?? ''"
                            wire:navigate
                            @class([$cell['gridClass'] ?? ''])
                        />
                    @elseif ($cell['type'] === 'image')
                        @php
                            $e = $cell['entry'] ?? [];
                            $src = $e['image_url'] ?? sprintf(
                                'https://picsum.photos/seed/%s/%d/%d',
                                $e['slug'] ?? 'blog',
                                900,
                                1100
                            );
                        @endphp
                        <a
                            href="{{ route('blog.show', $cell['slug'] ?? '') }}"
                            wire:navigate
                            data-reveal-item
                            @class([
                                'group block min-h-0 overflow-hidden rounded-2xl border border-zinc-200/90 bg-zinc-200/60 shadow-sm transition hover:border-zinc-300 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:border-zinc-800 dark:bg-zinc-800/40 dark:hover:border-zinc-600 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950',
                                $cell['gridClass'] ?? '',
                            ])
                        >
                            <figure class="m-0 h-full">
                                <img
                                    src="{{ $src }}"
                                    alt="{{ $e['alt'] ?? $e['title'] ?? '' }}"
                                    class="h-full min-h-[13rem] w-full object-cover transition duration-300 group-hover:scale-[1.02] sm:min-h-[15rem] xl:min-h-full"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </figure>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
</div>
