<?php

namespace App\Support;

final class CssColor
{
    public static function isHex(?string $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }

        return (bool) preg_match('/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $value);
    }

    public static function hexOrNull(?string $value): ?string
    {
        return self::isHex($value) ? $value : null;
    }
}
