@php
    $class = match ($attributes->get('size')) {
        'small' => 'px-5 pb-4 pt-2 sm:px-6 sm:pb-3 sm:pt-3 md:px-8 md:pb-4 md:pt-4 lg:px-10 lg:pb-5 lg:pt-5',
        default => 'px-5 pb-8 pt-2 sm:px-8 sm:pb-10 sm:pt-4 md:px-12 md:pb-12 lg:px-14 lg:pt-14 lg:pb-14'
    };
@endphp
<div
    {{ $attributes->class([$class]) }}
>
    {{ $slot }}
</div>
