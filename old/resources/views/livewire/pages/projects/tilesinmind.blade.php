<x-containers.content>
    <div class="py-10 mx-auto lg:flex">
        <div
            class="mx-auto grid grid-cols-1 gap-x-12 gap-y-16 lg:mx-0 lg:min-w-full lg:max-w-none lg:flex-none lg:gap-y-8">
            <div class="lg:col-end-1 lg:w-full lg:max-w-lg lg:pb-8 text-gray-app dark:text-white text-base | flex flex-col space-y-10">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-app-default dark:text-app-default">
                        {{ __('Desarrollo') }}
                    </h2>
                    <h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">
                        Tiles in Mind
                    </h1>
                    <p class="mt-6 text-xl leading-8">
                        Tiles in Mind es una plataforma web diseñada para que los usuarios puedan explorar, personalizar
                        y obtener inspiración para la decoración de espacios con azulejos. Este sitio permite a los
                        usuarios crear y guardar diseños únicos, lo que facilita visualizar cómo lucirán diferentes
                        combinaciones de colores, patrones y estilos en diversos entornos. Al combinar tecnología y
                        creatividad, la plataforma ofrece una experiencia interactiva, intuitiva y visualmente atractiva
                        para quienes buscan renovar o embellecer espacios mediante el uso de azulejos.
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
            <div class="flex flex-wrap items-start justify-end gap-6 sm:gap-8 lg:contents">
                <div class="w-0 flex-auto lg:ml-auto lg:w-auto lg:flex-none lg:self-end">
                    <img src="{{ asset('images/projects/tilesinmind/tilesinmindscreenshot.png') }}"
                        alt="Screenshot Tiles in Mind"
                        class="aspect-[7/5] w-[37rem] max-w-none rounded-2xl bg-gray-50 object-cover">
                </div>
                <div
                    class="contents lg:col-span-2 lg:col-end-2 lg:ml-auto lg:flex lg:w-[37rem] lg:items-start lg:justify-end lg:gap-x-8">
                    <div class="order-first flex w-64 max-w-full flex-none justify-end self-end lg:self-start lg:w-auto">
                        <img src="{{ asset('images/projects/tilesinmind/tilesinmindscreenshot-2.png') }}"
                            alt="Screenshot Tiles in Mind"
                            class="aspect-[4/3] w-[24rem] max-w-none flex-none rounded-2xl bg-gray-50 object-cover">
                    </div>
                    <div class="flex w-96 max-w-full flex-auto justify-end lg:w-auto lg:flex-none">
                        <img src="{{ asset('images/projects/tilesinmind/tilesinmindscreenshot-3.png') }}"
                            alt="Screenshot Tiles in Mind"
                            class="aspect-[7/5] w-[37rem] max-w-none flex-none rounded-2xl bg-gray-50 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-containers.content>
