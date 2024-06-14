<div class="flex flex-col space-y-2 | text-zinc-800 dark:text-white">
    <div class="flex justify-between">
        @isset($title)
            <div class="text-lg text-gray-500 dark:text-white/50">
                {{ $title }}
            </div>
        @endisset

        {{-- @isset($actions) --}}
            <div class="flex items-center gap-1">
                <button type="button" class="bg-gray-100 rounded-full text-dark text-xs shadow-sm hover:shadow-none dark:text-zinc-900/90 hover:bg-app-600 leading-none flex items-center justify-center">
                    <x-heroicon-m-arrow-up-circle class="h-6 w-6"/>
                </button>
                <button type="button" class="bg-gray-100 rounded-full text-dark text-xs shadow-sm hover:shadow-none dark:text-zinc-900/90 hover:bg-app-600 leading-none flex items-center justify-center">
                    <x-heroicon-m-arrow-up-circle class="h-6 w-6"/>
                </button>
            </div>
        {{-- @endisset --}}
    </div>
    <div class="text-base">
        {{ $slot }}
    </div>
</div>
