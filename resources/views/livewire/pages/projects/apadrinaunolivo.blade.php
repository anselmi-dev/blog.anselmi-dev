<x-containers.content>
    <section class="w-full mx-auto py-10 md:py-24 | grid grid-cols-1  lg:gap-x-8 lg:gap-y-4">
        <div class="flex flex-col space-y-10 text-gray-app dark:text-white text-base">
            <div class="w-full">
                <p class="font-semibold leading-7 text-app-default dark:text-app-default">
                    {{ __('Desarrollo') }}
                </p>

                <h1
                    class="mt-2 text-pretty text-4xl font-semibold tracking-tigh sm:text-5xl | uppercase">
                    Apadrina un Olivo
                </h1>

                <p class="mt-6">
                    Apadrina un Olivo es una plataforma creada para conectar a personas con el proyecto de revitalización de olivos centenarios en Oliete, un pequeño pueblo de Teruel, España. El sitio permite a los usuarios patrocinar un olivo, ayudar a su conservación, y contribuir al desarrollo sostenible de la región, promoviendo la recuperación de la biodiversidad y apoyando la economía local. Además, a través de la plataforma, los patrocinadores pueden recibir actualizaciones de sus olivos, fotos, y detalles sobre el progreso de su apadrinamiento y el impacto en la comunidad.
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
                                'Alpine',
                                'Javascript',
                                'Javascript',
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
        <div class="mt-16 sm:mt-20">
            <div class="-my-4 flex justify-center gap-5 py-4 sm:gap-8">
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded bg-zinc-100 sm:w-72 sm:rounded-2xl dark:bg-zinc-800 rotate-2">
                    <img
                        class="absolute inset-0 h-full w-full object-cover"
                        src="{{ asset('images/projects/apadrinaunolivo/apadrinaunolivo-screen.png') }}">
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded bg-zinc-100 sm:w-72 sm:rounded-2xl dark:bg-zinc-800 rotate-2">
                    <img
                        class="absolute inset-0 h-full w-full object-cover"
                        src="{{ asset('images/projects/apadrinaunolivo/apadrinaunolivo-screen-2.png') }}">
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded bg-zinc-100 sm:w-72 sm:rounded-2xl dark:bg-zinc-800 rotate-2">
                    <img
                        class="absolute inset-0 h-full w-full object-cover"
                        src="{{ asset('images/projects/apadrinaunolivo/apadrinaunolivo-screen-3.png') }}">
                </div>
                <div
                    class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded bg-zinc-100 sm:w-72 sm:rounded-2xl dark:bg-zinc-800 rotate-2">
                    <img
                        class="absolute inset-0 h-full w-full object-cover"
                        src="{{ asset('images/projects/apadrinaunolivo/apadrinaunolivo-screen-4.png') }}">
                </div>
            </div>
        </div>
    </section>
</x-containers.content>
