@props([
    'label',
    'items' => [],
    'align' => 'center',
])

@php
    $panelAlign = match ($align) {
        'left' => 'left-0',
        'right' => 'right-0',
        default => 'left-1/2 -translate-x-1/2',
    };
@endphp

<div
    {{ $attributes->class('relative') }}
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
>
    <button
        type="button"
        class="flex cursor-pointer items-center gap-2 rounded-md py-1 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
        @click="open = ! open"
        :aria-expanded="open"
        aria-haspopup="true"
    >
        <span>{{ $label }}</span>
        <span
            class="inline-flex size-7 items-center justify-center rounded-full border border-zinc-900"
            aria-hidden="true"
        >
            <x-icon
                name="chevron-down"
                class="size-3.5 shrink-0 transition-transform duration-300 ease-out"
                x-bind:class="{ 'rotate-180': open }"
            />
        </span>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
        class="absolute top-full z-30 mt-2 min-w-[12rem] origin-top rounded-2xl border border-zinc-200/80 bg-white py-2 shadow-lg shadow-zinc-900/10 {{ $panelAlign }}"
    >
        @forelse ($items as $item)
            <x-header.nav-link
                variant="dropdown"
                :href="$item['href']"
                :navigate="! empty($item['navigate'])"
                @click="open = false"
            >
                {{ $item['label'] }}
            </x-header.nav-link>
        @empty
            {{ $slot }}
        @endforelse
    </div>
</div>
