@section('title', __("I'm a Full Stack Web Developer"))

@section('description', __("Hi, I'm Carlos Anselmi"))

<div class="flex flex-col mt-10 mx-auto">
    <x-containers.content>
        <div class="space-y-12">
            <div class="flex flex-col space-y-12">
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
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 space-y-12">
                    <!-- My projects -->
                    <x-containers.card class="col-span-full">
                        <x-containers.card-section :title="__('Developments')">
                            <div class="expanding-cards | grid grid-cols-1 md:grid-cols-2 gap-2">
                                @php
                                    $projects = [
                                        [
                                            'bg' => asset('images/projects/peronda/bg.png'),
                                            'url' => route('project', ['project' => 'peronda']),
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
                                            'url' => route('project', ['project' => 'brickstarter']),
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
                                            'bg' => asset('images/projects/tilesinmind/background.jpg'),
                                            'url' => route('project', ['project' => 'tilesinmind']),
                                            'logo' => asset('images/projects/tilesinmind/logo.svg'),
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
                                            'url' => route('project', ['project' => 'apadrinaunolivo']),
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
                        </x-containers.card-section>
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

                    <!-- What I am reading -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-containers.card-section :title="__('What I am reading')">
                            <a href="https://www.amazon.com/-/es/Shunmyo-Masuno-ebook/dp/B07VYMP378" target="__blank" title="El arte de vivir con sencillez - Shunmyo Masuno">
                                <div class="bg-gray-app h-[152px] rounded p-2 text-white z-1 relative overflow-hidden">
                                    <x-card.external-link/>
                                    <div class="relative rounded-md text-xl pr-10 z-1">
                                        <i>El arte de vivir con sencillez</i>
                                        <br>
                                        - Shunmyo Masuno
                                    </div>
                                    <img src="{{ asset('images/el-arte-de-viviar-con-sencillez.jpg') }}" alt="El arte de vivir con sencillez - Shunmyo Masuno" class="card-book__image">
                                </div>
                            </a>
                        </x-containers.card-section>
                    </x-containers.card>
                    <!-- END:What I am reading -->

                    <!-- Where do I live -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-containers.card-section :title="__('Where do I live?')">
                            <x-slot name="actions">
                                <x-about.actions-button href="https://maps.app.goo.gl/6jtvkALmD64tEbkY9" target="_blank">
                                    {{-- <x-heroicon-m-arrow-up-circle class="h-4 w-4 mr-1"/> --}}
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="injected-svg" data-src="https://cdn.hugeicons.com/icons/maps-circle-01-duotone-rounded.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" >
                                        <path class="fill-current" fill="currentColor" opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M21.9993 12C21.9993 16.3491 19.223 20.0497 15.346 21.4263L2.62891 8.5001C4.04761 4.70338 7.70777 2 11.9993 2C17.5222 2 21.9993 6.47715 21.9993 12ZM14.4991 14C14.7337 14 14.9591 13.9092 15.1277 13.7468C15.2455 13.6334 15.3662 13.5188 15.4884 13.403C16.9721 11.9957 18.6636 10.3913 17.7323 8.15187C17.1866 6.83966 15.8767 6 14.4991 6C13.1215 6 11.8116 6.83966 11.2659 8.15187C10.3383 10.3823 12.0112 11.9747 13.4869 13.3795C13.6169 13.5033 13.7454 13.6256 13.8705 13.7468C14.0391 13.9092 14.2645 14 14.4991 14Z"></path>
                                        <path d="M15.1287 13.7468C14.9601 13.9092 14.7347 14 14.5001 14C14.2655 14 14.0401 13.9092 13.8715 13.7468C12.3272 12.2504 10.2576 10.5788 11.2669 8.15187C11.8126 6.83966 13.1225 6 14.5001 6C15.8777 6 17.1876 6.83966 17.7333 8.15187C18.7413 10.5757 16.6768 12.2555 15.1287 13.7468Z" stroke="#000000" stroke-width="1.5"></path>
                                        <path d="M14.5 9.5H14.509" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#000000" stroke-width="1.5"></path>
                                        <path d="M9 15L5 19M15 21L3 9" stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                    <x-slot name="text">
                                        {{ __('Google maps') }}
                                    </x-slot>
                                </x-about.actions-button>
                            </x-slot>
                            <a href="https://maps.app.goo.gl/6jtvkALmD64tEbkY9" target="__blank">
                                <div class="relative rounded-md overflow-hidden h-[152px] bg-app-default">
                                    <x-card.external-link/>
                                    <img src="{{ asset('images/mapa.svg') }}" alt="Where do I Live?" class="min-w-full min-h-full absolute bottom-0 z-0">
                                </div>
                            </a>
                        </x-containers.card-section>
                    </x-containers.card>
                    <!-- END:Where do I live -->

                    <!-- END:What do I hear -->
                    <x-containers.card class="col-span-2 sm:col-span-2 relative overflow-hidden">
                        <x-containers.card-section :title="__('What do I hear?')">
                            <x-slot name="icon">
                                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none">
                                    <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M10 15.5C10 16.3284 9.32843 17 8.5 17C7.67157 17 7 16.3284 7 15.5C7 14.6716 7.67157 14 8.5 14C9.32843 14 10 14.6716 10 15.5ZM10 15.5V11C10 10.1062 10 9.65932 10.2262 9.38299C10.4524 9.10667 10.9638 9.00361 11.9865 8.7975C13.8531 8.42135 15.3586 7.59867 16 7V13.5M16 13.75C16 14.4404 15.4404 15 14.75 15C14.0596 15 13.5 14.4404 13.5 13.75C13.5 13.0596 14.0596 12.5 14.75 12.5C15.4404 12.5 16 13.0596 16 13.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </x-slot>
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </x-containers.card-section>
                    </x-containers.card>
                    <!-- END:What do I hear -->
                </div>

                <div class="rounded-md bg-app-default dot-pattern p-4 w-full hidden">
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
