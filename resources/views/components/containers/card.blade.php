<div {{ $attributes->merge([
    'class' => "max-w-full rounded bg-secondary-50 dark:bg-zinc-800 py-3 px-4 shadow | dark:text-zinc-800 text-zinc-100 | nprogress-busy--card"
]) }}>
    {{ $slot }}
</div>
