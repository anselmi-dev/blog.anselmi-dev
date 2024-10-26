<div {{ $attributes->merge([
    'class' => "max-w-full | dark:text-zinc-800 text-zinc-100 | nprogress-busy--card"
]) }}>
    {{ $slot }}
</div>
