@php
    $chevron = <<<'SVG'
<svg class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h9.546l-3.195-3.195a.75.75 0 011.06-1.06l4.5 4.5a.75.75 0 010 1.06l-4.5 4.5a.75.75 0 11-1.06-1.06l3.195-3.195H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
</svg>
SVG;

    $arrowPair = <<<'SVG'
<span class="inline-flex gap-0.5 text-zinc-400" aria-hidden="true">
    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
</span>
SVG;
@endphp

<section
    id="sobre"
    aria-labelledby="intro-hola"
    data-reveal="fade-up"
    data-reveal-scroll
    data-reveal-stagger="0.12"
>
    <div class="mx-auto max-w-8xl py-16">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-12 lg:gap-10 xl:gap-14">
            {{-- Columna 1: resumen --}}
            <div class="flex flex-col lg:col-span-5" data-reveal-item>
                <a
                    href="#top"
                    class="mb-8 inline-flex w-fit text-xs font-medium tracking-wide text-zinc-400 transition hover:text-zinc-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                >
                    Volver arriba
                </a>

                <h2 id="intro-hola" class="text-4xl font-bold tracking-tight text-black sm:text-5xl lg:text-6xl lg:leading-[1.05]">
                    Hola.
                </h2>

                <div class="mt-6 space-y-4 text-base leading-relaxed text-zinc-600 sm:text-lg">
                    <p>
                        Soy un desarrollador web especializado en <strong class="font-semibold text-zinc-800">Laravel</strong>,
                        <strong class="font-semibold text-zinc-800">Livewire</strong>,
                        <strong class="font-semibold text-zinc-800">Vue</strong> y
                        <strong class="font-semibold text-zinc-800">Tailwind CSS</strong>.
                        Mi pasión radica en crear experiencias de usuario fluidas y funcionales a través de soluciones web innovadoras.
                    </p>
                    <p>
                        Desde que descubrí Laravel en 2018 sentí que había encontrado mi herramienta ideal.
                        Desde entonces he trabajado en proyectos que van desde sistemas de gestión integrados con AWS hasta e-commerce completos.
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="$dispatch('open-contact-modal')"
                    class="group mt-auto flex w-full cursor-pointer items-center justify-between gap-4 border-t border-zinc-200 bg-transparent pt-6 text-left text-sm text-zinc-600 transition hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900 lg:mt-14 lg:pt-8"
                >
                    <span class="leading-snug">Disponible para proyectos remotos · escribime cuando quieras</span>
                    {!! $chevron !!}
                </button>
            </div>

            {{-- Columna 2: historia laboral --}}
            <div class="flex flex-col lg:col-span-3" data-reveal-item>
                <span class="mb-6 text-xs font-medium tabular-nums tracking-widest text-zinc-400">01</span>
                <h3 class="text-lg font-bold text-black">Historia laboral</h3>
                <div class="mt-5 space-y-4 text-sm leading-relaxed text-zinc-600 sm:text-[0.9375rem]">
                    <p>
                        Llevo años acompañando productos digitales: desde landings y sitios institucionales hasta paneles y flujos más complejos.
                        Me interesa el detalle, la performance y que el equipo pueda seguir iterando sin fricción.
                    </p>
                    <p>
                        En conjunto he <strong class="font-semibold text-zinc-800">participado en decenas de proyectos</strong>
                        con equipos multidisciplinarios, siempre con foco en entregas útiles y comunicación clara.
                    </p>
                </div>

                <a
                    href="#"
                    class="group mt-auto flex w-full items-center justify-between gap-4 border-t border-zinc-200 pt-6 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900 lg:mt-14 lg:pt-8"
                >
                    <span>LinkedIn</span>
                    {!! $chevron !!}
                </a>
            </div>

            {{-- Columna 3: curiosidades + herramientas --}}
            <div class="flex flex-col gap-14 lg:col-span-4" data-reveal-item>
                <div>
                    <span class="mb-6 block text-xs font-medium tabular-nums tracking-widest text-zinc-400">02</span>
                    <h3 class="text-lg font-bold text-black">Curiosidades</h3>
                    <p class="mt-5 text-sm leading-relaxed text-zinc-600 sm:text-[0.9375rem]">
                        Aprendo mejor enseñando: suelo documentar lo que configuro y compartir atajos con el equipo.
                        Fuera del teclado, me recargo con música, buena lectura y caminatas largas para ordenar ideas.
                        Cuando tengo tiempo libre salgo a fotografiar — así que si ves algo raro en mi galería, fui yo.
                    </p>
                </div>

                <div>
                    <span class="mb-4 block text-xs font-medium tabular-nums tracking-widest text-zinc-400">03</span>
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-black">Herramientas</h3>
                        {!! $arrowPair !!}
                    </div>

                    <dl class="mt-6 border-t-2 border-zinc-900">
                        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-200 py-3 text-sm">
                            <dt class="text-zinc-500">Lenguajes</dt>
                            <dd class="text-right font-medium text-zinc-900">PHP, JavaScript, SQL</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-200 py-3 text-sm">
                            <dt class="text-zinc-500">Framework</dt>
                            <dd class="text-right font-medium text-zinc-900">Laravel, Livewire <span class="text-zinc-400 font-normal">(sin reinventar la rueda)</span></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-200 py-3 text-sm">
                            <dt class="text-zinc-500">Front</dt>
                            <dd class="text-right font-medium text-zinc-900">Tailwind CSS, Vite <span class="text-zinc-400 font-normal">(sí, uso utility classes, no me arrepiento)</span></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-200 py-3 text-sm">
                            <dt class="text-zinc-500">Entorno</dt>
                            <dd class="text-right font-medium text-zinc-900">Git, Docker, CI <span class="text-zinc-400 font-normal">(porque "en mi máquina funciona" no escala)</span></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 border-b border-zinc-200 py-3 text-sm">
                            <dt class="text-zinc-500">Música</dt>
                            <dd class="text-right font-medium text-zinc-900">Michael Jackson, Phil Collins <span class="text-zinc-400 font-normal">(nada de playlists aleatorias)</span></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 py-3 text-sm">
                            <dt class="text-zinc-500">Bebida</dt>
                            <dd class="text-right font-medium text-zinc-900">Café <span class="text-zinc-400 font-normal">(Coca-Cola si el café traicionó)</span></dd>
                        </div>
                    </dl>
                </div>

                <a
                    href="{{ route('blog') }}"
                    wire:navigate
                    class="group mt-auto flex w-full items-center justify-between gap-4 border-t border-zinc-200 pt-6 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900 lg:pt-8"
                >
                    <span>Blog</span>
                    {!! $chevron !!}
                </a>
            </div>
        </div>
    </div>
</section>
