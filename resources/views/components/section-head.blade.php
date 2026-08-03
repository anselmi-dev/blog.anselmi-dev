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
            class="hidden text-sm text-folio-muted-dark transition-colors hover:text-folio-fg dark:text-folio-muted md:inline dark:hover:text-zinc-100"
        >
            {{ $actionTitle }}
        </a>
    @endif
</div>
