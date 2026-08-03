<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entradas del blog (nota | imagen), clave = slug en /blog/{slug}
    |--------------------------------------------------------------------------
    */
    'entries' => [
        'nota-iterar' => [
            'kind' => 'note',
            'kicker' => 'Mar 2025 · proceso',
            'title' => 'Iterar sin drama',
            'excerpt' => 'Acordar límites por entrega: qué entra y qué queda fuera, sin prometer el universo.',
            'body' => [
                'Cuando el producto pide velocidad, la deuda técnica aparece disfrazada de “después lo arreglamos”. Prefiero que el alcance sea explícito en la mesa: qué entra en esta entrega, qué queda fuera y qué se revisa a la semana siguiente.',
                'Escribo estas notas para futuro yo y para quien labure en el mismo código: contexto breve, decisiones y trade-offs. Nada de manifiestos.',
                'Si el equipo puede moverse rápido sin sentir que está mintiendo al roadmap, ya ganamos la mitad del partido.',
            ],
        ],
        'nota-livewire' => [
            'kind' => 'note',
            'kicker' => 'Feb 2025 · stack',
            'title' => 'Laravel y Livewire',
            'excerpt' => 'Formularios, modales y wire:navigate: menos capas si respetás convenciones.',
            'body' => [
                'No es magia: es convención, buenas abstracciones y menos capas de las que parece. Los formularios, modales y la navegación SPA con wire:navigate me ahorran mesas de decisión eternas en proyectos chicos y medianos.',
                'Cuando algo crece, separo dominio, policies y vistas. El framework ya trae el hilo; el resto es disciplina y revisiones de código que no duelan.',
            ],
        ],
        'foto-bento-r' => [
            'kind' => 'image',
            'kicker' => 'Fotografía · referencia',
            'title' => 'Luz lateral en fachada',
            'excerpt' => 'Toma usada en el bento del blog: contraste piedra / madera.',
            'caption' => 'Imagen de prueba para el layout bento (Picsum). En producción reemplazala por tu archivo o CDN.',
            'body' => [
                'Me gusta alternar bloques de texto con fotos para que la lectura respire. La misma pieza puede vivir como miniatura en la rejilla y con más contexto acá.',
            ],
            'seed' => 'bento-r',
            'alt' => 'Fachada con luz natural',
        ],
        'foto-bento-l' => [
            'kind' => 'image',
            'kicker' => 'Fotografía · referencia',
            'title' => 'Encuadre vertical',
            'excerpt' => 'Segunda pieza vertical del bento.',
            'caption' => 'Otra referencia visual del grid; útil para probar alturas y recortes.',
            'body' => [],
            'seed' => 'bento-l',
            'alt' => 'Escena en formato vertical',
        ],
        'foto-bento-m' => [
            'kind' => 'image',
            'kicker' => 'Fotografía · referencia',
            'title' => 'Detalle cuadrado',
            'excerpt' => 'Celda casi cuadrada en el bento.',
            'caption' => 'Ideal para detalles, texturas o una sola idea fuerte en el centro.',
            'body' => [
                'En mobile esta imagen se apila con el resto; en escritorio mantiene proporción cuadrada dentro de la rejilla.',
            ],
            'seed' => 'bento-m',
            'alt' => 'Detalle fotográfico',
        ],
        'nota-foto' => [
            'kind' => 'note',
            'kicker' => 'Ene 2025 · oficio',
            'title' => 'Código y fotografía',
            'excerpt' => 'Dos formas de encuadrar: composición y márgenes en UI. Lo de afuera cuenta igual.',
            'body' => [
                'Ambas te enseñan a mirar el margen: qué dejás afuera importa tanto como lo que mostrás.',
                'En el sitio mezclo bloques editoriales con galería y juegos — mismo criterio de ritmo y espacio en blanco.',
            ],
        ],
        'nota-docs' => [
            'kind' => 'note',
            'kicker' => 'Dic 2024 · equipo',
            'title' => 'Documentar lo mínimo',
            'excerpt' => 'README corto, decisiones en PR y un lugar para links. Eso ya salva a la siguiente persona.',
            'body' => [
                'No hace falta un manual de cien páginas el día uno. Un README que diga cómo levantar el proyecto, dónde están los envs y un enlace al board o al canal ya reduce fricción.',
                'Las decisiones importantes pueden vivir en el cuerpo del PR o en ADRs cortos cuando el trade-off lo vale.',
            ],
        ],
        'nota-no' => [
            'kind' => 'note',
            'kicker' => 'Nov 2024 · producto',
            'title' => 'Decir que no con datos',
            'excerpt' => 'Cuando el scope crece, mostrar coste en tiempo y riesgo antes que asentir.',
            'body' => [
                '“Sí” rápido sin estimación suele convertirse en trabajo gratis y en frustración. Prefiero traducir el pedido a días u horas y señalar qué se deja de hacer si entra esto.',
                'No es bloquear: es alinear expectativas con algo tangible en la mesa.',
            ],
        ],
        'nota-tests' => [
            'kind' => 'note',
            'kicker' => 'Oct 2024 · calidad',
            'title' => 'Pruebas que importan',
            'excerpt' => 'Happy path + dos bordes raros suelen bastar al inicio; el resto cuando duela de verdad.',
            'body' => [
                'Cubrir el camino feliz y un par de casos borde que ya te hayan mordido da más retorno que perseguir el 100 % de cobertura en pantallas que cambian cada sprint.',
                'Cuando un bug vuelve, ahí sí agrego el test que falta: la regresión queda documentada en código.',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Celdas del bento (índice /blog). card e image llevan slug que existe en entries.
    |--------------------------------------------------------------------------
    */
    'bento_cells' => [
        [
            'type' => 'intro',
            'title' => 'Notas y borradores',
            'body' => 'Textos cortos sobre cómo trabajo, qué uso en el stack y por qué a veces conviene decir que no. Nada de manifiestos: contexto útil para futuro yo y para quien toque el mismo código.',
            'gridClass' => 'sm:col-span-2 xl:col-span-6',
        ],
        [
            'type' => 'card',
            'slug' => 'nota-iterar',
            'gridClass' => 'xl:col-span-3',
        ],
        [
            'type' => 'image',
            'slug' => 'foto-bento-r',
            'gridClass' => 'min-h-[14rem] sm:min-h-[16rem] xl:col-span-3 xl:row-span-2 xl:min-h-0',
        ],
        [
            'type' => 'image',
            'slug' => 'foto-bento-l',
            'gridClass' => 'min-h-[14rem] sm:min-h-[16rem] xl:col-span-3 xl:row-span-2 xl:min-h-0',
        ],
        [
            'type' => 'card',
            'slug' => 'nota-livewire',
            'gridClass' => 'xl:col-span-3',
        ],
        [
            'type' => 'image',
            'slug' => 'foto-bento-m',
            'gridClass' => 'min-h-[14rem] xl:col-span-3 xl:aspect-square xl:min-h-0',
        ],
        [
            'type' => 'card',
            'slug' => 'nota-foto',
            'gridClass' => 'xl:col-span-3',
        ],
        [
            'type' => 'card',
            'slug' => 'nota-docs',
            'gridClass' => 'xl:col-span-3',
        ],
        [
            'type' => 'card',
            'slug' => 'nota-no',
            'gridClass' => 'xl:col-span-3',
        ],
        [
            'type' => 'card',
            'slug' => 'nota-tests',
            'gridClass' => 'xl:col-span-3',
        ],
    ],
];
