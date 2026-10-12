<?php

declare(strict_types=1);

namespace App\Actions\Media;

class ExtractExifData
{
    /**
     * @return array<string, string|int>
     */
    public function handle(string $path): array
    {
        $exif = @exif_read_data($path);

        if ($exif === false) {
            return [];
        }

        $computed = $exif['COMPUTED'] ?? [];

        return array_filter([
            'camera' => $this->camera($exif),
            'iso' => $this->firstValue($this->tag($exif, 'ISOSpeedRatings')),
            'aperture' => $this->aperture($computed),
            'shutter' => $this->shutter($exif),
            'focal_length' => $this->focalLength($exif),
            'width' => $computed['Width'] ?? $this->tag($exif, 'ExifImageWidth'),
            'height' => $computed['Height'] ?? $this->tag($exif, 'ExifImageLength'),
        ], fn ($value) => $value !== null && $value !== '');
    }

    private function tag(array $exif, string $key): mixed
    {
        foreach ([$exif, $exif['IFD0'] ?? [], $exif['EXIF'] ?? []] as $section) {
            if (isset($section[$key])) {
                return $section[$key];
            }
        }

        return null;
    }

    private function firstValue(mixed $value): mixed
    {
        return is_array($value) ? reset($value) : $value;
    }

    private function camera(array $exif): ?string
    {
        $make = trim((string) $this->tag($exif, 'Make'));
        $model = trim((string) $this->tag($exif, 'Model'));

        if ($make !== '' && $model !== '' && ! str_starts_with($model, $make)) {
            return "{$make} {$model}";
        }

        return $model !== '' ? $model : ($make !== '' ? $make : null);
    }

    private function aperture(array $computed): ?string
    {
        $value = $computed['ApertureFNumber'] ?? null;

        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return str_starts_with($value, 'f/') ? $value : "f/{$value}";
    }

    private function shutter(array $exif): ?string
    {
        $value = $this->firstValue($this->tag($exif, 'ExposureTime'));

        if ($value === null) {
            return null;
        }

        return trim((string) $value).'s';
    }

    private function focalLength(array $exif): ?string
    {
        $value = $this->firstValue($this->tag($exif, 'FocalLength'));

        if ($value === null) {
            return null;
        }

        $value = (string) $value;

        if (str_contains($value, '/')) {
            [$numerator, $denominator] = array_map('floatval', explode('/', $value, 2));
            $value = $denominator > 0 ? $numerator / $denominator : $numerator;
        }

        $formatted = rtrim(rtrim(number_format((float) $value, 1), '0'), '.');

        return "{$formatted}mm";
    }
}
