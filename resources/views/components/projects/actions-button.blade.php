<a {{ $attributes->merge([
    "class" => "group | bg-app-default rounded-full border border-app-default text-xs shadow-sm hover:shadow-none dark:text-zinc-900/90 hover:dark:text-white dark:bg-app-default hover:bg-app-default text-white leading-none flex items-center justify-center"
]) }}>
    {{ $slot }}
    @isset($text)
        <span class="pr-2 group-hover:text-white">{{ $text }}</span>
    @endisset
</a>
