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
                        'La plataforma facilita la inversión colectiva, donde múltiples usuarios pueden aportar capital para financiar distintos proyectos inmobiliarios y obtener retornos según el rendimiento de cada propiedad. Los usuarios tienen acceso a información detallada de cada proyecto, incluyendo estimaciones de rentabilidad, plazos de inversión, y opciones de seguimiento de su capital invertido.',
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
            'excerpt' => 'Plataforma web para explorar catálogos de cerámicas, colecciones exclusivas y recursos para profesionales del diseño y la construcción.',
            'index' => '02',
            'color' => '#e8f38c',
            'tags' => ['PHP', 'Laravel', 'Livewire', 'Javascript', 'BITBUCKET', 'AWS'],
            'role' => 'Backend & frontend',
            'year' => '2023',
            'client' => 'Peronda',
            'url' => null,
            'sections' => [
                [
                    'title' => 'La plataforma',
                    'body' => [
                        'Peronda es una plataforma web para una empresa especializada en el diseño y fabricación de cerámicas de alta calidad. El sitio permite a los usuarios explorar catálogos de productos detallados, consultar colecciones exclusivas, y acceder a recursos para profesionales del diseño y la construcción.',
                        'En el proyecto se buscó ofrecer una experiencia visualmente atractiva, fluida y funcional, optimizada para resaltar la estética y calidad de los productos.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot.jpg',
                    'alt' => 'Vista principal de Peronda',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-1.png',
                    'alt' => 'Captura de Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-2.png',
                    'alt' => 'Detalle de producto Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-3.png',
                    'alt' => 'Catálogo de cerámicas Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-4.png',
                    'alt' => 'Experiencia de navegación Peronda',
                ],
                [
                    'src' => '/images/proyectos/peronda/peronda-screenshot-5.png',
                    'alt' => 'Interfaz de Peronda',
                ],
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
                        'Apadrina un Olivo es una plataforma creada para conectar a personas con el proyecto de revitalización de olivos centenarios en Oliete, un pequeño pueblo de Teruel, España. El sitio permite a los usuarios patrocinar un olivo, ayudar a su conservación, y contribuir al desarrollo sostenible de la región, promoviendo la recuperación de la biodiversidad y apoyando la economía local.',
                        'Además, a través de la plataforma, los patrocinadores pueden recibir actualizaciones de sus olivos, fotos, y detalles sobre el progreso de su apadrinamiento y el impacto en la comunidad.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen.png',
                    'alt' => 'Vista principal de Apadrina un Olivo',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen-2.png',
                    'alt' => 'Proceso de apadrinamiento',
                ],
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen-3.png',
                    'alt' => 'Seguimiento del olivo apadrinado',
                ],
                [
                    'src' => '/images/proyectos/apadrinaunolivo/apadrinaunolivo-screen-4.png',
                    'alt' => 'Comunidad y impacto en Oliete',
                ],
            ],
        ],
        'tiles-in-mind' => [
            'title' => 'Tiles in Mind',
            'excerpt' => 'Explorá, personalizá y obtené inspiración para decorar espacios con azulejos: creá y guardá diseños únicos para visualizar combinaciones de colores, patrones y estilos.',
            'index' => '04',
            'color' => '#F2EFE5',
            'tags' => ['PHP', 'Laravel', 'Livewire', 'Javascript', 'BITBUCKET', 'AWS'],
            'role' => 'Full-stack',
            'year' => '2022',
            'client' => 'Tiles in Mind',
            'url' => null,
            'sections' => [
                [
                    'title' => 'La plataforma',
                    'body' => [
                        'Tiles in Mind es una plataforma web diseñada para que los usuarios puedan explorar, personalizar y obtener inspiración para la decoración de espacios con azulejos. Este sitio permite a los usuarios crear y guardar diseños únicos, lo que facilita visualizar cómo lucirán diferentes combinaciones de colores, patrones y estilos en diversos entornos.',
                        'Al combinar tecnología y creatividad, la plataforma ofrece una experiencia interactiva, intuitiva y visualmente atractiva para quienes buscan renovar o embellecer espacios mediante el uso de azulejos.',
                    ],
                ],
            ],
            'images' => [
                [
                    'src' => '/images/proyectos/tilesinmind/tilesinmindscreenshot.png',
                    'alt' => 'Vista principal de Tiles in Mind',
                    'hero' => true,
                ],
                [
                    'src' => '/images/proyectos/tilesinmind/tilesinmindscreenshot-2.png',
                    'alt' => 'Explorador de azulejos',
                ],
                [
                    'src' => '/images/proyectos/tilesinmind/tilesinmindscreenshot-3.png',
                    'alt' => 'Personalización de espacios',
                ],
            ],
        ],
    ],

];
