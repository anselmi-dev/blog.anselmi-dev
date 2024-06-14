@section('title', 'The title of the page')

@section('description', 'Description of the page')

<div class="flex flex-col gap-40 mt-20">
    <x-containers.content>
        <div class="grid grid-cols-1 gap-y-16 lg:grid-cols-2 lg:grid-rows-[auto_1fr] lg:gap-y-12 gap-x-12">
            <div class="_lg:pl-20">
                <div class="max-w-full px-2.5 lg:max-w-none relative">
                    <img alt="" loading="lazy"
                        class="aspect-square rotate-3 rounded-2xl bg-zinc-100 object-cover dark:bg-zinc-800 opacity-20"
                        src="{{ asset('images/DSC_0307.jpg') }}">
                    <img alt="" loading="lazy"
                        class="aspect-square -rotate-3 rounded-2xl bg-zinc-200 object-cover dark:bg-zinc-800 absolute top-0 rigth-0 left-0 w-full"
                        src="{{ asset('images/DSC_0307.jpg') }}">
                </div>
            </div>
            <div class="_lg:order-first _lg:row-span-2">
                <h1 class="text-4xl font-bold tracking-tight text-zinc-800 dark:text-zinc-100 sm:text-5xl">
                    ¡Hola! Soy Carlos Anselmi, un apasionado desarrollador Full-Stack.
                </h1>
                <div class="mt-6 space-y-7 text-base text-zinc-600 dark:text-zinc-100">
                    <p>
                        Durante los últimos siete años, he estado sumergido en el apasionante mundo del desarrollo de aplicaciones web complejas, enfrentando valientemente los desafíos que se presentan. Mis principales armas en esta batalla son Laravel, Vue y Tailwind, herramientas que me permiten crear soluciones robustas y eficientes.
                    </p>
                    <p>
                        En la actualidad, me encuentro sumergido en el aprendizaje de NUXT, un potente y versátil framework basado en Vue. Y como alguien que ya posee conocimientos básicos en Python, Node y React, me siento preparado y entusiasmado por ampliar aún más mis conocimientos.
                    </p>
                    <p>
                        Cuando no estoy frente a la computadora, me gusta practicar fotografía, dar paseos con Drogo (mi perro), salir a trotar o dedicar tiempo a alcanzar el nivel de Maestro en League of Legends.
                    </p>
                </div>
            </div>
        </div>
        <div class="w-full text-center flex flex-wrap gap-2 justify-center mt-10">
            <a class="x-button-animation text-white"
                href="https://github.com/lorisleiva/tailwindcss-plugins/tree/master/pagination">GitHub</a>
            <a class="x-button-animation text-white"
                href="https://github.com/lorisleiva/tailwindcss-plugins/tree/master/pagination">Instagram</a>
            <a class="x-button-animation text-white"
                href="https://github.com/lorisleiva/tailwindcss-plugins/tree/master/pagination">Linkedln</a>
            <a class="x-button-animation text-white"
                href="https://github.com/lorisleiva/tailwindcss-plugins/tree/master/pagination">Twitter</a>
        </div>
    </x-containers.content>

    <x-containers.default>
        <section class=" text-slate-900 dark:text-white">
            <div class="text-center w-full mx-auto mb-10">
                <p class="font-display text-xl">
                    Mi experiencia con las tecnologías que he utilizado a largo de mi trayectoria
                </p>
            </div>
            <h2 class="sr-only">Testimonials</h2>
            <div
                class="mx-auto md:max-w-[40rem] lg:max-w-[50rem]"
                x-data="{
                    slider: 'laravel'
                }"
                >
                <div class="pl-10">
                    <ul role="list" class="-mx-4 flex overflow-x-auto pb-4 sm:mx-0 sm:overflow-visible sm:pb-0 lg:col-span-5 md:justify-center">
                        <li class="flex px-4 min-w-[200px]">
                            <button type="button" @click="slider = 'laravel'" :class="{'opacity-40 dark:opacity-10': slider != 'laravel' }" class="dark:text-white text-gray-800">
                                <x-icons.laravel width="158" height="28" class="fill-current full"/>
                            </button>
                        </li>
                        <li class="flex px-4 min-w-[200px]">
                            <button type="button" @click="slider = 'tailwind'" :class="{'opacity-40 dark:opacity-10': slider != 'tailwind' }" class="dark:text-white text-gray-800">
                                <x-icons.tailwind width="158" height="48" class="fill-current full"/>
                            </button>
                        </li>
                        <li class="flex px-4 min-w-[200px]">
                            <button type="button" @click="slider = 'alpine'" :class="{'opacity-40 dark:opacity-10': slider != 'alpine' }" class="dark:text-white text-gray-800">
                                <x-icons.alpinejs width="158" height="48" class="fill-current full"/>
                            </button>
                        </li>
                        <li class="flex px-4 min-w-[200px]">
                            <button type="button" @click="slider = 'vue'" :class="{'opacity-40 dark:opacity-10': slider != 'vue' }" class="dark:text-white text-gray-800">
                                <x-icons.vue  height="48" class="fill-current full"/>
                            </button>
                        </li>
                        <li class="flex px-4 min-w-[200px]">
                            <button type="button" @click="slider = 'aws'" :class="{'opacity-40 dark:opacity-10': slider != 'aws' }" class="dark:text-white text-gray-800">
                                <x-icons.aws  height="48" class="fill-current full"/>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="mt-10 min-h-[200px]">
                    <figure
                        x-show="slider == 'laravel'"
                        x-cloak
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="text-center"
                        id="headlessui-tabs-panel-4"
                        role="tabpanel"
                        aria-labelledby="headlessui-tabs-tab-1"
                        tabindex="0"
                        data-headlessui-state="selected">
                        <blockquote class="text-2xl leading-9">
                            <p>
                                Laravel ha sido un excepcional framework PHP que me ha brindado un conjunto completo de herramientas y recursos para crear aplicaciones web modernas y eficientes. Su ecosistema integral combina funciones integradas con una amplia variedad de paquetes y extensiones compatibles, lo que me ha permitido desarrollar proyectos de manera rápida y escalable.
                            </p>
                        </blockquote>
                    </figure>
                    <figure
                        x-show="slider == 'vue'"
                        x-cloak
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="text-center"
                        id="headlessui-tabs-panel-4"
                        role="tabpanel"
                        aria-labelledby="headlessui-tabs-tab-1"
                        tabindex="0"
                        data-headlessui-state="selected">
                        <blockquote class="text-2xl leading-9 ">
                            <p>
                                Livewire es un Framework que complementa a Laravel y permite crear aplicaciones web dinámicas e interactivas sin tener que escribir una línea de JavaScript.
                                Con Livewire, se pueden crear componentes de interfaz de usuario dinámicas, sin dejar la comodidad de Laravel, lo que facilita la creación de aplicaciones web fluidas y enriquecedoras.
                            </p>
                        </blockquote>
                    </figure>
                    <figure
                        x-show="slider == 'tailwind'"
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-cloak
                        class="text-center"
                        id="headlessui-tabs-panel-4"
                        role="tabpanel"
                        aria-labelledby="headlessui-tabs-tab-1"
                        tabindex="0"
                        data-headlessui-state="selected">
                        <blockquote class="text-2xl leading-9">
                            <p class="before:content-['“'] after:content-['”']">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum, laudantium? Excepturi similique doloremque debitis explicabo laudantium beatae
                            </p>
                        </blockquote>
                    </figure>
                </div>
            </div>
        </section>
    </x-containers.default>

    {{-- <x-containers.default class="overflow-hidden">
        <div
            x-data="{}"
            x-init="$nextTick(() => {
                let ul = $refs.logos;
                ul.insertAdjacentHTML('afterend', ul.outerHTML);
                ul.nextSibling.setAttribute('aria-hidden', 'true');
            })"
            class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-128px),transparent_100%)]"
        >
            <ul x-ref="logos" class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Facebook" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Disney" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Airbnb" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Apple" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Spark" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Samsung" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Quora" />
                </li>
                <li>
                    <img src="./images/DSC_0307.jpg" class="w-[200px]" alt="Sass" />
                </li>
            </ul>
            <ul class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll">
                <li>
                    <img src="./facebook.svg" alt="Facebook" />
                </li>
                <li>
                    <img src="./disney.svg" alt="Disney" />
                </li>
                <li>
                    <img src="./airbnb.svg" alt="Airbnb" />
                </li>
                <li>
                    <img src="./apple.svg" alt="Apple" />
                </li>
                <li>
                    <img src="./spark.svg" alt="Spark" />
                </li>
                <li>
                    <img src="./samsung.svg" alt="Samsung" />
                </li>
                <li>
                    <img src="./quora.svg" alt="Quora" />
                </li>
                <li>
                    <img src="./sass.svg" alt="Sass" />
                </li>
            </ul>
            <ul class="flex items-center justify-center md:justify-start [&_li]:mx-8 [&_img]:max-w-none animate-infinite-scroll" aria-hidden="true">
                <li>
                    <img src="./facebook.svg" alt="Facebook" />
                </li>
                <li>
                    <img src="./disney.svg" alt="Disney" />
                </li>
                <li>
                    <img src="./airbnb.svg" alt="Airbnb" />
                </li>
                <li>
                    <img src="./apple.svg" alt="Apple" />
                </li>
                <li>
                    <img src="./spark.svg" alt="Spark" />
                </li>
                <li>
                    <img src="./samsung.svg" alt="Samsung" />
                </li>
                <li>
                    <img src="./quora.svg" alt="Quora" />
                </li>
                <li>
                    <img src="./sass.svg" alt="Sass" />
                </li>
            </ul>
        </div>
    </x-containers.default> --}}

    <section id="features" aria-label="Features for running your books"
        class="relative overflow-hidden bg-app-default pb-28 pt-20 sm:py-32 w-full"><img alt=""
            loading="lazy" width="2245" height="1636" decoding="async" data-nimg="1"
            class="absolute left-1/2 top-1/2 max-w-none translate-x-[-44%] translate-y-[-42%]"
            style="color:transparent" src="/_next/static/media/background-features.5f7a9ac9.jpg">
        <div class="mx-auto max-w-full lg:max-w-7xl px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-2xl md:mx-auto md:text-center xl:max-w-none">
                <h2 class="font-display text-3xl tracking-tight text-white sm:text-4xl md:text-5xl">Everything you
                    need to run your books.</h2>
                <p class="mt-6 text-lg tracking-tight text-blue-100">Well everything you need if you aren’t that
                    picky about minor details like tax compliance.</p>
            </div>
            <div class="mt-16 grid grid-cols-1 items-center gap-y-2 pt-10 sm:gap-y-6 md:mt-20 lg:grid-cols-12 lg:pt-0">
                <div class="-mx-4 flex overflow-x-auto pb-4 sm:mx-0 sm:overflow-visible sm:pb-0 lg:col-span-5">
                    <div class="relative z-10 flex gap-x-4 whitespace-nowrap px-4 sm:mx-auto sm:px-0 lg:mx-0 lg:block lg:gap-x-0 lg:gap-y-1 lg:whitespace-normal"
                        role="tablist" aria-orientation="vertical">
                        <div
                            class="group relative rounded-full px-4 py-1 lg:rounded-l-xl lg:rounded-r-none lg:p-6 bg-white lg:bg-white/10 lg:ring-1 lg:ring-inset lg:ring-white/10">
                            <h3><button
                                    class="font-display text-lg [&amp;:not(:focus-visible)]:focus:outline-none text-blue-600 lg:text-white"
                                    id="headlessui-tabs-tab-:R2ba9m:" role="tab" type="button"
                                    aria-selected="true" tabindex="0" data-headlessui-state="selected"
                                    aria-controls="headlessui-tabs-panel-:Rda9m:"><span
                                        class="absolute inset-0 rounded-full lg:rounded-l-xl lg:rounded-r-none"></span>Payroll</button>
                            </h3>
                            <p class="mt-2 hidden text-sm lg:block text-white">Keep track of everyone's salaries
                                and whether or not they've been paid. Direct deposit not supported.</p>
                        </div>
                        <div
                            class="group relative rounded-full px-4 py-1 lg:rounded-l-xl lg:rounded-r-none lg:p-6 hover:bg-white/10 lg:hover:bg-white/5">
                            <h3><button
                                    class="font-display text-lg [&amp;:not(:focus-visible)]:focus:outline-none text-blue-100 hover:text-white lg:text-white"
                                    id="headlessui-tabs-tab-:R2ja9m:" role="tab" type="button"
                                    aria-selected="false" tabindex="-1" data-headlessui-state=""
                                    aria-controls="headlessui-tabs-panel-:Rla9m:"><span
                                        class="absolute inset-0 rounded-full lg:rounded-l-xl lg:rounded-r-none"></span>Claim
                                    expenses</button></h3>
                            <p class="mt-2 hidden text-sm lg:block text-blue-100 group-hover:text-white">All of
                                your receipts organized into one place, as long as you don't mind typing in the data
                                by hand.</p>
                        </div>
                        <div
                            class="group relative rounded-full px-4 py-1 lg:rounded-l-xl lg:rounded-r-none lg:p-6 hover:bg-white/10 lg:hover:bg-white/5">
                            <h3><button
                                    class="font-display text-lg [&amp;:not(:focus-visible)]:focus:outline-none text-blue-100 hover:text-white lg:text-white"
                                    id="headlessui-tabs-tab-:R2ra9m:" role="tab" type="button"
                                    aria-selected="false" tabindex="-1" data-headlessui-state=""
                                    aria-controls="headlessui-tabs-panel-:Rta9m:"><span
                                        class="absolute inset-0 rounded-full lg:rounded-l-xl lg:rounded-r-none"></span>VAT
                                    handling</button></h3>
                            <p class="mt-2 hidden text-sm lg:block text-blue-100 group-hover:text-white">We only
                                sell our software to companies who don't deal with VAT at all, so technically we do
                                all the VAT stuff they need.</p>
                        </div>
                        <div
                            class="group relative rounded-full px-4 py-1 lg:rounded-l-xl lg:rounded-r-none lg:p-6 hover:bg-white/10 lg:hover:bg-white/5">
                            <h3><button
                                    class="font-display text-lg [&amp;:not(:focus-visible)]:focus:outline-none text-blue-100 hover:text-white lg:text-white"
                                    id="headlessui-tabs-tab-:R33a9m:" role="tab" type="button"
                                    aria-selected="false" tabindex="-1" data-headlessui-state=""
                                    aria-controls="headlessui-tabs-panel-:R15a9m:"><span
                                        class="absolute inset-0 rounded-full lg:rounded-l-xl lg:rounded-r-none"></span>Reporting</button>
                            </h3>
                            <p class="mt-2 hidden text-sm lg:block text-blue-100 group-hover:text-white">Easily
                                export your data into an Excel spreadsheet where you can do whatever the hell you
                                want with it.</p>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <div id="headlessui-tabs-panel-:Rda9m:" role="tabpanel" tabindex="0"
                        data-headlessui-state="selected" aria-labelledby="headlessui-tabs-tab-:R2ba9m:">
                        <div class="relative sm:px-6 lg:hidden">
                            <div
                                class="absolute -inset-x-4 bottom-[-4.25rem] top-[-6.5rem] bg-white/10 ring-1 ring-inset ring-white/10 sm:inset-x-0 sm:rounded-t-xl">
                            </div>
                            <p class="relative mx-auto max-w-2xl text-base text-white sm:text-center">Keep track of
                                everyone's salaries and whether or not they've been paid. Direct deposit not
                                supported.</p>
                        </div>
                        <div
                            class="mt-10 w-[45rem] overflow-hidden rounded-xl bg-slate-50 shadow-xl shadow-blue-900/20 sm:w-auto lg:mt-0 lg:w-[67.8125rem]">
                            <img alt="" fetchpriority="high" width="2174" height="1464" decoding="async"
                                data-nimg="1" class="w-full" style="color:transparent"
                                sizes="(min-width: 1024px) 67.8125rem, (min-width: 640px) 100vw, 45rem"
                                srcset="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=640&amp;q=75 640w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=750&amp;q=75 750w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=828&amp;q=75 828w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=1080&amp;q=75 1080w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=1200&amp;q=75 1200w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=1920&amp;q=75 1920w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=2048&amp;q=75 2048w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=3840&amp;q=75 3840w"
                                src="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fpayroll.517af4e7.png&amp;w=3840&amp;q=75">
                        </div>
                    </div>
                    <div id="headlessui-tabs-panel-:Rla9m:" role="tabpanel" tabindex="-1" hidden=""
                        style="display:none" data-headlessui-state="" aria-labelledby="headlessui-tabs-tab-:R2ja9m:">
                        <div class="relative sm:px-6 lg:hidden">
                            <div
                                class="absolute -inset-x-4 bottom-[-4.25rem] top-[-6.5rem] bg-white/10 ring-1 ring-inset ring-white/10 sm:inset-x-0 sm:rounded-t-xl">
                            </div>
                            <p class="relative mx-auto max-w-2xl text-base text-white sm:text-center">All of your
                                receipts organized into one place, as long as you don't mind typing in the data by
                                hand.</p>
                        </div>
                        <div
                            class="mt-10 w-[45rem] overflow-hidden rounded-xl bg-slate-50 shadow-xl shadow-blue-900/20 sm:w-auto lg:mt-0 lg:w-[67.8125rem]">
                            <img alt="" fetchpriority="high" width="2174" height="1464" decoding="async"
                                data-nimg="1" class="w-full" style="color:transparent"
                                sizes="(min-width: 1024px) 67.8125rem, (min-width: 640px) 100vw, 45rem"
                                srcset="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=640&amp;q=75 640w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=750&amp;q=75 750w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=828&amp;q=75 828w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=1080&amp;q=75 1080w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=1200&amp;q=75 1200w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=1920&amp;q=75 1920w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=2048&amp;q=75 2048w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=3840&amp;q=75 3840w"
                                src="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fexpenses.3f331919.png&amp;w=3840&amp;q=75">
                        </div>
                    </div>
                    <div id="headlessui-tabs-panel-:Rta9m:" role="tabpanel" tabindex="-1" hidden=""
                        style="display:none" data-headlessui-state="" aria-labelledby="headlessui-tabs-tab-:R2ra9m:">
                        <div class="relative sm:px-6 lg:hidden">
                            <div
                                class="absolute -inset-x-4 bottom-[-4.25rem] top-[-6.5rem] bg-white/10 ring-1 ring-inset ring-white/10 sm:inset-x-0 sm:rounded-t-xl">
                            </div>
                            <p class="relative mx-auto max-w-2xl text-base text-white sm:text-center">We only sell
                                our software to companies who don't deal with VAT at all, so technically we do all
                                the VAT stuff they need.</p>
                        </div>
                        <div
                            class="mt-10 w-[45rem] overflow-hidden rounded-xl bg-slate-50 shadow-xl shadow-blue-900/20 sm:w-auto lg:mt-0 lg:w-[67.8125rem]">
                            <img alt="" fetchpriority="high" width="2174" height="1464" decoding="async"
                                data-nimg="1" class="w-full" style="color:transparent"
                                sizes="(min-width: 1024px) 67.8125rem, (min-width: 640px) 100vw, 45rem"
                                srcset="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=640&amp;q=75 640w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=750&amp;q=75 750w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=828&amp;q=75 828w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=1080&amp;q=75 1080w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=1200&amp;q=75 1200w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=1920&amp;q=75 1920w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=2048&amp;q=75 2048w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=3840&amp;q=75 3840w"
                                src="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fvat-returns.7402820f.png&amp;w=3840&amp;q=75">
                        </div>
                    </div>
                    <div id="headlessui-tabs-panel-:R15a9m:" role="tabpanel" tabindex="-1" hidden=""
                        style="display:none" data-headlessui-state="" aria-labelledby="headlessui-tabs-tab-:R33a9m:">
                        <div class="relative sm:px-6 lg:hidden">
                            <div
                                class="absolute -inset-x-4 bottom-[-4.25rem] top-[-6.5rem] bg-white/10 ring-1 ring-inset ring-white/10 sm:inset-x-0 sm:rounded-t-xl">
                            </div>
                            <p class="relative mx-auto max-w-2xl text-base text-white sm:text-center">Easily export
                                your data into an Excel spreadsheet where you can do whatever the hell you want with
                                it.</p>
                        </div>
                        <div
                            class="mt-10 w-[45rem] overflow-hidden rounded-xl bg-slate-50 shadow-xl shadow-blue-900/20 sm:w-auto lg:mt-0 lg:w-[67.8125rem]">
                            <img alt="" fetchpriority="high" width="2174" height="1464" decoding="async"
                                data-nimg="1" class="w-full" style="color:transparent"
                                sizes="(min-width: 1024px) 67.8125rem, (min-width: 640px) 100vw, 45rem"
                                srcset="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=640&amp;q=75 640w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=750&amp;q=75 750w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=828&amp;q=75 828w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=1080&amp;q=75 1080w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=1200&amp;q=75 1200w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=1920&amp;q=75 1920w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=2048&amp;q=75 2048w, /_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=3840&amp;q=75 3840w"
                                src="/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Freporting.2ad6f065.png&amp;w=3840&amp;q=75">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
