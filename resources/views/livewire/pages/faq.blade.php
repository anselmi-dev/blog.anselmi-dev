<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    @section('header-content')
        <x-hero-content>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                Preguntas frecuentes
            </h1>
            <p class="mt-4 text-base leading-relaxed text-zinc-600 sm:text-lg">
                Respuestas directas sobre cómo trabajo, plazos y formas de colaboración. Si no está acá, escribime desde contacto.
            </p>
        </x-hero-content>
    @endsection

    <div
        class="mx-auto mt-12 flex max-w-8xl flex-wrap gap-5 box-border sm:mt-14 sm:gap-6 lg:gap-x-8 lg:gap-y-6"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.07"
    >
        @foreach ($items as $index => $item)
            <article
                data-reveal-item
                class="box-border min-w-0 w-full rounded-xl border border-zinc-200/90 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-3"
                x-data="{ open: true }"
            >
                <h2 class="text-base font-semibold leading-snug text-zinc-900 sm:text-lg">
                    <button
                        type="button"
                        id="faq-trigger-{{ $index }}"
                        class="flex w-full cursor-pointer items-center justify-between gap-4 text-left outline-none transition hover:text-zinc-700 focus-visible:ring-2 focus-visible:ring-zinc-900/25 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                        x-on:click="open = ! open"
                        :aria-expanded="open"
                        aria-controls="faq-panel-{{ $index }}"
                    >
                        <span class="min-w-0 flex-1">{{ $item['question'] }}</span>
                        <span
                            class="relative inline-flex size-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 sm:size-10"
                            x-bind:class="{'bg-brand-lime': open}"
                            aria-hidden="true"
                        >
                            <x-icon name="chevron-down" class="size-5 transition-transform duration-300 ease-out" x-bind:class="{'rotate-180': open}" />
                        </span>
                    </button>
                </h2>

                <div
                    id="faq-panel-{{ $index }}"
                    role="region"
                    aria-labelledby="faq-trigger-{{ $index }}"
                    class="grid transition-[grid-template-rows] duration-300 ease-out motion-reduce:transition-none"
                    :class="open ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
                >
                    <div class="overflow-hidden" :aria-hidden="open ? 'false' : 'true'">
                        <p
                            class="pt-4 text-sm font-normal leading-relaxed text-zinc-600 sm:text-[0.9375rem] sm:leading-relaxed"
                        >
                            {{ $item['answer'] }}
                        </p>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <p
        class="mx-auto mt-12 max-w-xl text-center text-sm text-zinc-500"
        data-reveal="fade-up"
        data-reveal-scroll
    >
        ¿Seguís con dudas?
        <button
            type="button"
            class="font-semibold text-zinc-800 underline decoration-zinc-300 underline-offset-2 transition hover:decoration-zinc-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
            x-data
            x-on:click="Livewire.dispatch('open-contact-modal')"
        >
            Escribime
        </button>
    </p>
</div>
