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
                'Está escrito en TypeScript, sin dependencias externas. La animación de esta página es la demo del proyecto original.',
            ],
            'demo' => 'ascii-ocean',
            'image' => 'images/curious-code/ascii-sharks.jpg',
            'author' => 'stackedlol',
            'author_url' => 'https://github.com/stackedlol',
            'repo_url' => 'https://github.com/stackedlol/ascii-sharks',
            // Ilustración ASCII propia (no es el arte ni el código del repo original,
            // que no tiene licencia): silueta de tiburón generada acá con funciones
            // geométricas simples, pensada para leerse como una "foto convertida a
            // ASCII" al estilo del README del proyecto, pero de diseño propio.
            'ascii_art' => <<<'ART'
                                     *
                                     **
                                    ***
                                    ***                              ***
                                   *****                             *****
                                   *****                            ********
                                   ******                           *******
                                  ********                         *******
                          %%%################%%                    ******
                      %#####******************####%@              ******
                    %#*****************************##%@           *****
                   #%******+++++++++++++++++++*********#%%       *****
                  #*%+%@++-+---+++++++++++++++++++++++*****#%%%********
                 #++#======---=-+++++=++++====++++++++++++++***********
                ^.-=#======--=--========================================
                . ...----------=-===========-------------------=========
                    *.::-------------------------------:::-=+*%==========
                      =:::::::---------------:::::::::=#          =======
                         +:::::::::::::::::::::::-*%               ====
                          +++%#+-:::::::-=+*#%
                          ++++++
                           +++++
                            ++++
                            ++++
                             +++
                              +
                ART,
            'language' => 'TypeScript',
            'tags' => ['TypeScript', 'ASCII Art', 'Canvas'],
            'color' => '#dce5eb',
            'index' => '01',
        ],
    ],
];
