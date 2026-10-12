<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\Localization;

/**
 * Contenido traducible con columnas "{campo}_en". En las páginas en inglés se
 * usa la columna en inglés si tiene valor; si no, el texto en español.
 */
trait HasEnglishTranslations
{
    public function translated(string $attribute): mixed
    {
        $value = $this->getAttribute($attribute);

        if (! Localization::isEnglish()) {
            return $value;
        }

        $englishValue = $this->getAttribute($attribute.'_en');

        return filled($englishValue) ? $englishValue : $value;
    }
}
