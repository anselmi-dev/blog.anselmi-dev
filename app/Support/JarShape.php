<?php

declare(strict_types=1);

namespace App\Support;

use Filament\Support\Contracts\HasLabel;

/**
 * Forma del frasco de una herramienta en /servicios/herramientas. La geometría
 * de cada forma está en resources/js/tools-jars-3d.js (JAR_SHAPES).
 */
enum JarShape: string implements HasLabel
{
    case Classic = 'classic';
    case Tall = 'tall';
    case Squat = 'squat';
    case Round = 'round';
    case Hexagonal = 'hexagonal';

    public function getLabel(): string
    {
        return match ($this) {
            self::Classic => 'Clásico',
            self::Tall => 'Alto y angosto',
            self::Squat => 'Petiso y ancho',
            self::Round => 'Redondo (boticario)',
            self::Hexagonal => 'Hexagonal',
        };
    }

    /**
     * Forma por defecto para herramientas sin forma elegida, en orden.
     */
    public static function fallback(int $index): self
    {
        $cases = self::cases();

        return $cases[$index % count($cases)];
    }
}
