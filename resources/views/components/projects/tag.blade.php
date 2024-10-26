<small {{ $attributes->merge([
    "class" => "inline-block border-none p-0.5 uppercase bg-gray-app text-base leading-none shadow-sm rounded-sm text-white px-1"
]) }}>
    {{ $slot }}
</small>
