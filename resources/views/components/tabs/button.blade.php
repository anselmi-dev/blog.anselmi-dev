@props(['title' => null, 'activeTab'])

<button @click="activeTab = '{{ $activeTab }}'" :class="activeTab === '{{ $activeTab }}' ? 'border-app-primary text-app-primary font-semibold' : 'border-gray-200 dark:border-opacity-20'" class="px-4 py-2 text-sm md:text-base text-left border-l-2 outline-none focus:outline-none">
    {{ $title ?? $slot }}
</button>