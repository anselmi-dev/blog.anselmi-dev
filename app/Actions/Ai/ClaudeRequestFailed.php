<?php

declare(strict_types=1);

namespace App\Actions\Ai;

use RuntimeException;

/**
 * El pedido a Claude no se pudo hacer; el mensaje se muestra tal cual en el panel.
 */
class ClaudeRequestFailed extends RuntimeException {}
