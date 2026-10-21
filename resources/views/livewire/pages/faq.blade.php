<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24 overflow-hidden">
    @section('header-content')
        <x-hero-content>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                {{ __('Preguntas frecuentes') }}
            </h1>
            <p class="mt-4 text-base leading-relaxed text-zinc-600 sm:text-lg">
                {{ __('Respuestas directas sobre cómo trabajo, plazos y formas de colaboración. Si no está acá, escribime desde contacto.') }}
            </p>
        </x-hero-content>
    @endsection

    <div class="relative mx-auto mt-12 max-w-8xl rounded-xl sm:mt-14 sm:rounded-2xl">
        <div
            data-scroll-drift
            data-scroll-drift-distance="120"
            class="pointer-events-none absolute inset-0 select-none font-black leading-none text-brand-lime-200 opacity-30"
            aria-hidden="true"
        >
            <span class="absolute lg:-left-14 top-4 text-[14rem] sm:-left-20 sm:text-[22rem] lg:-left-24 lg:text-[30rem]">Q</span>
            <span class="absolute lg:-right-12 top-1/3 text-[13rem] sm:-right-16 sm:text-[20rem] lg:text-[28rem]">&amp;</span>
            <span class="absolute lg:-left-10 bottom-0 text-[14rem] sm:-left-14 sm:text-[22rem] lg:text-[30rem]">A</span>
        </div>

        <dl
            class="relative mx-auto flex max-w-xl flex-col gap-10 px-5 py-14 text-center sm:gap-14 sm:py-20"
            data-reveal="fade-up"
            data-reveal-scroll
            data-reveal-stagger="0.07"
        >
            @foreach ($items as $item)
                <div data-reveal-item>
                    <dt class="font-serif text-sm italic leading-snug text-zinc-500 sm:text-base">
                        {{ $item['question'] }}
                    </dt>
                    <dd class="mt-2 text-base font-bold leading-snug text-zinc-900 sm:text-lg">
                        {{ $item['answer'] }}
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>

    <p
        class="mx-auto mt-12 max-w-xl text-center text-sm text-zinc-500"
        data-reveal="fade-up"
        data-reveal-scroll
    >
        {{ __('¿Seguís con dudas?') }}
        <button
            type="button"
            class="font-semibold text-zinc-800 underline decoration-zinc-300 underline-offset-2 transition hover:decoration-zinc-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
            x-data
            x-on:click="Livewire.dispatch('open-contact-modal')"
        >
            {{ __('Escribime') }}
        </button>
    </p>
</div>
