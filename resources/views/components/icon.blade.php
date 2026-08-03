@props([
    /** Nombre en kebab-case, igual que en lucide.dev (ej. arrow-right, heart). */
    'name',
])

<i
    data-lucide="{{ $name }}"
    {{ $attributes }}
></i>
