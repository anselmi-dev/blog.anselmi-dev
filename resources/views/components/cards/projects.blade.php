@props([
    'href' => '#',
    'title' => '',
    'description' => '',
    'index' => '',
    'color' => '#E87FBF',
    'tags' => [],
])

@php($safeColor = \App\Support\CssColor::hexOrNull($color) ?? '#E87FBF')

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'group relative flex min-h-[380px] cursor-pointer flex-col justify-between rounded-[20px] bg-brand-lime px-4 py-4 transition-[transform,background-color] duration-200 hover:scale-[1.02] hover:bg-[var(--card-accent)]',
    ])->merge([
        'style' => '--card-accent: '.$safeColor,
    ]) }}
    >
    <span
        class="absolute right-4 top-4 size-4 rounded-full bg-[var(--card-accent)] ring-2 ring-zinc-900/15 transition-[transform,background-color,box-shadow] duration-300 ease-out group-hover:scale-110 group-hover:bg-zinc-900 group-hover:ring-zinc-900/30"
        aria-hidden="true"
    ></span>

    <svg
        class="project-card-mark size-12 origin-center transition-transform duration-500 ease-out will-change-transform group-hover:rotate-[12deg]"
        viewBox="0 0 48 48"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <rect class="project-card-mark-piece" style="--i: 0" x="8" y="8" width="14" height="14" fill="#111" />
        <rect class="project-card-mark-piece project-card-mark-accent" style="--i: 1" x="26" y="8" width="14" height="14" fill="var(--card-accent)" />
        <rect class="project-card-mark-piece" style="--i: 2" x="8" y="26" width="14" height="14" fill="rgba(17,17,17,0.4)" />
        <rect class="project-card-mark-piece" style="--i: 3" x="26" y="26" width="14" height="14" fill="#111" />
        <circle class="project-card-mark-piece project-card-mark-dot" style="--i: 4" cx="15" cy="33" r="5" fill="var(--card-accent)" />
        <circle class="project-card-mark-piece project-card-mark-dot" style="--i: 5" cx="33" cy="15" r="5" fill="var(--card-accent)" />
    </svg>

    <div class="mt-auto">
        <p class="mb-1.5 text-[13px] font-bold tracking-[0.02em] text-zinc-900">{{ $index }}.</p>
        <div class="flex items-end justify-between gap-2">
            <p class="text-[26px] font-extrabold leading-[1.15] text-zinc-900">{{ $title }}</p>
            <span
                class="mt-2 flex size-10 shrink-0 items-center justify-center rounded-full border border-zinc-900 bg-transparent text-zinc-900 transition-[transform,background-color,color] duration-300 ease-out group-hover:translate-x-0.5 group-hover:bg-zinc-900 group-hover:text-white"
                aria-hidden="true"
            >
                <svg
                    class="transition-transform duration-300 ease-out group-hover:translate-x-0.5"
                    width="16"
                    height="16"
                    viewBox="0 0 16 16"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                >
                    <line x1="3" y1="8" x2="13" y2="8"/><polyline points="9,4 13,8 9,12"/>
                </svg>
            </span>
        </div>
        <hr class="my-3 border-t border-zinc-900/25"/>
        <p class="text-[13px] leading-relaxed text-zinc-900 opacity-75">{{ $description }}</p>
        @if (count($tags) > 0)
            <ul class="mt-1 flex max-h-[3.75rem] flex-wrap items-start gap-1 overflow-hidden">
                @foreach ($tags as $tag)
                    <li class="rounded-full bg-gray-500/10 px-2 py-1 text-center text-[13px] font-bold tracking-[0.02em] text-zinc-900">{{ $tag }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</a>
