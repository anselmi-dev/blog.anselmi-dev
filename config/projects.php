<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proyectos del portafolio, clave = slug en /proyectos/{slug}
    |--------------------------------------------------------------------------
    */
    'entries' => [
        'brickstarter' => [
            'title' => 'Brickstarter',
            'excerpt' => 'Plataforma de inversión en propiedades inmobiliarias: invertí en proyectos seleccionados y diversificá tu portafolio sin comprar una propiedad completa.',
            'index' => '01',
            'color' => '#e3f7fa',
            'tags' => ['PHP', 'Laravel', 'Javascript', 'Lemonway', 'GIT', 'AWS'],
            'role' => 'Full-stack',
            'year' => '2024',
            'client' => 'Brickstarter',
            'url' => 'https://brickstarter.com',
            'sections' => [
                [
                    'title' => 'La plataforma',
                    'body' => [
                        'Brickstarter es una plataforma de inversión en propiedades inmobiliarias que permite a los usuarios invertir en proyectos seleccionados y diversificar su portafolio de bienes raíces sin necesidad de adquirir una propiedad completa.',
                        'La plataforma facilita la inversión colectiva, donde múltiples usuarios pueden aportar capital para financiar distintos proyectos inmobiliarios y obtener retornos según el rendimiento de cada propiedad.',
                    ],
                ],
                [
                    'title' => 'Qué ofrece',
                    'body' => [
                        'Los usuarios tienen acceso a información detallada de cada proyecto, incluyendo estimaciones de rentabilidad, plazos de inversión y opciones de seguimiento de su capital invertido.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-1.jpg',
                    'alt' => 'Vista principal de Brickstarter',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-2.jpg',
                    'alt' => 'Detalle de proyectos e inversión en Brickstarter',
                ],
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-3.jpg',
                    'alt' => 'Seguimiento de capital invertido en Brickstarter',
                ],
                [
                    'src' => '/images/proyectos/brickstarter/brickstarted-screen-4.jpg',
                    'alt' => 'Interfaz de Brickstarter',
                ],
            ],
        ],
        'peronda' => [
            'title' => 'Peronda',
            'excerpt' => 'Peronda es una plataforma web para una empresa especializada en el diseño y fabricación de cerámicas de alta calidad.',
            'index' => '02',
            'color' => '#e8f38c',
            'tags' => ['PHP', 'Laravel', 'Livewire', 'Javascript', 'BITBUCKET', 'AWS'],
            'role' => 'Backend & frontend',
            'year' => '2023',
            'client' => 'Peronda',
            'url' => null,
            'sections' => [
                [
                    'title' => 'El desafío',
                    'body' => [
                        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet.',
                        'Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla.',
                    ],
                ],
                [
                    'title' => 'La solución',
                    'body' => [
                        'Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc.',
                        'Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem.',
                    ],
                ],
            ],
            'images' => [
                ['seed' => 'peronda-hero', 'alt' => 'Vista principal de Peronda', 'hero' => true],
                ['seed' => 'peronda-1', 'alt' => 'Catálogo de cerámicas Peronda'],
                ['seed' => 'peronda-2', 'alt' => 'Detalle de producto Peronda'],
                ['seed' => 'peronda-3', 'alt' => 'Experiencia de navegación Peronda'],
            ],
        ],
        'apadrina-un-olivo' => [
            'title' => 'Apadrina un Olivo',
            'excerpt' => 'Plataforma para conectar personas con la revitalización de olivos centenarios en Oliete (Teruel): apadriná un olivo, seguí su conservación y apoyá el desarrollo sostenible de la región.',
            'index' => '03',
            'color' => '#ffe1a1',
            'tags' => ['PHP', 'Laravel', 'Livewire', 'Alpine', 'Javascript', 'GIT', 'AWS'],
            'role' => 'Full-stack',
            'year' => '2023',
            'client' => 'Apadrina un Olivo',
            'url' => null,
            'sections' => [
                [
                    'title' => 'La plataforma',
                    'body' => [
                        'Apadrina un Olivo es una plataforma creada para conectar a personas con el proyecto de revitalización de olivos centenarios en Oliete, un pequeño pueblo de Teruel, España.',
                        'El sitio permite a los usuarios patrocinar un olivo, ayudar a su conservación y contribuir al desarrollo sostenible de la región, promoviendo la recuperación de la biodiversidad y apoyando la economía local.',
                    ],
                ],
                [
                    'title' => 'Qué ofrece',
                    'body' => [
                        'A través de la plataforma, los patrocinadores pueden recibir actualizaciones de sus olivos, fotos y detalles sobre el progreso de su apadrinamiento y el impacto en la comunidad.',
                    ],
                ],
            ],
            'images' => [
                ['seed' => 'olivo-hero', 'alt' => 'Vista principal de Apadrina un Olivo', 'hero' => true],
                ['seed' => 'olivo-1', 'alt' => 'Olivos centenarios en Oliete'],
                ['seed' => 'olivo-2', 'alt' => 'Proceso de apadrinamiento'],
                ['seed' => 'olivo-3', 'alt' => 'Comunidad y seguimiento'],
            ],
        ],
        'tiles-in-mind' => [
            'title' => 'Tiles in Mind',
            'excerpt' => 'Tiles in Mind es una plataforma web diseñada para que los usuarios puedan explorar, personalizar y obtener inspiración para la decoración de espacios con azulejos.',
            'index' => '04',
            'color' => '#F2EFE5',
            'tags' => ['PHP', 'Laravel', 'Livewire', 'Alpine', 'Javascript', 'AWS'],
            'role' => 'Full-stack',
            'year' => '2022',
            'client' => 'Tiles in Mind',
            'url' => null,
            'sections' => [
                [
                    'title' => 'El desafío',
                    'body' => [
                        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Donec sollicitudin molestie malesuada.',
                        'Curabitur aliquet quam id dui posuere blandit. Nulla quis lorem ut libero malesuada feugiat. Proin eget tortor risus.',
                    ],
                ],
                [
                    'title' => 'La solución',
                    'body' => [
                        'Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.',
                        'Pellentesque in ipsum id orci porta dapibus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.',
                    ],
                ],
            ],
            'images' => [
                ['seed' => 'tiles-hero', 'alt' => 'Vista principal de Tiles in Mind', 'hero' => true],
                ['seed' => 'tiles-1', 'alt' => 'Explorador de azulejos'],
                ['seed' => 'tiles-2', 'alt' => 'Personalización de espacios'],
                ['seed' => 'tiles-3', 'alt' => 'Inspiración y moodboards'],
            ],
        ],
    ],

];
