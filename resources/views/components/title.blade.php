@props(['title', 'number' => null])

<div class="flex item-center justify-center w-full">
    @if ($number)
        <span class="x-text-base-primary mr-1">
            {{ $number }}
        </span>
    @endif
    <span class="font-semibold dark:text-white">
        {{ $title }}
    </span>

    <span class="flex-1 flex justify-center items-center pl-2">
        <hr class="w-full dark:opacity-20">
    </span>
</div>