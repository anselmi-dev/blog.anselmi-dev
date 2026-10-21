<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proyectos de "Código curioso", clave = slug en /cosas-curiosas/{slug}
    |--------------------------------------------------------------------------
    |
    | Son proyectos de otras personas que me parecieron curiosos y quiero
    | dar a conocer. No son míos: acá solo se muestra el enlace al original.
    */
    'entries' => [
        'ascii-sharks' => [
            'title' => 'ascii-sharks',
            'excerpt' => 'Tiburones y peces dibujados con caracteres ASCII, animados en un canvas, en TypeScript y sin dependencias.',
            'description' => [
                'ascii-sharks dibuja tiburones y peces como arte ASCII dentro de un canvas, con un motor (OceanEngine) al que le indicás la especie y el tamaño del tiburón para generar la animación.',
                'Está escrito en TypeScript, sin dependencias externas, e incluye una demo que corre en el navegador con `npm install && npm run dev`.',
            ],
            'author' => 'stackedlol',
            'author_url' => 'https://github.com/stackedlol',
            'repo_url' => 'https://github.com/stackedlol/ascii-sharks',
            'language' => 'TypeScript',
            'tags' => ['TypeScript', 'ASCII Art', 'Canvas'],
            'color' => '#dce5eb',
            'index' => '01',
        ],
    ],
];
