@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-folio-forest dark:text-folio-muted">
            Blog
        </p>
        <p class="text-xs font-medium tracking-wide text-zinc-500 dark:text-zinc-400">
            {{ $entry['kicker'] ?? '' }}
        </p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 sm:text-4xl">
            {{ $entry['title'] ?? '' }}
        </h1>
        @if (! empty($entry['excerpt']))
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400 sm:text-lg">
                {{ $entry['excerpt'] }}
            </p>
        @endif
    </x-hero-content>
@endsection

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <div
        class="mx-auto max-w-8xl px-0 sm:px-0 space-y-5"
        data-reveal="fade-up"
        data-reveal-stagger="0.1"
        data-reveal-duration="0.85"
    >
        <a
            href="{{ route('blog') }}"
            wire:navigate
            data-reveal-item
            class="inline-flex items-center gap-2 text-base font-medium text-zinc-600 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
        >
            <x-icon name="arrow-left" class="size-4 shrink-0" />
            Volver al blog
        </a>

        @if (($entry['kind'] ?? '') === 'image')
            @php
                $src = $entry['image_url'] ?? sprintf(
                    'https://picsum.photos/seed/%s/%d/%d',
                    $entry['slug'] ?? 'blog',
                    1600,
                    1000
                );
            @endphp
            <figure
                data-reveal-item
                class="overflow-hidden rounded-2xl border border-zinc-200/90 bg-zinc-100 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
            >
                <img
                    src="{{ $src }}"
                    alt="{{ $entry['alt'] ?? $entry['title'] ?? '' }}"
                    class="aspect-[16/10] w-full object-cover"
                    loading="eager"
                    decoding="async"
                />
                @if (! empty($entry['caption']))
                    <figcaption class="border-t border-zinc-200/80 px-5 py-4 text-sm leading-relaxed text-zinc-600 dark:border-zinc-800 dark:text-zinc-400">
                        {{ $entry['caption'] }}
                    </figcaption>
                @endif
            </figure>
        @endif

        @if (! empty($entry['body']))
            <div
                data-reveal-item
                class="space-y-5 text-base leading-relaxed text-zinc-600 dark:text-zinc-400"
            >
                @foreach ($entry['body'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>
