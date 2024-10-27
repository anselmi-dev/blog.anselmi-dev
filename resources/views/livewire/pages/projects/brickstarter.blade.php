<x-containers.content>
    <section class="w-full mx-auto py-10 md:py-24 | grid grid-cols-1 lg:grid-cols-2 lg:gap-x-8 lg:gap-y-4">
        <div class="flex flex-col space-y-10 text-gray-app dark:text-white text-base">
            <div class="w-full">
                <p class="font-semibold leading-7 text-app-default dark:text-app-default">
                    {{ __('Desarrollo') }}
                </p>

                <h1
                    class="mt-2 text-pretty text-4xl font-semibold tracking-tigh sm:text-5xl | uppercase">
                    Brickstarter
                </h1>

                <p class="mt-6">
                    Brickstarter es una plataforma de inversión en propiedades inmobiliarias que permite a los usuarios
                    invertir en proyectos seleccionados y diversificar su portafolio de bienes raíces sin necesidad de
                    adquirir una propiedad completa.
                </p>
                <p class="mt-6">
                    La plataforma facilita la inversión colectiva, donde múltiples usuarios pueden aportar capital para
                    financiar distintos proyectos inmobiliarios y obtener retornos según el rendimiento de cada
                    propiedad. Los usuarios tienen acceso a información detallada de cada proyecto, incluyendo
                    estimaciones de rentabilidad, plazos de inversión, y opciones de seguimiento de su capital
                    invertido.
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
                                'Javascript',
                                'Lemonway',
                                'GIT',
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

        <div class="lg:row-span-2 mt-16">
            <div class="grid grid-cols-2 gap-4 sm:-mx-16 sm:grid-cols-4 lg:mx-0 lg:grid-cols-2 lg:gap-4 xl:gap-8">
                <div
                    class="aspect-square overflow-hidden rounded shadow-xl outline-1 -outline-offset-1 outline-black/10">
                    <img alt=""
                        src="{{ asset('images/projects/brickstarter/brickstarted-screen-1.jpg') }}"
                        class="block size-full object-cover">
                </div>
                <div
                    class="-mt-8 aspect-square overflow-hidden rounded shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                    <img alt=""
                        src="{{ asset('images/projects/brickstarter/brickstarted-screen-2.jpg') }}"
                        class="block size-full object-cover">
                </div>
                <div
                    class="aspect-square overflow-hidden rounded shadow-xl outline-1 -outline-offset-1 outline-black/10">
                    <img alt=""
                        src="{{ asset('images/projects/brickstarter/brickstarted-screen-3.jpg') }}"
                        class="block size-full object-cover">
                </div>
                <div
                    class="-mt-8 aspect-square overflow-hidden rounded shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                    <img alt=""
                        src="{{ asset('images/projects/brickstarter/brickstarted-screen-4.jpg') }}"
                        class="block size-full object-cover">
                </div>
            </div>
        </div>
    </section>
</x-containers.content>
