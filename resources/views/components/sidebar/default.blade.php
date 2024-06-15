@props(['logo' => null])

<div
    {{ $attributes->whereDoesntStartWith('x-') }}
    @keydown.window.escape="{{ $attributes->get('x-show') }} = false"
    >
    <div
        {{ $attributes->whereStartsWith('x-show') }}
        {{ $attributes->whereStartsWith('x-ref') }}
        x-cloak
        class="relative z-40"
        aria-modal="true">

        <div
            {{ $attributes->whereStartsWith('x-show') }}
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"

            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"

            class="fixed inset-0 bg-gray-600 dark:bg-zinc-900/90 bg-opacity-75"></div>

        <div class="fixed inset-0 z-40 flex">
            <div
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
                {{ $attributes->whereStartsWith('x-show') }}
                @animation_sidebar
                class="relative flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-zinc-900/90"
                @click.away="{{ $attributes->get('x-show') }} = false">

                <div
                    {{ $attributes->whereStartsWith('x-show') }}
                    x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute top-0 right-0 -mr-12 pt-2">
                </div>

                <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4 pl-12">
                    <div class="flex flex-shrink-0 items-center px-4">
                        {{ $logo }}
                    </div>
                    <nav class="mt-5 space-y-1 px-2">
                        {{ $slot }}
                    </nav>
                </div>
                {{--
                    <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
                        <a href="#" class="group block flex-shrink-0">
                            <div class="flex items-center">
                                <div>
                                    <img class="inline-block h-10 w-10 rounded-full"
                                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                        alt="">
                                </div>
                                <div class="ml-3">
                                    <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">Tom Cook
                                    </p>
                                    <p class="text-sm font-medium text-gray-500 group-hover:text-gray-700">View profile
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                --}}
            </div>

            <div class="w-16 flex-shrink-0">
                <!-- Force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>
</div>
