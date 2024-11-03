@props(['title', 'href', 'company', 'date'])

<div>
    <h2 class="text-sm md:text-md | flex items-center flex-wrap whitespace-nowrap gap-1 | font-semibold">
        <span>{{ $title }}</span>
        <a href="{{ $href }}" class="text-app-primary font-semibold" target="__blank">
            {{ $company }}
        </a>
    </h2>
    <p class="text-xxs mb-1 dark:text-white/70">{{ $date }}</p>
</div>