<x-containers.content>
    <div class="overflow-hidden py-10">
        <div class="mx-auto lg:flex">
            <div
                class="mx-auto grid grid-cols-1 gap-x-12 gap-y-16 lg:mx-0 lg:min-w-full lg:max-w-none lg:flex-none lg:gap-y-8">
                <div class="lg:col-end-1 lg:w-full lg:max-w-lg lg:pb-8 text-gray-app dark:text-white text-base | flex flex-col space-y-10">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-app-default dark:text-app-default">
                            {{ __('Project') }}
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
                                <div class="flex flex-wrap gap-0.5">
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
                        <img src="https://images.unsplash.com/photo-1670272502246-768d249768ca?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1152&q=80"
                            alt="" class="aspect-[7/5] w-[37rem] max-w-none rounded-2xl bg-gray-50 object-cover">
                    </div>
                    <div
                        class="contents lg:col-span-2 lg:col-end-2 lg:ml-auto lg:flex lg:w-[37rem] lg:items-start lg:justify-end lg:gap-x-8">
                        <div class="order-first flex w-64 flex-none justify-end self-start lg:w-auto">
                            <img src="https://images.unsplash.com/photo-1605656816944-971cd5c1407f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=768&h=604&q=80"
                                alt=""
                                class="aspect-[4/3] w-[24rem] max-w-none flex-none rounded-2xl bg-gray-50 object-cover">
                        </div>
                        <div class="flex w-96 flex-auto justify-end lg:w-auto lg:flex-none">
                            <img src="https://images.unsplash.com/photo-1568992687947-868a62a9f521?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1152&h=842&q=80"
                                alt=""
                                class="aspect-[7/5] w-[37rem] max-w-none flex-none rounded-2xl bg-gray-50 object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-containers.content>
