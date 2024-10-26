<x-containers.content>
    <section class="w-full mx-auto py-10 md:py-24 | grid grid-cols-1 lg:grid-cols-2 lg:gap-x-8 lg:gap-y-4">
        <div class="flex flex-col space-y-10">
            <div class="w-full">
                <p class="text-base font-semibold leading-7 text-app-default">
                    {{ __('Project') }}
                </p>

                <h1 class="mt-2 text-pretty text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl | uppercase">
                    Brickstarter
                </h1>

                <p class="mt-6 text-base/7 text-gray-600">
                    Brickstarter es una plataforma de inversión en propiedades inmobiliarias que permite a los usuarios invertir en proyectos seleccionados y diversificar su portafolio de bienes raíces sin necesidad de adquirir una propiedad completa.
                </p>
                <p class="mt-6 text-base/7 text-gray-600">
                    La plataforma facilita la inversión colectiva, donde múltiples usuarios pueden aportar capital para financiar distintos proyectos inmobiliarios y obtener retornos según el rendimiento de cada propiedad. Los usuarios tienen acceso a información detallada de cada proyecto, incluyendo estimaciones de rentabilidad, plazos de inversión, y opciones de seguimiento de su capital invertido.
                </p>
            </div>

            <div class="lg:col-span-1">
                <h2 class="text-2xl uppercase leading-7 text-gray-500">
                    Tecnologías
                </h2>
                <hr class="mt-6 border-t border-gray-200">

                <div class="details mt-6">
                    <span class="info flex flex-col space-y-1 top-0">
                        <div class="flex flex-wrap gap-0.5">
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

        <div class="lg:row-span-2">
            <div class="-mx-8 grid grid-cols-2 gap-4 sm:-mx-16 sm:grid-cols-4 lg:mx-0 lg:grid-cols-2 lg:gap-4 xl:gap-8">
                <div class="aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10">
                    <img alt="" src="https://images.unsplash.com/photo-1590650516494-0c8e4a4dd67e?&auto=format&fit=crop&crop=center&w=560&h=560&q=90" class="block size-full object-cover">
                </div>
                <div class="-mt-8 aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                    <img alt="" src="https://images.unsplash.com/photo-1557804506-669a67965ba0?&auto=format&fit=crop&crop=left&w=560&h=560&q=90" class="block size-full object-cover">
                </div>
                <div class="aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10">
                    <img alt="" src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?&auto=format&fit=crop&crop=left&w=560&h=560&q=90" class="block size-full object-cover">
                </div>
                <div class="-mt-8 aspect-square overflow-hidden rounded-xl shadow-xl outline-1 -outline-offset-1 outline-black/10 lg:-mt-40">
                    <img alt="" src="https://images.unsplash.com/photo-1598257006458-087169a1f08d?&auto=format&fit=crop&crop=center&w=560&h=560&q=90" class="block size-full object-cover">
                </div>
            </div>
        </div>
    </section>
</x-containers.content>