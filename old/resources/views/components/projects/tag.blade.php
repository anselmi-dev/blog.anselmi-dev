<small {{ $attributes->merge([
    "class" => "inline-block border-none p-0.5 uppercase bg-gray-app text-xs leading-none shadow-sm rounded-sm text-white px-1 dark:bg-app-default dark:text-white"
]) }}>
    {{ $slot }}
</small>
