<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
     * Claude (Anthropic) para las funciones con IA del panel (traducir, notas de canciones). La clave se
     * carga en Ajustes → IA (cifrada); esta del .env queda como alternativa.
     */
    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'workspace_id' => env('ANTHROPIC_WORKSPACE_ID'),
        // El más barato por defecto; para más matiz: claude-sonnet-5-5 o claude-opus-5-5.
        'model' => env('ANTHROPIC_MODEL', env('ANTHROPIC_TRANSLATION_MODEL', 'claude-haiku-5-5')),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
