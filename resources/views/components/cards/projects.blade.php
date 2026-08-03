@props([
    'href' => '#',
    'title' => '',
    'description' => '',
    'index' => '',
    'color' => '#E87FBF',
    'tags' => [],
])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'group flex min-h-[380px] cursor-pointer flex-col justify-between rounded-[20px] bg-brand-lime px-4 py-4 transition-[transform,background-color] duration-200 hover:scale-[1.02] hover:bg-[var(--card-accent)]',
    ])->merge([
        'style' => '--card-accent: '.$color,
    ]) }}
    >
    <svg class="size-12" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 8h14v14H8zM26 8h14v14H26z" fill="#111" />
        <path d="M8 26h14v14H8z" fill="#111" opacity="0.4" />
        <path d="M26 26h14v14H26z" fill="#111" />
        <circle cx="15" cy="33" r="5" fill="var(--card-accent)" />
        <circle cx="33" cy="15" r="5" fill="var(--card-accent)" />
    </svg>

    <div class="mt-auto">
        <p class="mb-1.5 text-[13px] font-bold tracking-[0.02em] text-zinc-900">{{ $index }}.</p>
        <div class="flex items-end justify-between gap-2">
            <p class="text-[26px] font-extrabold leading-[1.15] text-zinc-900">{{ $title }}</p>
            <span class="mt-2 flex size-10 shrink-0 items-center justify-center rounded-full border border-zinc-900 bg-transparent" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#111" stroke-width="1.8" stroke-linecap="round">
                    <line x1="3" y1="8" x2="13" y2="8"/><polyline points="9,4 13,8 9,12"/>
                </svg>
            </span>
        </div>
        <hr class="my-3 border-t border-zinc-900/25"/>
        <p class="text-[13px] leading-relaxed text-zinc-900 opacity-75">{{ $description }}</p>
        @if (count($tags) > 0)
            <ul class="mt-1 flex flex-wrap items-center gap-1">
                @foreach ($tags as $tag)
                    <li class="rounded-full bg-gray-500/10 px-2 py-1 text-center text-[13px] font-bold tracking-[0.02em] text-zinc-900">{{ $tag }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</a>
