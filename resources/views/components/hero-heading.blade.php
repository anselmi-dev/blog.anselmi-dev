@props([
    'line1' => 'Diseño y desarrollo que',
    'line2' => 'impulsan tu presencia digital',
])

<h1
    {{ $attributes->class([
        'group max-w-4xl text-3xl font-extrabold leading-[1.06] tracking-tight text-zinc-900 sm:text-4xl md:text-5xl lg:text-6xl lg:leading-[1.04]',
    ]) }}
>
    <span class="block">{{ $line1 }}</span>
    <span class="mt-2 flex flex-wrap items-center gap-3 sm:mt-3 sm:gap-4">
        <span
            class="hero-plant-mark inline-flex size-12 shrink-0 items-center justify-center rounded-full border-2 border-zinc-900 bg-white transition-transform duration-300 ease-out sm:size-14 md:size-16 group-hover:scale-105 group-hover:-rotate-3"
            aria-hidden="true"
        >
            <svg
                class="hero-plant-icon size-7 origin-[50%_88%] text-zinc-900 will-change-transform sm:size-8"
                viewBox="0 0 32 32"
                fill="none"
                aria-hidden="true"
            >
                <path
                    class="hero-plant-stem"
                    d="M16 26V12M16 12c0-3 2.5-5.5 6-6M16 12c0-3-2.5-5.5-6-6"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    class="hero-plant-smile"
                    d="M10 20c2 2 4 3 6 3s4-1 6-3"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                />
            </svg>
        </span>
        <span class="min-w-0">{{ $line2 }}</span>
    </span>
</h1>
