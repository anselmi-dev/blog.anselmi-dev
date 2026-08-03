@props([
    'href' => '#',
    'kicker' => '',
    'title' => '',
    'excerpt' => '',
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'group relative flex min-h-[11rem] flex-col rounded-2xl border border-zinc-200/90 bg-white p-6 shadow-sm transition hover:border-zinc-300 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-600 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950',
    ]) }}
>
    <span
        class="absolute right-5 top-5 inline-flex size-9 items-center justify-center rounded-full border border-zinc-200 bg-white text-zinc-700 transition group-hover:border-zinc-300 group-hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200"
        aria-hidden="true"
    >
        <x-icon name="arrow-up-right" class="size-4" />
    </span>
    <p class="pr-12 text-xs font-medium tracking-wide text-zinc-400 dark:text-zinc-500">
        {{ $kicker }}
    </p>
    <h2 class="mt-3 text-lg font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
        {{ $title }}
    </h2>
    <p class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
        {{ $excerpt }}
    </p>
</a>
