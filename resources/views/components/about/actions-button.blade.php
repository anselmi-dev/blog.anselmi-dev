<a {{ $attributes->merge([
    "class" => "group | bg-gray-100 rounded-full text-app-default border border-app-default text-xs shadow-sm hover:shadow-none dark:text-zinc-900/90 hover:dark:text-white dark:bg-app-default hover:bg-app-default hover:text-white leading-none flex items-center justify-center"
]) }}>
    {{ $slot }}
    @isset($text)
        <span class="pr-2 group-hover:text-white">{{ $text }}</span>
    @endisset
</a>
