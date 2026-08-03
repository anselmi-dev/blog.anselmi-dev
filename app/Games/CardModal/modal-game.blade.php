<div>
    <div
        wire:click="open"
        wire:key="{{ $component }}"
        {{ $attributes->class([
            'flex min-h-[380px] cursor-pointer flex-col justify-between rounded-[20px] px-6 py-7 transition-transform duration-200 hover:scale-[1.02]',
        ]) }}
    >
        <svg class="size-12" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 8h14v14H8zM26 8h14v14H26z" fill="#111" />
            <path d="M8 26h14v14H8z" fill="#111" opacity="0.4" />
            <path d="M26 26h14v14H26z" fill="#111" />
            <circle cx="15" cy="33" r="5" fill="#E87FBF" />
            <circle cx="33" cy="15" r="5" fill="#E87FBF" />
        </svg>

        <div class="mt-auto">
            <p class="mb-1.5 text-[13px] font-bold tracking-[0.02em] text-zinc-900">{{ $index }}.</p>
            <div class="flex items-end justify-between gap-2">
                <p class="text-[26px] font-extrabold leading-[1.15] text-zinc-900">{{ $title }}</p>
                <button class="mt-2 flex size-10 shrink-0 cursor-pointer items-center justify-center rounded-full border border-zinc-900 bg-transparent">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#111" stroke-width="1.8" stroke-linecap="round">
                        <line x1="3" y1="8" x2="13" y2="8"/><polyline points="9,4 13,8 9,12"/>
                    </svg>
                </button>
            </div>
            <hr class="my-3 border-t border-zinc-900/25"/>
            <p class="text-[13px] leading-relaxed text-zinc-900 opacity-75">{{ $description }}</p>
        </div>
    </div>

    @if ($show)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
            wire:click.self="close"
            x-data
            x-on:keydown.escape.window="$wire.close()"
            role="dialog"
            aria-modal="true"
        >
            <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl ring-1 ring-zinc-900/5">
                <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                    <h2 class="text-sm font-semibold text-zinc-900">{{ $title }}</h2>
                    <button
                        type="button"
                        wire:click="close"
                        class="rounded-lg p-1.5 text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600"
                        aria-label="Cerrar"
                    >
                        <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                            <path d="M3 3l10 10M13 3 3 13"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    @livewire($component, key($component))
                </div>
            </div>
        </div>
    @endif
</div>
