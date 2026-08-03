@props([
    'kicker' => '',
    'title' => '',
    'actionTitle' => null,
    'actionHref' => '#',
    'animate' => true,
])

<div
    @if ($animate)
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.06"
        data-reveal-duration="0.75"
    @endif
    {{ $attributes->class(['mb-10 flex items-end justify-between gap-6']) }}
>
    <div @if ($animate) data-reveal-item @endif>
        <p
            class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-folio-forest dark:text-folio-muted"
        >
            {{ $kicker }}
        </p>
        <h2 class="font-display text-3xl font-bold tracking-tight text-folio-fg dark:text-zinc-50 lg:text-4xl">
            {{ $title }}
        </h2>
    </div>
    @if (filled($actionTitle))
        <a
            href="{{ $actionHref }}"
            wire:navigate
            @if ($animate) data-reveal-item @endif
            class="group hidden shrink-0 items-center gap-2.5 text-sm font-semibold text-zinc-900 transition-colors md:inline-flex focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
        >
            <span>{{ $actionTitle }}</span>
            <span
                class="inline-flex size-8 shrink-0 items-center justify-center rounded-full border border-zinc-900 text-zinc-900 transition-[transform,background-color,color] duration-300 ease-out group-hover:translate-x-0.5 group-hover:bg-zinc-900 group-hover:text-white"
                aria-hidden="true"
            >
                <x-icon
                    name="arrow-right"
                    class="size-3.5 transition-transform duration-300 ease-out group-hover:translate-x-0.5"
                />
            </span>
        </a>
    @endif
</div>
