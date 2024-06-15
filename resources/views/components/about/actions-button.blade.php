<a {{ $attributes->merge([
    "class" => "group | bg-gray-100 rounded-full text-dark text-xs shadow-sm hover:shadow-none dark:text-zinc-900/90 hover:bg-app-600 hover:text-white leading-none flex items-center justify-center"
]) }}>
    {{ $slot }}
    @isset($text)
        <span class="pr-2 group-hover:text-white">{{ $text }}</span>
    @endisset
</a>
