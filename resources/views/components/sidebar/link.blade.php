@props(['active' => false])

<a {{ $attributes }}
    @class([
        "group flex items-center rounded-md px-2 py-2 text-base font-medium",
        'bg-black text-white dark:bg-primary-400 dark:text-black' => $active,
        "text-gray-600 hover:bg-gray-50 hover:text-primary-400 dark:text-white dark:hover:bg-transparent dark:hover:text-white" => !$active
    ])>
    <x-heroicon-s-chevron-double-right @class(["w-3 mr-1"])/>
    <span>{{ $slot }}</span>
    <x-heroicon-o-arrow-long-right class="ml-2 h-5 w-5 group-hover:w-5 transition-all"/>
</a>
