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
                    <x-icon name="arrow-right" class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" />
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

                <div class="mt-auto border-t border-zinc-200 pt-6 lg:mt-14 lg:pt-8">
                    <div class="flex flex-col gap-3">
                        <a
                            href="https://www.linkedin.com/in/anselmidev/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex w-full items-center justify-between gap-4 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900"
                        >
                            <span>LinkedIn</span>
                            <x-icon name="arrow-right" class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" />
                        </a>
                        <a
                            href="https://x.com/AnselmiDev"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex w-full items-center justify-between gap-4 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900"
                        >
                            <span>X / Twitter</span>
                            <x-icon name="arrow-right" class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" />
                        </a>
                        <a
                            href="https://www.instagram.com/anselmi.dev/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex w-full items-center justify-between gap-4 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900"
                        >
                            <span>Instagram</span>
                            <x-icon name="arrow-right" class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" />
                        </a>
                    </div>
                </div>
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
                        <span class="inline-flex gap-0.5 text-zinc-400" aria-hidden="true">
                            <x-icon name="chevron-left" class="size-4" />
                            <x-icon name="chevron-right" class="size-4" />
                        </span>
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

                @if ($hasPublishedPosts ?? false)
                    <a
                        href="{{ route('blog') }}"
                        wire:navigate
                        class="group mt-auto flex w-full items-center justify-between gap-4 border-t border-zinc-200 pt-6 font-semibold text-black transition hover:text-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-zinc-900 lg:pt-8"
                    >
                        <span>Blog</span>
                        <x-icon name="arrow-right" class="size-4 shrink-0 text-zinc-900 transition-transform duration-200 group-hover:translate-x-0.5" />
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
