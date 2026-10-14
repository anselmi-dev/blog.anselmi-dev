<?php

declare(strict_types=1);

namespace App\Actions\Translations;

use RuntimeException;

/**
 * La traducción no se pudo hacer; el mensaje se muestra tal cual en el panel.
 */
class TranslationFailed extends RuntimeException {}
