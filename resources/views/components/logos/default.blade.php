
<span class="sr-only">{{ config('app.name') }}</span>
<img
    @if ($attributes->has('class'))
        class="{{ $attributes->get('class') }}"
    @else
        class="h-8 w-auto"
    @endif
    src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="{{ config('app.name') }}">
