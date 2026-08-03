@php
    $contactEmail = config('mail.from.address', 'hola@ejemplo.com');
    $siteName = config('app.name', 'Sitio');
@endphp

<div class="contents">
    @if ($show)
        <div
            class="fixed inset-0 z-[200] overflow-y-auto overscroll-contain"
            role="dialog"
            aria-modal="true"
            aria-labelledby="contact-modal-title"
            wire:keydown.escape.window="close"
        >
            <div
                @class([
                    'fixed inset-0 cursor-pointer bg-zinc-900/50 backdrop-blur-md',
                    'motion-safe:animate-contact-backdrop-in' => ! $leaving,
                    'motion-safe:animate-contact-backdrop-out' => $leaving,
                ])
                wire:click="close"
                aria-hidden="true"
            ></div>

            <div class="relative flex min-h-full items-end justify-center p-0 sm:items-center sm:p-6">
                <div
                    @class([
                        'relative z-10 grid w-full max-w-5xl grid-cols-1 rounded-t-3xl bg-white shadow-[0_25px_80px_-20px_rgba(15,23,42,0.35)] sm:my-6 sm:rounded-3xl',
                        'lg:grid-cols-[minmax(0,1fr)_minmax(0,1.15fr)]',
                        'motion-safe:animate-contact-modal-in' => ! $leaving,
                        'motion-safe:animate-contact-modal-out' => $leaving,
                    ])
                    wire:click.stop
                >
                <button
                    type="button"
                    class="absolute right-3 top-3 z-20 inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-200 bg-white/90 text-zinc-600 transition hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                    wire:click="close"
                    aria-label="Cerrar"
                >
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                    </svg>
                </button>

                {{-- Columna info --}}
                <div class="flex flex-col gap-6 border-b border-zinc-200/80 p-6 pb-8 sm:gap-8 sm:p-8 sm:pb-10 lg:border-b-0 lg:border-r lg:pb-8">
                    <div class="flex items-center gap-2.5 pr-10">
                        <span
                            class="inline-flex size-9 items-center justify-center rounded-lg border border-zinc-900 bg-white"
                            aria-hidden="true"
                        >
                            <svg class="size-5 text-zinc-900" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                                <path
                                    d="M8 24l4-8M12 16l4-8m4 8l4-8M16 8l4-8"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </span>
                        <span class="text-lg font-extrabold tracking-tight text-zinc-900">{{ $siteName }}</span>
                    </div>

                    <div class="space-y-7 text-sm text-zinc-600">
                        <div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900" aria-hidden="true">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-bold text-zinc-900">Escribinos</p>
                                    <p class="mt-1 leading-relaxed">Respuesta humana, sin vueltas.</p>
                                    <a
                                        class="mt-2 inline-block font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 transition hover:decoration-zinc-900"
                                        href="mailto:{{ $contactEmail }}"
                                    >
                                        {{ $contactEmail }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900" aria-hidden="true">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="11" r="2.5" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-bold text-zinc-900">Ubicación</p>
                                    <p class="mt-1 leading-relaxed">Trabajo en remoto; podemos coordinar videollamada cuando haga falta.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 text-zinc-900" aria-hidden="true">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path
                                            d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-bold text-zinc-900">Horario</p>
                                    <p class="mt-1 leading-relaxed">Lun a vie, horario flexible (UTC−3).</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto flex flex-wrap gap-2 border-t border-zinc-100 pt-6" aria-label="Redes sociales">
                        @foreach (['Facebook', 'X', 'LinkedIn', 'YouTube', 'Web'] as $net)
                            <span
                                class="inline-flex size-9 items-center justify-center rounded-md border border-dashed border-zinc-200 bg-zinc-50 text-[0.65rem] font-bold tracking-tighter text-zinc-400"
                                title="Próximamente"
                            >
                                {{ mb_substr($net, 0, 1) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Formulario --}}
                <div class="flex flex-col bg-brand-lime p-6 pt-10 sm:p-8 sm:pt-12 lg:pt-10">
                    @if ($success)
                        <div class="m-auto flex max-w-md flex-col items-center gap-5 py-8 text-center">
                            <span
                                class="inline-flex size-14 items-center justify-center rounded-full border-2 border-zinc-900 bg-white text-zinc-900"
                                aria-hidden="true"
                            >
                                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xl font-bold tracking-tight text-zinc-900">¡Recibido!</p>
                                <p class="mt-2 text-sm leading-relaxed text-zinc-800">
                                    Gracias por escribir. Te respondo a la brevedad.
                                </p>
                            </div>
                            <button
                                type="button"
                                class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                                wire:click="close"
                            >
                                Cerrar
                            </button>
                        </div>
                    @else
                        <h2 id="contact-modal-title" class="text-2xl font-extrabold leading-tight tracking-tight text-zinc-900 sm:text-3xl">
                            ¿Ideas en mente? Sumemos.
                        </h2>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-800 sm:text-base">
                            Contame sobre vos y qué tenés en mente.
                        </p>

                        <form class="mt-8 flex flex-col gap-6" wire:submit.prevent="submit">
                            <div class="relative">
                                <label class="sr-only" for="contact-name">Tu nombre</label>
                                <input
                                    id="contact-name"
                                    type="text"
                                    autocomplete="name"
                                    @class([
                                        'w-full border-0 border-b-2 border-zinc-900 bg-transparent py-2 text-sm font-medium text-zinc-900 placeholder:text-zinc-600/80 focus:border-zinc-900 focus:outline-none focus:ring-0',
                                        'pr-11' => $errors->has('name'),
                                    ])
                                    placeholder="Tu nombre"
                                    wire:model.blur="name"
                                    @error('name') aria-invalid="true" @enderror
                                />
                                <x-contact.field-error-icon field="name" />
                            </div>

                            <div class="relative">
                                <label class="sr-only" for="contact-email">Correo</label>
                                <input
                                    id="contact-email"
                                    type="email"
                                    autocomplete="email"
                                    @class([
                                        'w-full border-0 border-b-2 border-zinc-900 bg-transparent py-2 text-sm font-medium text-zinc-900 placeholder:text-zinc-600/80 focus:border-zinc-900 focus:outline-none focus:ring-0',
                                        'pr-11' => $errors->has('email'),
                                    ])
                                    placeholder="vos@empresa.com"
                                    wire:model.blur="email"
                                    @error('email') aria-invalid="true" @enderror
                                />
                                <x-contact.field-error-icon field="email" />
                            </div>

                            <div class="relative">
                                <label class="sr-only" for="contact-message">Proyecto</label>
                                <textarea
                                    id="contact-message"
                                    rows="3"
                                    @class([
                                        'w-full resize-none border-0 border-b-2 border-zinc-900 bg-transparent py-2 text-sm font-medium text-zinc-900 placeholder:text-zinc-600/80 focus:border-zinc-900 focus:outline-none focus:ring-0',
                                        'pr-11' => $errors->has('message'),
                                    ])
                                    placeholder="Contame un poco sobre el proyecto…"
                                    wire:model.blur="message"
                                    @error('message') aria-invalid="true" @enderror
                                ></textarea>
                                <x-contact.field-error-icon field="message" placement="textarea" />
                            </div>

                            <button
                                type="submit"
                                class="mt-2 inline-flex w-full items-center justify-center rounded-xl bg-zinc-900 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 disabled:cursor-not-allowed disabled:opacity-60"
                                wire:loading.attr="disabled"
                                wire:target="submit"
                            >
                                <span wire:loading.remove wire:target="submit">¡Arranquemos!</span>
                                <span wire:loading wire:target="submit">Enviando…</span>
                            </button>
                        </form>
                    @endif
                </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    document.addEventListener('livewire:navigating', () => {
        $wire.$set('leaving', false, false);
        $wire.$set('show', false, false);
        $wire.$set('success', false, false);
        window.dispatchEvent(new CustomEvent('contact-modal-close'));
    });
</script>
@endscript
