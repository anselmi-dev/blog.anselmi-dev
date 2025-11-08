{{--
    Agregar fondo blanco con sombra
    bg-white dark:bg-zinc-800 px-5 md:px-10 rounded shadow
--}}
<div {{ $attributes->merge(['class' => "divide-y divide-gray-200 dark:divide-gray-700"])}}>
    {{ $slot }}
</div>
