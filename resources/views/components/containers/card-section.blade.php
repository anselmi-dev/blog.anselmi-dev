<div class="flex flex-col space-y-2 | x-text-base-color">
    <div class="flex justify-between | flex-col xs:flex-row">
        @isset($title)
        <div class="text-lg lg:text-xl uppercase text-gray-app dark:text-white/50 | order-2 xs:order-1 leading-none | w-full flex items-center gap-1">
                @isset($icon)
                    {{ $icon }}
                @endisset
                {{ $title }}
            </div>
        @endisset

        @isset($actions)
            <div class="flex items-center gap-1 order-1 xs:order-2 justify-end">
                {{ $actions }}
            </div>
        @endisset
    </div>
    <div class="text-base">
        {{ $slot }}
    </div>
</div>
