@props([
    'authorName' => '',
    'authorRole' => '',
    'initials' => '',
    'avatar' => null,
])

<div class="mb-14 flex items-center gap-4 sm:gap-5">
    @if (filled($avatar))
        <img
            src="{{ $avatar }}"
            alt=""
            width="56"
            height="56"
            class="size-14 shrink-0 rounded-full object-cover ring-1 ring-white/10 sm:size-16"
        />
    @else
        <div
            class="flex size-14 shrink-0 items-center justify-center rounded-full bg-zinc-800 text-sm font-semibold tracking-tight text-white ring-1 ring-white/10 sm:size-16 sm:text-base"
            aria-hidden="true"
        >
            {{ filled($initials) ? $initials : '·' }}
        </div>
    @endif
    <div class="min-w-0">
        <p class="text-base font-semibold text-zinc-900 sm:text-lg">{{ $authorName }}</p>
        <p class="mt-0.5 text-sm text-zinc-500">{{ $authorRole }}</p>
    </div>
</div>
