<div>
    @section('header-content')
        <x-hero-content>
            <x-hero-heading />
        </x-hero-content>
    @endsection

    <section
        id="vitrina"
        class="mx-auto max-w-8xl py-16"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.1"
        data-reveal-duration="0.9"
    >
        <!-- ==================== HERO ==================== -->
        <section
            data-reveal-item
            class="mb-3 flex flex-wrap items-start gap-8 rounded-3xl bg-brand-lime p-10 max-[600px]:flex-col max-[600px]:gap-6 max-[600px]:p-6"
        >
            <!-- LEFT: text -->
            <div class="flex flex-[3_1_300px] flex-col gap-4 max-[600px]:w-full max-[600px]:flex-none">
                <span class="inline-block w-fit rounded-full border border-[#111] px-4 py-[5px] text-[0.78rem] font-medium text-[#111]">Montevideo · Uruguay</span>

                <h1 class="font-cond m-0 text-[clamp(1.9rem,3.8vw,2.75rem)] font-black leading-[1.1] text-[#111]">
                    Hola.
                </h1>

                <p class="m-0 max-w-[400px] text-base leading-[1.65] text-[#333]">
                    Soy un desarrollador web especializado en Laravel, Livewire, Vue y Tailwind CSS.
                    Mi pasión radica en crear experiencias de usuario fluidas y funcionales
                    a través de soluciones web innovadoras.
                </p>

                <a
                    href="{{ route('about') }}"
                    wire:navigate
                    class="group inline-flex w-fit items-center gap-[10px] rounded-full bg-brand-lime-500 px-5 py-[11px] text-[0.85rem] font-semibold text-brand-lime-50 no-underline transition-[background-color,transform] duration-300 hover:scale-[1.03] hover:bg-[#2a2a2a] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                >
                    Conocé más
                    <span
                        class="flex h-[26px] w-[26px] shrink-0 items-center justify-center rounded-full bg-brand-lime text-zinc-900 transition-transform duration-300 ease-out group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:scale-105"
                        aria-hidden="true"
                    >
                        <svg
                            class="transition-transform duration-300 ease-out group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                            width="11"
                            height="11"
                            viewBox="0 0 12 12"
                            fill="none"
                        >
                            <path d="M2 10L10 2M10 2H4M10 2V8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            </div>

            <!-- RIGHT: two photos -->
            <div class="flex flex-[2_1_240px] items-start gap-[10px] max-[600px]:w-full max-[600px]:flex-none max-[420px]:gap-2">
                <div class="h-[270px] flex-1 overflow-hidden rounded-2xl max-[600px]:h-[190px] max-[420px]:h-[160px]">
                    <img class="block size-full object-cover" src="{{ asset('images/DSC_0307.jpg') }}" alt="Cyclist racing" />
                </div>
                {{-- <div class="mt-11 h-[220px] flex-1 overflow-hidden rounded-2xl max-[600px]:mt-7 max-[600px]:h-[160px] max-[420px]:mt-6 max-[420px]:h-[130px]">
                    <img class="block size-full object-cover" src="https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=400&h=500&fit=crop&crop=top" alt="Cyclist with bike" />
                </div> --}}
            </div>
        </section>

        <!-- ==================== BOTTOM ROW ==================== -->
        <div class="flex flex-wrap items-stretch gap-3" data-reveal-item>
            <!-- Photo / craft card -->
            <div class="flex min-w-[240px] flex-[2_1_280px] flex-col gap-4 rounded-3xl bg-brand-lime p-7 max-[600px]:min-w-0">
                <p class="m-0 text-base font-semibold leading-snug text-brand-lime-500">
                    Entre mis hobbies está la fotografía. Sí, "estoy tomando fotografía"... todavía aprendiendo, así que cualquier parecido con un profesional es pura coincidencia.
                    @if ($hasPublishedGalleryPhotos ?? false)
                        <a href="{{ route('gallery') }}" wire:navigate class="text-brand-lime-700 underline decoration-brand-lime-700/50 underline-offset-2 transition-colors hover:text-brand-lime-900 hover:decoration-brand-lime-900">Podés ver más acá</a>.
                    @endif
                </p>
                <div class="relative min-h-[170px] flex-1 overflow-hidden rounded-2xl max-[600px]:min-h-[150px]">
                    <img class="block min-h-[170px] w-full object-cover max-[600px]:min-h-[150px]" src="{{ asset('images/001-UCU.webp') }}" alt="Cyclist group" />
                    {{-- <div class="absolute bottom-[14px] left-[14px] flex flex-col gap-2">
                        <div class="flex gap-2">
                            <span class="w-fit rounded-full border border-white/80 bg-black/15 px-[14px] py-1 text-[0.75rem] font-medium text-white backdrop-blur-sm">Código</span>
                        </div>
                        <div class="ml-14 flex gap-2">
                            <span class="w-fit rounded-full border border-white/80 bg-black/15 px-[14px] py-1 text-[0.75rem] font-medium text-white backdrop-blur-sm">Foto</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-fit rounded-full border border-white/80 bg-black/15 px-[14px] py-1 text-[0.75rem] font-medium text-white backdrop-blur-sm">Producto</span>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- Stats panel -->
            <div class="flex min-w-[260px] flex-[3_1_320px] flex-col justify-between gap-7 rounded-3xl bg-brand-lime px-10 py-9 max-[600px]:px-6 max-[600px]:py-6">
                <div>
                    <h2 class="font-cond mb-3 m-0 text-[clamp(1.75rem,3.2vw,2.5rem)] font-black leading-[1.15] text-brand-lime-500">
                        Años escribiendo código, resolviendo problemas y sobreviviendo a litros de café.
                    </h2>
                    <p class="m-0 max-w-[580px] text-base leading-[1.65] text-[#333]">
                        Desde landing pages hasta paneles de administración y e-commerce. Laravel es mi base, Livewire mi aliado y Tailwind el encargado de que todo se vea bien.
                    </p>
                    <p class="mt-2 text-base leading-[1.65] text-[#333]">
                        Y sigo aprendiendo. La tecnología cambia demasiado rápido como para quedarse quieto. Hoy la IA está redefiniendo cómo desarrollamos software, así que cada semana hay algo nuevo por descubrir. Quizá John Connor tenía razón.
                    </p>
                </div>

                <div class="flex flex-wrap items-end gap-8 max-[600px]:gap-5">
                    <div>
                        <div
                            class="font-cond text-[clamp(2rem,4vw,2.6rem)] font-black leading-none text-brand-lime-500"
                            data-count="12"
                            data-count-prefix="+"
                            data-count-once
                        >0</div>
                        <div class="mt-1 text-[0.73rem] font-medium text-[#555]">Experiencia</div>
                    </div>
                    <div>
                        <div
                            class="font-cond text-[clamp(2rem,4vw,2.6rem)] font-black leading-none text-brand-lime-500"
                            data-count="20"
                            data-count-prefix="+"
                            data-count-once
                        >0</div>
                        <div class="mt-1 text-[0.73rem] font-medium text-[#555]">Proyectos</div>
                    </div>
                    <div>
                        <div
                            class="font-cond text-[clamp(2rem,4vw,2.6rem)] font-black leading-none text-brand-lime-500"
                            data-count="2"
                            data-count-once
                        >0</div>
                        <div class="mt-1 text-[0.73rem] font-medium text-[#555]">Mascotas</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-8xl py-16">
        <x-section-head
            kicker="02. proyectos"
            title="Estos son algunos de mis proyectos"
            actionTitle="Ver proyectos"
            actionHref="{{ route('projects') }}"
        />
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-3 lg:grid-cols-4"
            data-reveal="fade-up"
            data-reveal-scroll
            data-reveal-stagger="0.08"
        >
            @foreach (config('projects.entries', []) as $slug => $project)
                <x-cards.projects
                    data-reveal-item
                    :href="route('projects.show', $slug)"
                    wire:navigate
                    :title="$project['title']"
                    :description="$project['excerpt']"
                    :index="$project['index']"
                    :color="$project['color']"
                    :tags="$project['tags']"
                />
            @endforeach
        </div>
    </section>

    {{-- <x-home.intro-columns /> --}}

    <x-home.snapshot-grid />

    @if ($hasPublishedGalleryPhotos ?? false)
        <x-home.gallery-teaser />
    @endif

    {{-- <x-home.games-teaser /> --}}
</div>
