<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use Filament\Support\Contracts\HasLabel;

/**
 * Estilo del cursor del sitio, elegido en Filament (Ajustes → Sitio). El de
 * círculo se dibuja en resources/js/custom-cursor.js.
 */
enum CursorStyle: string implements HasLabel
{
    case Default = 'default';
    case Circle = 'circle';

    public function getLabel(): string
    {
        return match ($this) {
            self::Default => 'Cursor normal del sistema',
            self::Circle => 'Círculo (anillo + punto)',
        };
    }

    /**
     * Estilo guardado en los ajustes del sitio.
     */
    public static function current(): self
    {
        return self::fromSetting(Setting::site()['cursor_style'] ?? null);
    }

    public static function fromSetting(mixed $value): self
    {
        return is_string($value) ? (self::tryFrom($value) ?? self::Circle) : self::Circle;
    }
}
