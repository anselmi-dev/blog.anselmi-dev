@props([
    'authorName' => config('app.name', 'Carlos Anselmi'),
])

<div
    class="flex w-full flex-col items-stretch gap-8 sm:flex-row sm:items-center sm:justify-between sm:gap-10"
>
    <p
        class="max-w-2xl text-center text-sm leading-relaxed text-zinc-900 sm:text-left md:text-[0.9375rem]"
    >
        © {{ now()->year }}
        {{ $authorName }}. Todos los derechos reservados, aunque si copiás algo al menos mandame un saludo.
    </p>

    <button
        type="button"
        x-data
        x-on:click="Livewire.dispatch('open-contact-modal')"
        x-on:keydown.meta.k.window.prevent="Livewire.dispatch('open-contact-modal')"
        x-on:keydown.ctrl.k.window.prevent="Livewire.dispatch('open-contact-modal')"
        class="inline-flex w-full max-w-md shrink-0 cursor-pointer items-center justify-between gap-4 self-center rounded-full border-0 bg-footer-pine px-5 py-2.5 pl-6 text-footer-mist shadow-sm transition hover:bg-footer-pine/92 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-footer-pine sm:w-auto sm:min-w-[min(100%,19rem)] sm:self-auto sm:py-3"
        aria-label="Escribime un mensaje"
    >
        <span class="text-left font-mono text-sm font-medium tracking-tight">Escribime un mensaje</span>
        <span class="flex shrink-0 items-center gap-1.5" aria-hidden="true">
            <kbd
                class="flex size-7 items-center justify-center rounded-md bg-footer-pine-key font-mono text-xs font-semibold leading-none text-footer-mist"
            >
                ⌘
            </kbd>
            <kbd
                class="flex size-7 items-center justify-center rounded-md bg-footer-pine-key font-mono text-xs font-semibold leading-none text-footer-mist"
            >
                K
            </kbd>
        </span>
    </button>
</div>
