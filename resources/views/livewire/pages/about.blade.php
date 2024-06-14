@section('title', 'The title of the page')

@section('description', 'Description of the page')

<div class="flex flex-col mt-10 mx-auto">
    <x-containers.content>
        <div class="space-y-12">
            <div class="flex flex-col space-y-4">
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 max-w-3xl">
                    <div class="h-[90px] w-[90px] relative">
                        <img alt="" loading="lazy"
                            class="aspect-square rounded-xl bg-zinc-100 object-cover dark:bg-zinc-800"
                            src="{{ asset('images/DSC_0307.jpg') }}">
                    </div>
                    <div class="flex justify-center flex-col text-zinc-800 dark:text-zinc-100 max-w-full flex-1">
                        <h1 class="text-4xl font-bold tracking-tight">
                            <span class="text-app-600">Hi I'm Carlos Anselmi</span> - I'm a Developer Web Full Stack. <span class="opacity-60">I' love Laravel.</span>
                        </h1>
                    </div>
                </div>

                <div class="space-y-4 text-base text-zinc-800 dark:text-zinc-100 max-w-5xl">
                    <div>
                        <span class="[&:has(~.active)]:opacity-25 [&.active~*]:opacity-25 transition-opacity duration-200">Desarrollador Full-Stack con más de 5 años de experiencia enfrentando desafíos con</span>
                        <x-tooltip-like>
                            <span class="font-bold text-app-600">PHP</span>
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
                            <span class="font-bold text-app-600">Laravel</span>
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
                {{-- <div>
                    <ul class="flex space-x-2">
                        <li>
                            <a href="https://www.linkedin.com/in/carlos-anselmi/" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" class="injected-svg" data-src="https://cdn.hugeicons.com/icons/linkedin-01-bulk-rounded.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" color="#000000">
                                    <path opacity="0.4" d="M12.0572 1.75H11.9428C9.75214 1.74999 8.03143 1.74998 6.68802 1.93059C5.31137 2.11568 4.21911 2.50271 3.36091 3.36091C2.50272 4.21911 2.11568 5.31137 1.93059 6.68802C1.74998 8.03144 1.74999 9.75212 1.75 11.9428V12.0572C1.74999 14.2479 1.74998 15.9686 1.93059 17.312C2.11568 18.6886 2.50272 19.7809 3.36091 20.6391C4.21911 21.4973 5.31137 21.8843 6.68802 22.0694C8.03144 22.25 9.7521 22.25 11.9428 22.25H12.0572C14.2479 22.25 15.9686 22.25 17.312 22.0694C18.6886 21.8843 19.7809 21.4973 20.6391 20.6391C21.4973 19.7809 21.8843 18.6886 22.0694 17.312C22.25 15.9686 22.25 14.2479 22.25 12.0572V11.9428C22.25 9.75214 22.25 8.03144 22.0694 6.68802C21.8843 5.31137 21.4973 4.21911 20.6391 3.36091C19.7809 2.50271 18.6886 2.11568 17.312 1.93059C15.9686 1.74998 14.2479 1.74999 12.0572 1.75Z" fill="#000000"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7 9.5C7.55229 9.5 8 9.94771 8 10.5V17C8 17.5523 7.55229 18 7 18C6.44772 18 6 17.5523 6 17L6 10.5C6 9.94771 6.44772 9.5 7 9.5ZM11.9114 9.58791C11.7544 9.2412 11.4054 9 11 9C10.4477 9 10 9.44771 10 10V17C10 17.5523 10.4477 18 11 18C11.5523 18 12 17.5523 12 17V13C12 11.8954 12.8954 11 14 11C15.1046 11 16 11.8954 16 13V17C16 17.5523 16.4477 18 17 18C17.5523 18 18 17.5523 18 17V13C18 10.7909 16.2091 9 14 9C13.2346 9 12.5193 9.215 11.9114 9.58791ZM7.00781 8.25C7.69817 8.25 8.25781 7.69036 8.25781 7C8.25781 6.30964 7.69817 5.75 7.00781 5.75H6.99883C6.30848 5.75 5.74883 6.30964 5.74883 7C5.74883 7.69036 6.30848 8.25 6.99883 8.25H7.00781Z" fill="#000000"></path>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://x.com/AnselmiCarlos" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" class="injected-svg" data-src="https://cdn.hugeicons.com/icons/new-twitter-rectangle-bulk-rounded.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" color="#000000">
                                    <path opacity="0.4" d="M12.0573 1.75C14.248 1.74999 15.9687 1.74998 17.3121 1.93059C18.6888 2.11568 19.781 2.50271 20.6392 3.36091C21.4974 4.21911 21.8844 5.31137 22.0695 6.68802C22.2501 8.03144 22.2501 9.7521 22.2501 11.9428V11.9428V12.0572V12.0572C22.2501 14.2479 22.2501 15.9686 22.0695 17.312C21.8844 18.6886 21.4974 19.7809 20.6392 20.6391C19.781 21.4973 18.6888 21.8843 17.3121 22.0694C15.9687 22.25 14.248 22.25 12.0573 22.25H12.0573H11.943H11.9429C9.75223 22.25 8.03156 22.25 6.68815 22.0694C5.31149 21.8843 4.21923 21.4973 3.36104 20.6391C2.50284 19.7809 2.1158 18.6886 1.93072 17.312C1.7501 15.9686 1.75011 14.2479 1.75012 12.0572V11.9428C1.75011 9.75212 1.7501 8.03144 1.93072 6.68802C2.1158 5.31137 2.50284 4.21911 3.36104 3.36091C4.21923 2.50271 5.31149 2.11568 6.68815 1.93059C8.03156 1.74998 9.75224 1.74999 11.9429 1.75H12.0573Z" fill="#000000"></path>
                                    <path d="M6.33235 6.65856C6.46057 6.40779 6.71848 6.25 7.00013 6.25H9.7779C10.0187 6.25 10.2449 6.36565 10.3859 6.56088L12.899 10.0405L16.4698 6.46967C16.7627 6.17678 17.2376 6.17678 17.5305 6.46967C17.8233 6.76256 17.8233 7.23744 17.5305 7.53033L13.7886 11.2722L17.6081 16.5609C17.773 16.7892 17.7961 17.0907 17.6679 17.3414C17.5397 17.5922 17.2818 17.75 17.0001 17.75H14.2223C13.9815 17.75 13.7553 17.6344 13.6143 17.4391L11.1013 13.9595L7.53046 17.5303C7.23756 17.8232 6.76269 17.8232 6.4698 17.5303C6.1769 17.2374 6.1769 16.7626 6.4698 16.4697L10.2117 12.7278L6.39212 7.43912C6.22721 7.21079 6.20413 6.90933 6.33235 6.65856Z" fill="#000000"></path>
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/anselmi-dev" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" class="injected-svg" data-src="https://cdn.hugeicons.com/icons/github-01-bulk-rounded.svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" color="#000000">
                                    <path opacity="0.4" d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12Z" fill="#000000"></path>
                                    <path d="M9.94118 17.8805C9.94118 17.5171 10.0494 17.2012 10.2238 16.9158C10.3434 16.7201 10.2615 16.4423 10.0453 16.3809C8.25526 15.8727 7 15.0556 7 12.3452C7 11.6405 7.22356 10.978 7.61654 10.4014C7.71414 10.2582 7.76181 10.1931 7.77491 10.1216C7.78806 10.0499 7.76638 9.97173 7.72543 9.80179C7.58167 9.20528 7.57017 8.57684 7.73081 7.99064C7.78361 7.79794 7.8968 7.68555 8.10166 7.70725C8.3674 7.7354 8.82753 7.86122 9.50999 8.30151C9.77813 8.4745 9.91221 8.56101 10.0303 8.58036C10.1484 8.59971 10.3062 8.55918 10.622 8.47812C11.0537 8.36728 11.4986 8.30777 12 8.30777C12.5014 8.30777 12.9463 8.36727 13.378 8.47812C13.6938 8.55917 13.8516 8.59971 13.9697 8.58036C14.0878 8.56101 14.2219 8.47451 14.49 8.30152C15.1725 7.86122 15.6326 7.7354 15.8983 7.70725C16.1032 7.68555 16.2164 7.79794 16.2692 7.99064C16.4298 8.57684 16.4183 9.20526 16.2746 9.80178C16.2336 9.97173 16.2119 10.0499 16.2251 10.1216C16.2382 10.1931 16.2859 10.2582 16.3834 10.4014C16.7764 10.978 17 11.6405 17 12.3452C17 15.0556 15.7447 15.8727 13.9547 16.3809C13.7385 16.4423 13.6566 16.7201 13.7762 16.9158C13.9506 17.2012 14.0588 17.5171 14.0588 17.8805V22.5531C13.3925 22.6823 12.7041 22.75 12 22.75C11.2959 22.75 10.6075 22.6823 9.94118 22.5531V19.8519C9.88484 19.8453 9.81816 19.8355 9.74225 19.8211C9.52615 19.78 9.23571 19.7014 8.89714 19.5512C8.21489 19.2486 7.36263 18.667 6.53685 17.5664C6.28826 17.2351 6.35533 16.7649 6.68665 16.5164C7.01798 16.2678 7.48809 16.3348 7.73668 16.6662C8.4109 17.5648 9.06058 17.9828 9.50527 18.18C9.6822 18.2585 9.83089 18.3039 9.94118 18.3301V17.8805Z" fill="#000000"></path>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div> --}}
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-containers.card class="col-span-full">
                        <x-about.section :title="__('My experience')">
                            <div x-data="{ tab: 'tab1' }">
                                <div class="p-4 relative overflow-hidden">
                                  <div
                                    x-show="tab == 'tab1'"
                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 -translate-y-8"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-12"
                                  >
                                    Tab1 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
                                  </div>
                                  <div
                                    x-show="tab == 'tab2'"
                                    x-cloak
                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 -translate-y-8"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-12"
                                  >
                                    Tab2 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
                                  </div>
                                  <div
                                    x-show="tab == 'tab3'"
                                    x-cloak
                                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 -translate-y-8"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-12"
                                  >
                                    Tab3 content. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt sunt, consectetur eos quod perferendis mollitia consequuntur natus, porro molestiae qui iusto deserunt rerum tempore voluptatum itaque. Ad, nisi esse cum quidem consequuntur ullam obcaecati.
                                  </div>

                                </div>
                                <div>
                                    <ul class="flex bg-gray-100 rounded-full max-w-sm justify-between">
                                        <li class="flex-1">
                                            <a
                                                class="block py-1 px-5 text-center"
                                                href="#"
                                                :class="{ 'bg-app-600 text-white rounded-full': tab == 'tab1'}"
                                                @click.prevent="tab = 'tab1'"
                                                >Tab 1</a>
                                        </li>
                                        <li class="flex-1">
                                            <a
                                                class="block py-1 px-5 text-center"
                                                href="#"
                                                :class="{ 'bg-app-600 text-white rounded-full': tab == 'tab2'}"
                                                @click.prevent="tab = 'tab2'"
                                                >Tab 2</a>
                                        </li>
                                        <li class="flex-1">
                                            <a
                                                class="block py-1 px-5 text-center"
                                                href="#"
                                                :class="{ 'bg-app-600 text-white rounded-full': tab == 'tab3'}"
                                                @click.prevent="tab = 'tab3'"
                                                >Tab 3</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </x-about.section>
                    </x-containers.card>
                    <x-containers.card class="col-span-2">
                        <x-about.section :title="__('My experience')">
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

                    <x-containers.card class="col-span-1">
                        <x-about.section :title="__('My dogs')">
                            <div class="relative rounded-md overflow-hidden bg-gray-200 | h-[300px]">
                                <section class="splide h-full" aria-label="{{ __('My dogs') }}" x-data="Splide({ options: { type: 'loop' } })">
                                    <div class="splide__track h-full">
                                        <ul class="splide__list h-full">
                                            <li class="splide__slide h-full | relative">
                                                <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 text-app-600 dark:text-app-600 shadow">Drogo</span>
                                                </div>
                                                <img src="{{ asset('images/drogo.jpg') }}" alt="" class="object-cover absolute">
                                            </li>
                                            <li class="splide__slide | relative">
                                                <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 text-app-600 dark:text-app-600 shadow">Mooncake</span>
                                                </div>
                                                <img src="{{ asset('images/drogo.jpg') }}" alt="" class="object-cover absolute">
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                            </div>
                        </x-about.section>
                    </x-containers.card>

                    <x-containers.card class="col-span-1 relative overflow-hidden">
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

                    <x-containers.card class="relative overflow-hidden">
                        <x-about.section :title="__('Where do I live?')">
                            <div class="relative rounded-md overflow-hidden h-[160px]">
                                <img src="{{ asset('images/map.png') }}" alt="Drogo" class="min-w-full min-h-full">
                            </div>
                        </x-about.section>
                    </x-containers.card>

                    <x-containers.card>
                        <x-about.section :title="__('My photos')">
                            <div class="relative rounded-md overflow-hidden bg-gray-200 | h-[152px]">
                                <section class="splide h-full bg-gray-500" aria-label="{{ __('My photos') }}" x-data="Splide({ options: { type: 'loop' } })">
                                    <div class="splide__track h-full">
                                        <ul class="splide__list h-full">
                                            <li class="splide__slide h-full | relative">
                                                {{-- <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 text-app-600 dark:text-app-600 shadow">Drogo</span>
                                                </div> --}}
                                                <img src="{{ asset('images/DSC_0307.jpg') }}" class="object-cover absolute bottom-0 top-0 mx-auto">
                                            </li>
                                            <li class="splide__slide h-full | relative">
                                                {{-- <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 text-app-600 dark:text-app-600 shadow">Drogo</span>
                                                </div> --}}
                                                <img src="{{ asset('images/DSC_0307.jpg') }}" class="object-cover absolute bottom-0 top-0 mx-auto">
                                            </li>
                                            <li class="splide__slide h-full | relative">
                                                {{-- <div class="absolute top-0 p-4 z-1">
                                                    <span class="bg-white rounded-xl px-4 text-app-600 dark:text-app-600 shadow">Drogo</span>
                                                </div> --}}
                                                <img src="{{ asset('images/DSC_0307.jpg') }}" class="object-cover absolute bottom-0 top-0 mx-auto">
                                            </li>
                                        </ul>
                                    </div>
                                </section>
                            </div>
                        </x-about.section>
                    </x-containers.card>

                    <x-containers.card class="col-span-2 relative overflow-hidden">
                        <x-about.section :title="__('What do I hear?')">
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW?utm_source=generator" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </x-about.section>
                    </x-containers.card>
                </div>
            </div>
        </div>
    </x-containers.content>
</div>


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
@endpush

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script> --}}
    <script>
        // document.addEventListener( 'DOMContentLoaded', function () {
        //     new Splide( '#me-carousel', {
        //         type  : 'fade',
        //     }).mount();
        //     new Splide( '#image-carousel', {
        //         type  : 'fade',
        //     }).mount();
        // });
    </script>
@endpush
