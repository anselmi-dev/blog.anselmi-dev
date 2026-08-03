@props([
    'authorName' => config('app.name', 'Sitio'),
])

<x-site-frame
    {{ $attributes->class(['bg-brand-lime mt-14 transition-colors duration-300']) }}
    data-reveal="fade-up"
    data-reveal-scroll
    data-reveal-threshold="0.05"
    data-reveal-stagger="0.08"
>
    <x-hero-content size="small">
        <div class="w-full" data-reveal-item>
            <x-footer.bottom-bar :author-name="$authorName" />
        </div>
    </x-hero-content>
</x-site-frame>
