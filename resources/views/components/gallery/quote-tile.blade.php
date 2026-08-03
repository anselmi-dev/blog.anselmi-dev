@props([
    'quote',
    'attribution' => null,
    'wide' => false,
])

@php
    $shell = 'flex min-h-0 flex-col justify-center rounded-xl border border-zinc-200/90 bg-zinc-900 p-6 shadow-sm ring-1 ring-zinc-950/[0.06] dark:border-zinc-700 dark:bg-zinc-950 dark:ring-white/[0.06] sm:p-8';
    $frame = $wide
        ? 'aspect-[4/3] min-h-[13rem] sm:aspect-[2/1] sm:min-h-[15rem] lg:min-h-[17rem]'
        : 'aspect-[3/4] min-h-[15rem] sm:min-h-[17rem] lg:min-h-[18rem]';
@endphp

<article {{ $attributes->class([$shell, $frame, 'text-center']) }}>
    <blockquote class="mx-auto max-w-md">
        <p class="text-base font-medium leading-relaxed text-white sm:text-lg">
            “{{ $quote }}”
        </p>
        @if (filled($attribution))
            <footer class="mt-4 text-sm text-zinc-400">
                {{ $attribution }}
            </footer>
        @endif
    </blockquote>
</article>
