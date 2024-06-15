@props([
    'active' => false
])

<a  {{ $attributes }}
    @class([
        'font-semibold' => $active,
        "text-sm leading-6 py-2 relative | transition text-black dark:text-white font-ligth"
    ])>
    {{ $slot }}
    <span
        @class([
            "block absolute inset-x-1 -bottom-px h-px bg-gradient-to-r from-app-default/0 via-app-default/40 to-app-default/0 dark:from-primary-700/0 dark:via-primary-700/40 dark:to-primary-700/0",
            'hidden' => !$active
        ])>
    </span>
</a>
