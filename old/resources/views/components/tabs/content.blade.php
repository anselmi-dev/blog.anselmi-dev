@props(['activeTab'])

<div x-show="activeTab === '{{ $activeTab }}'"
    role="tabpanel"
    aria-labelledby="tab-{{ $activeTab }}"
    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
    x-transition:enter-start="opacity-0 -translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-12"    
    class="tab-content bg-gray-100/50 dark:bg-gray-app p-2 md:py-4 md:px-4 h-full rounded max-w-full w-full">
    {{ $slot }}
</div>