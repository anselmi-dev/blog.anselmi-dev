<x-containers.content>
    <div class="py-10">
        <div class="mx-auto max-w-7xl overflow-hidden rounded">
            <div
                class="mx-auto grid grid-cols-1 gap-x-8 gap-y-10 lg:mx-0 lg:grid-cols-2">
                <div class="lg:pr-8">
                    <div class="flex flex-col space-y-10 lg:max-w-lg | text-gray-app dark:text-white text-base">
                        <div class="w-full">
                            <h2 class="text-base font-semibold leading-7 text-app-default dark:text-app-default">
                                {{ __('Desarrollo') }}
                            </h2>
                            <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight sm:text-5xl">
                                Peronda
                            </h1>
                            <p class="mt-6 text-lg leading-8">
                                Peronda es una plataforma web para una empresa especializada en el diseño y fabricación de
                                cerámicas de alta calidad. El sitio permite a los usuarios explorar catálogos de productos
                                detallados, consultar colecciones exclusivas, y acceder a recursos para profesionales del
                                diseño y la construcción. En el proyecto se buscó ofrecer una experiencia visualmente
                                atractiva, fluida y funcional, optimizada para resaltar la estética y calidad de los
                                productos.
                            </p>
                        </div>

                        <div class="lg:col-span-1">
                            <h2 class="text-2xl uppercase leading-7">
                                Tecnologías
                            </h2>
                            <hr class="mt-6 border-t border-gray-200 dark:border-gray-100">
            
                            <div class="details mt-6">
                                <span class="info flex flex-col space-y-1 top-0">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach([
                                            'PHP',
                                            'Laravel',
                                            'Livewire',
                                            'Javascript',
                                            'BITBUCKET',
                                            'AWS',
                                            ] as $tag)
                                            <x-projects.tag class="lg:text-lg">
                                                {{ $tag }}
                                            </x-projects.tag>
                                        @endforeach
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative isolate overflow-hidden bg-gray-100 px-0 pt-8 mx-auto rounded lg:pl-16 lg:pr-0 lg:pt-16 lg:mx-0">
                    <img src="{{ asset('images/projects/peronda/peronda-screenshot.jpg') }}"
                        class="w-full lg:absolute lg:top-0 lg:left-0 lg:h-full lg:w-fit max-w-none lg:max-h-screen">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 col-span-full">
                    <!-- Large item -->
                    <div class="md:col-span-2 md:row-span-2 relative overflow-hidden rounded-2xl shadow-lg group">
                        <img src="{{ asset('images/projects/peronda/peronda-screenshot-1.png') }}" alt="Nature" class="w-full h-full object-cover">
                        {{--
                        <div class="absolute inset-0 bg-black bg-opacity-60 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="text-2xl font-bold text-white">Explore Nature</h3>
                                <p class="text-white">Discover the beauty of the natural world</p>
                            </div>
                        </div>
                        --}}
                    </div>
        
                    <!-- Two small items -->
                    <div class="relative overflow-hidden rounded-2xl shadow-lg group">
                        <img src="{{ asset('images/projects/peronda/peronda-screenshot-2.png') }}" alt="Nature" class="w-full h-full object-cover">
                    </div>

                    <div class="relative overflow-hidden rounded-2xl shadow-lg group">
                        <img src="{{ asset('images/projects/peronda/peronda-screenshot-3.png') }}" alt="Nature" class="w-full h-full object-cover">
                    </div>
        
                    <!-- Three medium items -->
                    <div class="relative overflow-hidden rounded-2xl shadow-lg group">
                        <img src="{{ asset('images/projects/peronda/peronda-screenshot-4.png') }}" alt="Nature" class="w-full h-full object-cover">
                    </div>

                    <div class="relative overflow-hidden rounded-2xl shadow-lg group">
                        <<img src="{{ asset('images/projects/peronda/peronda-screenshot-5.png') }}" alt="Nature" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-containers.content>
