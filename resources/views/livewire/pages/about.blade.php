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
                        <h1 class="text-4xl font-bold tracking-tight">
                            <span class="x-text-base-primary">
                                {{ __("Hi, I'm Carlos Anselmi") }}
                            </span>
                            <span>-</span>
                            <span>{{ __("I'm a Full Stack Web Developer") }}.</span>
                            <span class="opacity-60">
                                {{ __("I love Laravel") }}.
                            </span>
                        </h1>
                    </div>
                </div>
                <!-- END:ME -->

                <div class="space-y-4 max-w-5xl | x-text-base-color">
                    <div>
                        <span class="[&:has(~.active)]:opacity-25 [&.active~*]:opacity-25 transition-opacity duration-200">Desarrollador Full-Stack con más de 5 años de experiencia enfrentando desafíos con</span>
                        <x-tooltip-like>
                            <span class="font-bold x-text-base-primary">PHP</span>
                            <x-slot name="message">
                                <svg class="fill-indigo-500" xmlns="http://www.w3.org/2000/svg" width="17" height="14" aria-hidden="true">
                                    <path fill-rule="nonzero" d="M2.014 3.68c.276-1.267.82-2.198 1.629-2.79C4.453.295 5.627 0 7.167 0c.514 0 .908.02 1.185.061L5.035 10.49c-.75 2.494-2.429 3.66-5.035 3.496L2.014 3.68Zm8.648 0c.237-1.227.77-2.147 1.6-2.76C13.09.307 14.274 0 15.814 0c.514 0 .909.02 1.185.061L13.683 10.49c-.79 2.494-2.468 3.66-5.035 3.496L10.662 3.68Z" />
                                </svg>
                                <p>
                                    This component is AWESOME. The hover feature is well-thought-out. Even the smaller details, like using colors, really helps everything stay organized. Cruip is amazing and I really enjoy using it.
                                </p>
                                <p>
                                    Mary Smith <span class="text-slate-600">-</span> <span class="text-slate-400">Software Engineer</span>
                                </p>
                            </x-slot>
                        </x-tooltip-like>,
                        <x-tooltip-like>
                            <span class="font-bold x-text-base-primary">Laravel</span>
                            <x-slot name="message">
                                <svg class="fill-indigo-500" xmlns="http://www.w3.org/2000/svg" width="17" height="14" aria-hidden="true">
                                    <path fill-rule="nonzero" d="M2.014 3.68c.276-1.267.82-2.198 1.629-2.79C4.453.295 5.627 0 7.167 0c.514 0 .908.02 1.185.061L5.035 10.49c-.75 2.494-2.429 3.66-5.035 3.496L2.014 3.68Zm8.648 0c.237-1.227.77-2.147 1.6-2.76C13.09.307 14.274 0 15.814 0c.514 0 .909.02 1.185.061L13.683 10.49c-.79 2.494-2.468 3.66-5.035 3.496L10.662 3.68Z" />
                                </svg>
                                <p>
                                    This component is AWESOME. The hover feature is well-thought-out. Even the smaller details, like using colors, really helps everything stay organized. Cruip is amazing and I really enjoy using it.
                                </p>
                                <p>
                                    Mary Smith <span class="text-slate-600">-</span> <span class="text-slate-400">Software Engineer</span>
                                </p>
                            </x-slot>
                        </x-tooltip-like>, <x-link href="https://nuxt.com/" target="_blank" title="NUXT">Livewire</x-link>, <x-link href="https://nuxt.com/" target="_blank" title="NUXT">AWS</x-link>, <x-link href="https://nuxt.com/" target="_blank" title="NUXT">Git</x-link>, <x-link href="https://nuxt.com/" target="_blank" title="NUXT">Alpine</x-link>, <x-link href="https://nuxt.com/" target="_blank" title="NUXT">Tailwind</x-link> y <x-link href="https://nuxt.com/" target="_blank" title="NUXT">Vue</x-link>.
                        <span class="[&:has(~.active)]:opacity-25 [&.active~*]:opacity-25 transition-opacity duration-200">
                            Además, poseo conocimientos básicos-intermedios en Electron.js, React, Ionic y Nuxt, adquiridos en pequeños desarrollos que han surgido a lo largo de mi carrera.
                        </span>
                        <div class="[&:has(~.active)]:opacity-25 [&.active~*]:opacity-25 transition-opacity duration-200">
                            Actualmente, estoy en el aprendizaje de <x-link href="https://nuxt.com/" target="_blank" title="NUXT">Phyton</x-link>, para ampliar mis habilidades y mantenerme al día en el mundo del desarrollo.
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- My projects -->
                    <x-containers.card class="col-span-full">
                        <x-about.section :title="__('My projects')">
                            <div x-data="{ tab: 'tab1' }">
                                <div class="p-4 relative overflow-hidden">
                                    <div class="h-[2rem] w-[95%] | bg-gradient-to-b from-transparent via-transparent via-99% to-white  dark:to-zinc-800 | absolute pointer-events-none z-10 | bottom-0"></div>
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
                                                :class="{ 'bg-app-600 text-white dark:text-white rounded-full': tab == 'tab1'}"
                                                @click.prevent="tab = 'tab1'"
                                                >Tab 1</a>
                                        </li>
                                        <li class="flex-1">
                                            <a
                                                class="block py-0 px-2 text-center dark:text-gray-500"
                                                href="#"
                                                :class="{ 'bg-app-600 text-white dark:text-white rounded-full': tab == 'tab2'}"
                                                @click.prevent="tab = 'tab2'"
                                                >Tab 2</a>
                                        </li>
                                        <li class="flex-1">
                                            <a
                                                class="block py-0 px-2 text-center dark:text-gray-500"
                                                href="#"
                                                :class="{ 'bg-app-600 text-white dark:text-white rounded-full': tab == 'tab3'}"
                                                @click.prevent="tab = 'tab3'"
                                                >Tab 3</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:My projects  -->

                    <!-- My experience -->
                    <x-containers.card class="col-span-2 | relative overflow-hidden">
                        <x-about.section :title="__('Work Experiences')">
                            <div class="relative">
                                <div class="h-[2rem] w-[95%] | bg-gradient-to-b from-white dark:from-zinc-800 via-transparent via-2% | absolute pointer-events-none z-10"></div>
                                <div class="h-[2rem] w-[95%] | bg-gradient-to-b from-transparent via-transparent via-99% to-white  dark:to-zinc-800 | absolute pointer-events-none z-10 | bottom-0"></div>
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
                                <section class="splide h-full" aria-label="{{ __('My dogs') }}" x-data="Splide({ options: { type: 'loop' } })">
                                    <div class="splide__track h-full">
                                        <ul class="splide__list h-full">
                                            <li class="splide__slide h-full | relative">
                                                <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 x-text-base-primary dark:x-text-base-primary shadow">Drogo</span>
                                                </div>
                                                <img src="{{ asset('images/drogo.jpg') }}" alt="" class="object-cover absolute min-w-full min-h-full">
                                            </li>
                                            <li class="splide__slide | relative">
                                                <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 x-text-base-primary dark:x-text-base-primary shadow">Mooncake</span>
                                                </div>
                                                <img src="{{ asset('images/drogo.jpg') }}" alt="" class="object-cover absolute min-w-full min-h-full">
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
                            <div class="relative rounded-md overflow-hidden h-[160px]">
                                <img src="{{ asset('images/map.png') }}" alt="Drogo" class="min-w-full min-h-full">
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:Where do I live -->

                    <!-- My photos -->
                    <x-containers.card class="col-span-2 sm:col-span-1 | relative overflow-hidden">
                        <x-about.section :title="__('My photos')">
                            <x-slot name="actions">
                                <x-about.actions-button wire:navigate href="{{ route('gallery.index') }}">
                                    <x-heroicon-m-arrow-up-circle class="h-6 w-6"/>
                                    <x-slot name="text">
                                        {{ __('More') }}
                                    </x-slot>
                                </x-about.actions-button>
                            </x-slot>
                            <div class="relative rounded-md overflow-hidden bg-gray-200 | h-[152px]">
                                <section class="splide h-full bg-gray-500" aria-label="{{ __('My photos') }}" x-data="Splide({ options: { type: 'loop' } })">
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
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </x-about.section>
                    </x-containers.card>
                    <!-- END:What do I hear -->
                </div>
            </div>
        </div>
    </x-containers.content>
</div>
