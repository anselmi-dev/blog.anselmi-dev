<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fuentes (Google Fonts) — /servicios/fuentes
    |--------------------------------------------------------------------------
    */
    'fonts' => [
        'plus-jakarta-sans' => [
            'name' => 'Plus Jakarta Sans',
            'family' => '"Plus Jakarta Sans", sans-serif',
            'tailwind' => 'font-sans',
            'category' => 'Sans-serif',
            'weights' => '400, 500, 600, 700, 800',
            'sample' => 'Hakuna Matata',
            'google' => 'https://fonts.google.com/specimen/Plus+Jakarta+Sans',
            'bunny' => 'https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800',
            'css' => "font-family: 'Plus Jakarta Sans', sans-serif;",
            'note' => 'Fuente principal del sitio (layout).',
        ],
        'playfair-display' => [
            'name' => 'Playfair Display',
            'family' => '"Playfair Display", serif',
            'tailwind' => "font-['Playfair_Display']",
            'category' => 'Serif',
            'weights' => '400, 600, 700',
            'sample' => 'Hakuna Matata',
            'google' => 'https://fonts.google.com/specimen/Playfair+Display',
            'bunny' => 'https://fonts.bunny.net/css?family=playfair-display:400,600,700',
            'css' => "font-family: 'Playfair Display', serif;",
            'note' => 'Elegante para titulares editoriales.',
        ],
        'space-grotesk' => [
            'name' => 'Space Grotesk',
            'family' => '"Space Grotesk", sans-serif',
            'tailwind' => "font-['Space_Grotesk']",
            'category' => 'Sans-serif',
            'weights' => '400, 500, 600, 700',
            'sample' => 'Hakuna Matata',
            'google' => 'https://fonts.google.com/specimen/Space+Grotesk',
            'bunny' => 'https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700',
            'css' => "font-family: 'Space Grotesk', sans-serif;",
            'note' => 'Geométrica, ideal para UI moderna.',
        ],
        'jetbrains-mono' => [
            'name' => 'JetBrains Mono',
            'family' => '"JetBrains Mono", monospace',
            'tailwind' => "font-['JetBrains_Mono']",
            'category' => 'Monospace',
            'weights' => '400, 500, 600',
            'sample' => 'hakuna_matata()',
            'google' => 'https://fonts.google.com/specimen/JetBrains+Mono',
            'bunny' => 'https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600',
            'css' => "font-family: 'JetBrains Mono', monospace;",
            'note' => 'Para código y etiquetas técnicas.',
        ],
        'fraunces' => [
            'name' => 'Fraunces',
            'family' => '"Fraunces", serif',
            'tailwind' => "font-['Fraunces']",
            'category' => 'Serif variable',
            'weights' => '400, 600, 700',
            'sample' => 'Hakuna Matata',
            'google' => 'https://fonts.google.com/specimen/Fraunces',
            'bunny' => 'https://fonts.bunny.net/css?family=fraunces:400,600,700',
            'css' => "font-family: 'Fraunces', serif;",
            'note' => 'Serif expresiva con carácter “soft”.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Colores — /servicios/colores (columnas tipo brand guide)
    |--------------------------------------------------------------------------
    */
    'color_columns' => [
        [
            [
                'name' => 'Brand Lime 50',
                'hex' => '#F6F4EE',
                'rgb' => [246, 244, 238],
                'cmyk' => [0, 1, 3, 4],
                'span' => 'sm',
                'ink' => 'dark',
            ],
            [
                'name' => 'Brand Lime',
                'hex' => '#EEEADD',
                'rgb' => [238, 234, 221],
                'cmyk' => [0, 2, 7, 7],
                'span' => 'md',
                'ink' => 'dark',
            ],
            [
                'name' => 'Brand Lime 300',
                'hex' => '#CCC099',
                'rgb' => [204, 192, 153],
                'cmyk' => [0, 6, 25, 20],
                'span' => 'md',
                'ink' => 'dark',
            ],
            [
                'name' => 'Brand Lime 700',
                'hex' => '#665A33',
                'rgb' => [102, 90, 51],
                'cmyk' => [0, 12, 50, 60],
                'span' => 'lg',
                'ink' => 'light',
            ],
        ],
        [
            [
                'name' => 'Light',
                'hex' => '#F1F5F8',
                'rgb' => [241, 245, 248],
                'cmyk' => [3, 1, 0, 3],
                'span' => 'sm',
                'ink' => 'dark',
            ],
            [
                'name' => 'City Sky',
                'hex' => '#BCE8ED',
                'rgb' => [188, 232, 237],
                'cmyk' => [16, 2, 0, 7],
                'span' => 'md',
                'ink' => 'dark',
            ],
            [
                'name' => 'Real Blue',
                'hex' => '#055193',
                'rgb' => [5, 81, 147],
                'cmyk' => [97, 45, 0, 42],
                'span' => 'md',
                'ink' => 'light',
            ],
            [
                'name' => 'Smudged Blue',
                'hex' => '#25525C',
                'rgb' => [37, 82, 92],
                'cmyk' => [83, 33, 0, 64],
                'span' => 'lg',
                'ink' => 'light',
            ],
        ],
        [
            [
                'name' => 'Blue Grey',
                'hex' => '#C6CFD4',
                'rgb' => [198, 207, 212],
                'cmyk' => [7, 2, 0, 17],
                'span' => 'sm',
                'ink' => 'dark',
            ],
            [
                'name' => 'Rooftop Grey',
                'hex' => '#36494D',
                'rgb' => [54, 73, 77],
                'cmyk' => [30, 5, 0, 70],
                'span' => 'md',
                'ink' => 'light',
            ],
            [
                'name' => 'Asphalt',
                'hex' => '#1C2729',
                'rgb' => [28, 39, 41],
                'cmyk' => [32, 5, 0, 84],
                'span' => 'md',
                'ink' => 'light',
            ],
            [
                'name' => 'Smooth Black',
                'hex' => '#131414',
                'rgb' => [19, 20, 20],
                'cmyk' => [0, 0, 0, 92],
                'span' => 'lg',
                'ink' => 'light',
            ],
        ],
        [
            [
                'name' => 'Sandstone',
                'hex' => '#FAF8E7',
                'rgb' => [250, 248, 231],
                'cmyk' => [0, 1, 8, 2],
                'span' => 'xl',
                'ink' => 'dark',
            ],
            [
                'name' => 'Matte Orange',
                'hex' => '#E89B5C',
                'rgb' => [232, 155, 92],
                'cmyk' => [0, 33, 60, 9],
                'span' => 'sm',
                'ink' => 'dark',
            ],
            [
                'name' => 'Bright Red',
                'hex' => '#D64545',
                'rgb' => [214, 69, 69],
                'cmyk' => [0, 68, 68, 16],
                'span' => 'sm',
                'ink' => 'light',
            ],
            [
                'name' => 'Deep Red',
                'hex' => '#7A1F2B',
                'rgb' => [122, 31, 43],
                'cmyk' => [0, 75, 65, 52],
                'span' => 'md',
                'ink' => 'light',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Herramientas / stack — /servicios/herramientas
    |--------------------------------------------------------------------------
    */
    'tools' => [
        [
            'name' => 'Laravel',
            'tagline' => 'Backend & estructura de la app',
            'url' => 'https://laravel.com',
            'category' => 'Backend',
            'icon' => 'laravel',
        ],
        [
            'name' => 'Livewire',
            'tagline' => 'UI dinámica sin SPA pesada',
            'url' => 'https://livewire.laravel.com',
            'category' => 'Frontend',
            'icon' => 'livewire',
        ],
        [
            'name' => 'Tailwind CSS',
            'tagline' => 'Estilos utility-first',
            'url' => 'https://tailwindcss.com',
            'category' => 'Frontend',
            'icon' => 'tailwindcss',
        ],
        [
            'name' => 'Alpine.js',
            'tagline' => 'Interacciones ligeras en el cliente',
            'url' => 'https://alpinejs.dev',
            'category' => 'Frontend',
            'icon' => 'alpinedotjs',
        ],
        [
            'name' => 'Vite',
            'tagline' => 'Build y hot reload',
            'url' => 'https://vitejs.dev',
            'category' => 'Tooling',
            'icon' => 'vite',
        ],
        [
            'name' => 'GSAP',
            'tagline' => 'Animaciones y motion',
            'url' => 'https://gsap.com',
            'category' => 'Motion',
            'icon' => 'greensock',
        ],
        [
            'name' => 'MySQL',
            'tagline' => 'Base de datos relacional',
            'url' => 'https://www.mysql.com',
            'category' => 'Data',
            'icon' => 'mysql',
        ],
        [
            'name' => 'AWS',
            'tagline' => 'Infra y despliegue',
            'url' => 'https://aws.amazon.com',
            'category' => 'Cloud',
            'icon' => 'amazonaws',
        ],
    ],

];
