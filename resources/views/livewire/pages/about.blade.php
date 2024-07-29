@section('title', __("I'm a Full Stack Web Developer"))

@section('description', __("Hi, I'm Carlos Anselmi"))

<div class="flex flex-col mt-10 mx-auto">
    <x-containers.content>
        <div class="space-y-12">
            <div class="flex flex-col space-y-4">
                <!-- ME -->
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 max-w-3xl">
                    <div class="h-[90px] w-[90px] relative">
                        <img alt="" loading="lazy"
                            class="aspect-square rounded-xl bg-zinc-100 object-cover dark:bg-zinc-800"
                            src="{{ asset('images/DSC_0307.jpg') }}">
                    </div>
                    <div class="flex justify-center flex-col max-w-full flex-1 | x-text-base-color">
                        <h1 class="text-4xl font-bold tracking-tight | font-secondary">
                            <span class="x-text-base-primary">
                                {{ __("Hi, I'm Carlos Anselmi") }}
                            </span>
                            <span>-</span>
                            <span>{{ __("I'm a Full Stack Web Developer") }}.</span>
                        </h1>
                    </div>
                </div>
                <!-- END:ME -->

                <div class="space-y-4 max-w-5xl | x-text-base-color">
                    <div class="space-y-2">
                        <div>
                            Soy un desarrollador web especializado en tecnologías como <x-tooltip-like.card item="laravel" label="Laravel PHP"/>, <x-tooltip-like.card item="livewire" label="Livewire"/>, <x-tooltip-like.card item="vue" label="Vue"/> y  <x-tooltip-like.card item="tailwind" label="Tailwind CSS"/>. Mi pasión radica en crear experiencias de usuario fluidas y funcionales a través de soluciones web innovadoras.
                        </div>

                        <div>
                            Desde que me crucé con Laravel en 2018, fue como encontrar el amor de mi vida en el desarrollo web. Desde entonces, hemos trabajado juntos en una variedad de proyectos, desde sistemas de gestión que interactuan con recursos de AWS hasta e-commerce.
                        </div>

                        <div>
                            Además, actualmente estoy ampliando mis horizontes explorando tecnologías como <x-tooltip-like.card item="electron" label="Electron JS"/> y React, porque, ya sabes, uno nunca sabe cuándo podría necesitar construir una aplicación de escritorio.
                        </div>

                        <div>
                            Cuando no estoy programando, puedes encontrarme explorando cafés locales, practicando fotografía profesional o tomandome una rica coca-cola.
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- My projects -->
                    <x-containers.card class="col-span-full">
                        <x-about.section :title="__('My projects')">
                            <div class="expanding-cards | flex gap-2">
                                @php
                                    $projects = [
                                        [
                                            'bg' => asset('images/projects/peronda/bg.png'),
                                            'logo' => asset('images/projects/peronda/logo-white.png'),
                                            'description' => 'En este proyecto, desarrollé un sistema que permite a los usuarios explorar una amplia gama de productos cerámicos, cada uno cuidadosamente diseñado para aportar valor y elegancia a cualquier espacio. La plataforma facilita la búsqueda de productos según las preferencias del cliente y muestra detalles exhaustivos que destacan la calidad y singularidad de cada pieza.',
                                            'tags' => [
                                                'PHP',
                                                'Laravel',
                                                'Livewire',
                                                'Javascript',
                                                'BITBUCKET',
                                                'AWS',
                                            ]
                                        ],
                                        [
                                            'bg' => asset('images/projects/brickstarter/bg.jpg'),
                                            'logo' => asset('images/projects/brickstarter/logo.svg'),
                                            'description' => 'En este proyecto, desarrollé un sistema que permite a los usuarios explorar una amplia gama de productos cerámicos, cada uno cuidadosamente diseñado para aportar valor y elegancia a cualquier espacio. La plataforma facilita la búsqueda de productos según las preferencias del cliente y muestra detalles exhaustivos que destacan la calidad y singularidad de cada pieza.',
                                            'tags' => [
                                                'PHP',
                                                'Laravel',
                                                'Javascript',
                                                'Lemonway',
                                                'GIT',
                                                'AWS',
                                            ]
                                        ],
                                        [
                                            'bg' => asset('images/projects/tim/bg.jpg'),
                                            'logo' => asset('images/projects/tim/logo.svg'),
                                            'description' => 'En este proyecto, desarrollé un sistema que permite a los usuarios explorar una amplia gama de productos cerámicos, cada uno cuidadosamente diseñado para aportar valor y elegancia a cualquier espacio. La plataforma facilita la búsqueda de productos según las preferencias del cliente y muestra detalles exhaustivos que destacan la calidad y singularidad de cada pieza.',
                                            'tags' => [
                                                'PHP',
                                                'Laravel',
                                                'Livewire',
                                                'Javascript',
                                                'Tailwind',
                                                'GIT',
                                                'AWS',
                                            ]
                                        ],
                                        [
                                            'bg' => asset('images/projects/apadrinaunolivo/bg.jpg'),
                                            'logo' => asset('images/projects/apadrinaunolivo/logo_white.svg'),
                                            'description' => 'En este proyecto, desarrollé un sistema que permite a los usuarios explorar una amplia gama de productos cerámicos, cada uno cuidadosamente diseñado para aportar valor y elegancia a cualquier espacio. La plataforma facilita la búsqueda de productos según las preferencias del cliente y muestra detalles exhaustivos que destacan la calidad y singularidad de cada pieza.',
                                            'tags' => [
                                                'PHP',
                                                'Laravel',
                                                'Livewire',
                                                'Alpine',
                                                'Javascript',
                                                'Javascript',
                                                'GIT',
                                                'AWS',
                                            ]
                                        ]
                                    ];
                                @endphp
                                @foreach ($projects as $project)
                                    <x-projects.card :project="$project"/>
                                @endforeach
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    {{--
                    <x-containers.card class="col-span-full">
                        <x-about.section :title="__('My projects')">
                            <div x-data="{ tab: 'tab1' }">
                                <div class="p-4 relative overflow-hidden">
                                    <div class="h-[2rem] w-[95%] | bg-gradient-to-b from-transparent via-transparent via-99% to-secondary-50  dark:to-zinc-800 | absolute pointer-events-none z-10 | bottom-0"></div>
                                  <div
                                    x-show="tab == 'tab1'"
                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 -translate-y-100"
                                    x-transition:enter-end="opacity-100 translate-y-0"

                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                  >
                                    Tab1 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
                                  </div>
                                  <div
                                    x-show="tab == 'tab2'"
                                    x-cloak
                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 -translate-y-100"
                                    x-transition:enter-end="opacity-100 translate-y-0"

                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                  >
                                    Tab2 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
                                  </div>
                                  <div
                                    x-show="tab == 'tab3'"
                                    x-cloak
                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 -translate-y-100"
                                    x-transition:enter-end="opacity-100 translate-y-0"

                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                  >
                                    Tab3 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
                                  </div>
                                </div>
                                <div>
                                    <ul class="flex bg-gray-100 p-1 dark:bg-zinc-900/90 rounded-full max-w-sm justify-between">
                                        <li class="flex-1">
                                            <a
                                                class="block py-0 px-2 text-center dark:text-gray-500"
                                                href="#"
                                                :class="{ 'bg-app-default text-white dark:text-white rounded-full': tab == 'tab1'}"
                                                @click.prevent="tab = 'tab1'"
                                                >Tab 1</a>
                                        </li>
                                        <li class="flex-1">
                                            <a
                                                class="block py-0 px-2 text-center dark:text-gray-500"
                                                href="#"
                                                :class="{ 'bg-app-default text-white dark:text-white rounded-full': tab == 'tab2'}"
                                                @click.prevent="tab = 'tab2'"
                                                >Tab 2</a>
                                        </li>
                                        <li class="flex-1">
                                            <a
                                                class="block py-0 px-2 text-center dark:text-gray-500"
                                                href="#"
                                                :class="{ 'bg-app-default text-white dark:text-white rounded-full': tab == 'tab3'}"
                                                @click.prevent="tab = 'tab3'"
                                                >Tab 3</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    --}}
                    <!-- END:My projects  -->

                    <!-- My experience -->
                    <x-containers.card class="col-span-2 | relative overflow-hidden">
                        <x-about.section :title="__('Work Experiences')">
                            <div class="relative">
                                <div class="h-[2rem] w-[95%] | bg-gradient-to-b from-secondary-50 dark:from-zinc-800 via-transparent via-2% | absolute pointer-events-none z-10"></div>
                                <div class="h-[2rem] w-[95%] | bg-gradient-to-b from-transparent via-transparent via-99% to-secondary-50  dark:to-zinc-800 | absolute pointer-events-none z-10 | bottom-0"></div>
                                <div class="text-base relative | h-[300px] overflow-auto | scrollbar">
                                    <div class="absolute left-[6px] h-full w-[1px] bg-slate-500 block"></div>
                                    <ul class="space-y-6 py-2">
                                        <li class="max-w-2xl">
                                            <x-about.item :title="__('Infinety.es - Agencia digital')">
                                                <x-slot name="date">2020 - 2024</x-slot>
                                                <p>Full-Stack</p>
                                            </x-about.item>
                                        </li>
                                        <li class="max-w-2xl">
                                            <x-about.item :title="__('Kudos eCommerce')">
                                                <x-slot name="date">2019 - 2020</x-slot>
                                                <p>Front-End</p>
                                            </x-about.item>
                                        </li>
                                        <li class="max-w-2xl">
                                            <x-about.item :title="__('Oenergy.cl – Distribución Solar Chile')">
                                                <x-slot name="date">2015 - 2019</x-slot>
                                                <p>Full Stack</p>
                                            </x-about.item>
                                        </li>
                                        <li class="max-w-2xl">
                                            <x-about.item :title="__('Ocean DevGroup')">
                                                <x-slot name="date">2014 - 2015</x-slot>
                                                <p>Front-End</p>
                                            </x-about.item>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:My experience  -->

                    <!--  My dogs -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-about.section :title="__('My dogs')">
                            <div class="relative rounded-md overflow-hidden bg-gray-200 | h-[300px]">
                                <section class="splide splide--app h-full" aria-label="{{ __('My dogs') }}" x-data="
                                        Splide({
                                            options: { type: 'loop' }
                                        })
                                    ">
                                    <div class="splide__arrows splide__arrows--ltr">
                                        <button
                                          class="splide__arrow splide__arrow--prev hover:bg-app-default opacity-10 hover:opacity-100 transition-all ease-linear ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform "
                                          type="button"
                                          aria-label="Previous slide"
                                          aria-controls="splide01-track"
                                        >
                                            <x-heroicon-s-chevron-right class="transition-all ease-linear"/>
                                        </button>
                                        <button
                                          class="splide__arrow splide__arrow--next hover:bg-app-default opacity-10 hover:opacity-100 transition-all ease-linear ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform "
                                          type="button"
                                          aria-label="Next slide"
                                          aria-controls="splide01-track"
                                        >
                                            <x-heroicon-s-chevron-right class="transition-all ease-linear"/>
                                        </button>
                                    </div>
                                    <div class="splide__track h-full">
                                        <ul class="splide__list h-full">
                                            <li class="splide__slide h-full | relative">
                                                <div class="absolute top-0 p-4 z-1 | splide-tag">
                                                    <span class="bg-app-default rounded-xl px-2 shadow text-sm | flex items-center gap-1">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M102,140a10,10,0,1,1-10-10A10,10,0,0,1,102,140Zm62-10a10,10,0,1,0,10,10A10,10,0,0,0,164,130Zm65.77,10.72a14.24,14.24,0,0,1-5.89,1.29,13.72,13.72,0,0,1-9.88-4.23V184a38,38,0,0,1-38,38H80a38,38,0,0,1-38-38V137.78A13.76,13.76,0,0,1,32.11,142a14.23,14.23,0,0,1-5.88-1.29,13.82,13.82,0,0,1-8-15.34l16.42-88a14,14,0,0,1,17.16-11l.24.07L104.86,42h46.28l52.79-15.51.24-.07a14,14,0,0,1,17.16,11l16.42,88A13.81,13.81,0,0,1,229.77,140.72ZM93.88,51.27,48.84,38a1.9,1.9,0,0,0-1.49.27,2,2,0,0,0-.88,1.32l-16.42,88a2,2,0,0,0,3.54,1.61ZM202,184V122.43L149.06,54H106.94L54,122.43V184a26,26,0,0,0,26,26h42V194.48l-14.24-14.24a6,6,0,0,1,8.48-8.48L128,183.51l11.76-11.75a6,6,0,0,1,8.48,8.48L134,194.48V210h42A26,26,0,0,0,202,184ZM226,127.6l-16.42-88a2,2,0,0,0-.88-1.31,2.07,2.07,0,0,0-1.49-.27l-45,13.23,60.32,78A2,2,0,0,0,226,127.6Z"></path></svg>
                                                        <span>Drogo</span>
                                                    </span>
                                                </div>
                                                <img src="{{ asset('images/drogo.jpg') }}" alt="" class="object-cover absolute min-w-full min-h-full">
                                            </li>
                                            <li class="splide__slide | relative">
                                                <div class="absolute top-0 p-4 z-1 | splide-tag">
                                                    <span class="bg-app-default rounded-xl px-2 shadow text-sm | flex items-center gap-1">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" fill="currentColor"><path d="M208,120.38V184a32,32,0,0,1-32,32H80a32,32,0,0,1-32-32V120.38L104,48h48Z" opacity="0.2"></path><path d="M239.71,125l-16.42-88a16,16,0,0,0-19.61-12.58l-.31.09L150.85,40h-45.7L52.63,24.56l-.31-.09A16,16,0,0,0,32.71,37.05L16.29,125a15.77,15.77,0,0,0,9.12,17.52A16.26,16.26,0,0,0,32.12,144,15.48,15.48,0,0,0,40,141.84V184a40,40,0,0,0,40,40h96a40,40,0,0,0,40-40V141.85a15.5,15.5,0,0,0,7.87,2.16,16.31,16.31,0,0,0,6.72-1.47A15.77,15.77,0,0,0,239.71,125ZM32,128h0L48.43,40,90.5,52.37Zm144,80H136V195.31l13.66-13.65a8,8,0,0,0-11.32-11.32L128,180.69l-10.34-10.35a8,8,0,0,0-11.32,11.32L120,195.31V208H80a24,24,0,0,1-24-24V123.11L107.93,56h40.14L200,123.11V184A24,24,0,0,1,176,208Zm48-80L165.5,52.37,207.57,40,224,128ZM104,140a12,12,0,1,1-12-12A12,12,0,0,1,104,140Zm72,0a12,12,0,1,1-12-12A12,12,0,0,1,176,140Z"></path></svg>
                                                        <span>Mooncake</span>
                                                    </span>
                                                </div>
                                                <img src="{{ asset('images/mooncake.jpg') }}" alt="" class="object-cover absolute min-w-full min-h-full">
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:My dogs -->

                    <!-- What I am reading -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-about.section :title="__('What I am reading')">
                            <div class="h-[300px]">
                                <div class="relative rounded-md text-2xl">
                                    The intelligent investor
                                    <br>
                                    Benjamin Graham
                                </div>
                                <img src="{{ asset('images/51Xur1KZWKL.jpg') }}" style="position: absolute; bottom: -50px; max-width: 50%; right: -5px; max-height: 70%; transform: rotate(15deg); opacity: 0.7;">
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:What I am reading -->

                    <!-- Where do I live -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-about.section :title="__('Where do I live?')">
                            <x-slot name="actions">
                                <x-about.actions-button href="https://maps.app.goo.gl/6jtvkALmD64tEbkY9" target="_blank">
                                    <x-heroicon-m-arrow-up-circle class="h-4 w-4 mr-1"/>
                                    <x-slot name="text">
                                        {{ __('Google maps') }}
                                    </x-slot>
                                </x-about.actions-button>
                            </x-slot>
                            <div class="relative rounded-md overflow-hidden h-[160px]">
                                <img src="{{ asset('images/map.png') }}" alt="Drogo" class="min-w-full min-h-full">
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:Where do I live -->

                    <!-- My photos -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-about.section :title="__('My photos')">
                            <x-slot name="icon">
                                <x-hugeicons-image-crop class="h-5"/>
                            </x-slot>
                            <x-slot name="actions">
                                <x-about.actions-button wire:navigate href="{{ route('gallery.index') }}">
                                    <x-heroicon-m-arrow-up-circle class="h-4 w-4 mr-1"/>
                                    <x-slot name="text">
                                        {{ __('More') }}
                                    </x-slot>
                                </x-about.actions-button>
                            </x-slot>
                            <div class="relative rounded-md overflow-hidden bg-gray-200 | h-[152px]">
                                <section class="splide splide--app h-full bg-gray-500" aria-label="{{ __('My photos') }}" x-data="Splide({ options: { type: 'loop' } })">
                                    <div class="splide__arrows splide__arrows--ltr">
                                        <button
                                          class="splide__arrow splide__arrow--prev hover:bg-app-default opacity-10 hover:opacity-100 transition-all ease-linear ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform "
                                          type="button"
                                          aria-label="Previous slide"
                                          aria-controls="splide01-track"
                                        >
                                            <x-heroicon-s-chevron-right class="transition-all ease-linear"/>
                                        </button>
                                        <button
                                          class="splide__arrow splide__arrow--next hover:bg-app-default opacity-10 hover:opacity-100 transition-all ease-linear ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform "
                                          type="button"
                                          aria-label="Next slide"
                                          aria-controls="splide01-track"
                                        >
                                            <x-heroicon-s-chevron-right class="transition-all ease-linear"/>
                                        </button>
                                    </div>
                                    <div class="splide__track h-full">
                                        <ul class="splide__list h-full">
                                            <li class="splide__slide h-full | relative">
                                                {{-- <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 x-text-base-primary dark:x-text-base-primary shadow">Drogo</span>
                                                </div> --}}
                                                <img src="{{ asset('images/DSC_0307.jpg') }}" class="object-cover absolute min-w-full min-h-full bottom-0 top-0 mx-auto">
                                            </li>
                                            <li class="splide__slide h-full | relative">
                                                {{-- <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 x-text-base-primary dark:x-text-base-primary shadow">Drogo</span>
                                                </div> --}}
                                                <img src="{{ asset('images/DSC_0307.jpg') }}" class="object-cover absolute min-w-full min-h-full bottom-0 top-0 mx-auto">
                                            </li>
                                            <li class="splide__slide h-full | relative">
                                                {{-- <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 x-text-base-primary dark:x-text-base-primary shadow">Drogo</span>
                                                </div> --}}
                                                <img src="{{ asset('images/DSC_0307.jpg') }}" class="object-cover absolute min-w-full min-h-full bottom-0 top-0 mx-auto">
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:My photos -->

                    <!-- END:What do I hear -->
                    <x-containers.card class="col-span-2 relative overflow-hidden">
                        <x-about.section :title="__('What do I hear?')">
                            <x-slot name="icon">
                                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none">
                                    <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M10 15.5C10 16.3284 9.32843 17 8.5 17C7.67157 17 7 16.3284 7 15.5C7 14.6716 7.67157 14 8.5 14C9.32843 14 10 14.6716 10 15.5ZM10 15.5V11C10 10.1062 10 9.65932 10.2262 9.38299C10.4524 9.10667 10.9638 9.00361 11.9865 8.7975C13.8531 8.42135 15.3586 7.59867 16 7V13.5M16 13.75C16 14.4404 15.4404 15 14.75 15C14.0596 15 13.5 14.4404 13.5 13.75C13.5 13.0596 14.0596 12.5 14.75 12.5C15.4404 12.5 16 13.0596 16 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </x-slot>
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:What do I hear -->
                </div>

                <div class="rounded-md bg-app-default dot-pattern p-4 w-full">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-hugeicons-information-square class="h-6 w-6 text-white"/>
                        </div>
                        <div class="ml-2 flex-1 md:flex md:justify-between">
                            <p class="text-sm text-white">Si te ha encantado mi portafolio, ¿podrías darme un like? ¡Solo te tomará 1 segundo!</p>
                            <p class="mt-3 text-sm md:ml-6 md:mt-0">
                                <a href="#" class="whitespace-nowrap font-bold text-white underline | flex">
                                    Me gusta
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-containers.content>
</div>
