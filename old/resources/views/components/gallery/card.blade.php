@props(['src'])

<div {{ $attributes->whereDoesntStartWith('src') ->merge([
    "class" => "relative"
]) }}>
    <img class="w-full object-cover rounded-lg"
        src="{{ $src }}">
    <div class="absolute inset-0 ring-1 ring-inset ring-black/10 rounded-lg"></div>
</div>
