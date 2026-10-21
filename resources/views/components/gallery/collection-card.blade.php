@props([
    /** Entrada de Collection::toEntryArray(). */
    'entry',
])

@php
    $preview = $entry['preview'];
    // El primero va adelante; el segundo atrás a la izquierda y el tercero a la derecha.
    $positions = [
        'z-[3] group-hover:-translate-y-1.5',
        'z-[1] -translate-x-[58%] -rotate-[8deg] scale-[0.86] group-hover:-translate-x-[72%] group-hover:-rotate-[12deg]',
        'z-[2] translate-x-[58%] rotate-[8deg] scale-[0.86] group-hover:translate-x-[72%] group-hover:rotate-[12deg]',
    ];

    $summary = collect([
        // trans_choice() caería al inglés en /es (no hay es.json), por eso singular y plural por separado.
        'photo' => fn (int $count): string => $count === 1 ? __(':count foto', ['count' => $count]) : __(':count fotos', ['count' => $count]),
        'video' => fn (int $count): string => $count === 1 ? __(':count video', ['count' => $count]) : __(':count videos', ['count' => $count]),
        'quote' => fn (int $count): string => $count === 1 ? __(':count cita', ['count' => $count]) : __(':count citas', ['count' => $count]),
    ])
        ->filter(fn (callable $label, string $kind): bool => ($entry['counts'][$kind] ?? 0) > 0)
        ->map(fn (callable $label, string $kind): string => $label($entry['counts'][$kind]))
        ->implode(' · ');
@endphp

<a
    href="{{ localized_route('gallery.show', ['slug' => $entry['slug']]) }}"
    wire:navigate
    {{ $attributes->class(['group block rounded-2xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900']) }}
>
    <div class="relative aspect-[5/4] w-full" data-collection-fan>
        @forelse ($preview as $index => $item)
            <div
                @class([
                    'absolute left-1/2 top-1/2 aspect-[3/4] w-[44%] -ml-[22%] -mt-[29.3%] overflow-hidden rounded-xl bg-white shadow-[0_10px_30px_-12px_rgb(24_24_27/0.35)] ring-1 ring-zinc-900/10 transition duration-500 ease-[cubic-bezier(.2,.7,.2,1)]',
                    $positions[$index],
                ])
            >
                @if (($item['kind'] ?? null) === 'quote')
                    <div class="flex size-full flex-col p-3 sm:p-3.5">
                        <p class="flex items-center gap-1.5 font-folio-mono text-[0.6rem] uppercase tracking-widest text-zinc-400">
                            <x-icon name="quote" class="size-3" />
                            {{ __('Cita') }}
                        </p>
                        <p class="mt-2 line-clamp-6 font-[Fraunces,ui-serif,Georgia,serif] text-[0.8rem] leading-snug text-zinc-800">
                            {{ $item['quote'] ?? '' }}
                        </p>
                        @if (filled($item['attribution'] ?? null))
                            <p class="mt-auto truncate pt-2 font-folio-mono text-[0.6rem] uppercase tracking-wider text-zinc-400">
                                {{ $item['attribution'] }}
                            </p>
                        @endif
                    </div>
                @elseif (($item['kind'] ?? null) === 'video' && blank($item['poster_url'] ?? null))
                    <video
                        src="{{ $item['video_url'] }}#t=0.1"
                        class="size-full object-cover"
                        preload="metadata"
                        muted
                        playsinline
                        aria-hidden="true"
                    ></video>
                @else
                    <img
                        src="{{ $item['poster_url'] ?? $item['thumb_url'] ?? $item['image_url'] ?? '' }}"
                        alt="{{ $index === 0 ? ($item['title'] ?? '') : '' }}"
                        class="size-full object-cover"
                        loading="lazy"
                        decoding="async"
                    />
                @endif
                @if ($index === 0 && in_array($item['kind'] ?? null, ['photo', 'video'], true))
                    <span class="absolute bottom-2 right-2 inline-flex size-6 items-center justify-center rounded-md bg-white/90 text-zinc-700 shadow-sm" aria-hidden="true">
                        <x-icon :name="$item['kind'] === 'video' ? 'video' : 'camera'" class="size-3.5" />
                    </span>
                @endif
            </div>
        @empty
            <div class="absolute left-1/2 top-1/2 flex aspect-[3/4] w-[44%] -ml-[22%] -mt-[29.3%] items-center justify-center rounded-xl border border-dashed border-zinc-300 text-zinc-400">
                <x-icon name="images" class="size-6" />
            </div>
        @endforelse
    </div>

    <div class="mt-4 flex items-start justify-between gap-3">
        <h2 class="text-base font-semibold text-zinc-900 transition group-hover:text-zinc-600">{{ $entry['name'] }}</h2>
        <span class="shrink-0 rounded-full bg-brand-lime px-2 py-0.5 font-folio-mono text-xs tabular-nums text-zinc-700">{{ $entry['total'] }}</span>
    </div>
    @if (filled($summary))
        <p class="mt-1 text-sm text-zinc-500">{{ $summary }}</p>
    @endif
    @if ($entry['updated_at'])
        <p class="mt-0.5 text-sm text-zinc-400">
            {{ __('Actualizada :time', ['time' => $entry['updated_at']->diffForHumans()]) }}
        </p>
    @endif
</a>
