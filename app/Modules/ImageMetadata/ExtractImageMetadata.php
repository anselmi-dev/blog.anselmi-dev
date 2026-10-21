<?php

declare(strict_types=1);

namespace App\Modules\ImageMetadata;

use App\Modules\ImageMetadata\Exceptions\ImageMetadataException;
use DateTimeImmutable;
use ErrorException;

final class ExtractImageMetadata
{
    /**
     * @return array<string, array<string, string>>
     */
    public function handle(string $path): array
    {
        if (! function_exists('exif_read_data')) {
            throw new ImageMetadataException('La extensión EXIF de PHP no está disponible.');
        }

        if (! is_readable($path)) {
            throw new ImageMetadataException('No se pudo leer el archivo temporal.');
        }

        $metadata = $this->readExifData($path);

        if (! $this->hasExifMetadata($metadata)) {
            return [];
        }

        return array_filter([
            'camera' => $this->cameraData($metadata),
            'capture' => $this->captureData($metadata),
            'image' => $this->imageData($metadata),
            'author' => $this->authorData($metadata),
            'location' => $this->locationData($metadata),
        ], static fn (array $section): bool => $section !== []);
    }

    /**
     * @return array<string, mixed>
     */
    private function readExifData(string $path): array
    {
        set_error_handler(static function (
            int $severity,
            string $message,
            string $file,
            int $line,
        ): never {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            $metadata = exif_read_data($path, null, true, false);
        } catch (ErrorException) {
            return [];
        } finally {
            restore_error_handler();
        }

        return is_array($metadata) ? $metadata : [];
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function hasExifMetadata(array $metadata): bool
    {
        foreach (['IFD0', 'EXIF', 'GPS'] as $section) {
            if (! empty($metadata[$section]) && is_array($metadata[$section])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, string>
     */
    private function cameraData(array $metadata): array
    {
        $make = $this->stringValue($this->tag($metadata, 'Make'));
        $model = $this->stringValue($this->tag($metadata, 'Model'));

        return $this->withoutEmptyValues([
            'make' => $make,
            'model' => $model,
            'lens' => $this->stringValue($this->tag($metadata, 'LensModel')),
            'camera_serial' => $this->stringValue($this->tag($metadata, 'BodySerialNumber')),
            'lens_serial' => $this->stringValue($this->tag($metadata, 'LensSerialNumber')),
        ]);
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, string>
     */
    private function captureData(array $metadata): array
    {
        return $this->withoutEmptyValues([
            'captured_at' => $this->capturedAt($metadata),
            'shutter_speed' => $this->shutterSpeed($metadata),
            'aperture' => $this->aperture($metadata),
            'iso' => $this->stringValue($this->tag($metadata, 'ISOSpeedRatings')),
            'focal_length' => $this->measurement($this->tag($metadata, 'FocalLength'), 'mm'),
            'focal_length_35mm' => $this->measurement($this->tag($metadata, 'FocalLengthIn35mmFilm'), 'mm'),
            'exposure_program' => $this->mappedValue(
                $this->tag($metadata, 'ExposureProgram'),
                [
                    0 => 'No definido',
                    1 => 'Manual',
                    2 => 'Programa normal',
                    3 => 'Prioridad de apertura',
                    4 => 'Prioridad de obturación',
                    5 => 'Programa creativo',
                    6 => 'Programa de acción',
                    7 => 'Retrato',
                    8 => 'Paisaje',
                ],
            ),
            'exposure_bias' => $this->signedMeasurement($this->tag($metadata, 'ExposureBiasValue'), ' EV'),
            'metering_mode' => $this->mappedValue(
                $this->tag($metadata, 'MeteringMode'),
                [
                    0 => 'Desconocido',
                    1 => 'Promedio',
                    2 => 'Promedio ponderado al centro',
                    3 => 'Puntual',
                    4 => 'Multipunto',
                    5 => 'Patrón',
                    6 => 'Parcial',
                    255 => 'Otro',
                ],
            ),
            'white_balance' => $this->mappedValue(
                $this->tag($metadata, 'WhiteBalance'),
                [0 => 'Automático', 1 => 'Manual'],
            ),
            'flash' => $this->flash($metadata),
        ]);
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, string>
     */
    private function imageData(array $metadata): array
    {
        return $this->withoutEmptyValues([
            'width' => $this->stringValue(
                $this->tag($metadata, 'ExifImageWidth')
                    ?? $this->sectionValue($metadata, 'COMPUTED', 'Width'),
            ),
            'height' => $this->stringValue(
                $this->tag($metadata, 'ExifImageLength')
                    ?? $this->sectionValue($metadata, 'COMPUTED', 'Height'),
            ),
            'orientation' => $this->mappedValue(
                $this->tag($metadata, 'Orientation'),
                [
                    1 => 'Horizontal',
                    2 => 'Horizontal reflejada',
                    3 => 'Rotada 180°',
                    4 => 'Vertical reflejada',
                    5 => 'Reflejada y rotada 90°',
                    6 => 'Rotada 90°',
                    7 => 'Reflejada y rotada 270°',
                    8 => 'Rotada 270°',
                ],
            ),
            'color_space' => $this->mappedValue(
                $this->tag($metadata, 'ColorSpace'),
                [1 => 'sRGB', 65535 => 'Sin calibrar'],
            ),
            'software' => $this->stringValue($this->tag($metadata, 'Software')),
            'resolution' => $this->resolution($metadata),
        ]);
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, string>
     */
    private function authorData(array $metadata): array
    {
        return $this->withoutEmptyValues([
            'artist' => $this->stringValue($this->tag($metadata, 'Artist')),
            'copyright' => $this->stringValue($this->tag($metadata, 'Copyright')),
        ]);
    }

    /**
     * @param  array<string, mixed>  $metadata
     * @return array<string, string>
     */
    private function locationData(array $metadata): array
    {
        $latitude = $this->coordinate(
            $this->sectionValue($metadata, 'GPS', 'GPSLatitude'),
            $this->sectionValue($metadata, 'GPS', 'GPSLatitudeRef'),
        );
        $longitude = $this->coordinate(
            $this->sectionValue($metadata, 'GPS', 'GPSLongitude'),
            $this->sectionValue($metadata, 'GPS', 'GPSLongitudeRef'),
        );

        return $this->withoutEmptyValues([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'altitude' => $this->altitude($metadata),
        ]);
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function capturedAt(array $metadata): ?string
    {
        $value = $this->stringValue(
            $this->tag($metadata, 'DateTimeOriginal')
                ?? $this->tag($metadata, 'DateTimeDigitized')
                ?? $this->tag($metadata, 'DateTime'),
        );

        if ($value === null) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Y:m:d H:i:s', $value);

        return $date instanceof DateTimeImmutable
            ? $date->format('d/m/Y H:i:s')
            : $value;
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function shutterSpeed(array $metadata): ?string
    {
        $value = $this->firstValue($this->tag($metadata, 'ExposureTime'));

        if ($value === null) {
            return null;
        }

        $rawValue = trim((string) $value);

        return $rawValue === '' ? null : "{$rawValue} s";
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function aperture(array $metadata): ?string
    {
        $computed = $this->stringValue($this->sectionValue($metadata, 'COMPUTED', 'ApertureFNumber'));

        if ($computed !== null) {
            return str_starts_with(mb_strtolower($computed), 'f/')
                ? $computed
                : "f/{$computed}";
        }

        $value = $this->rationalToFloat($this->tag($metadata, 'FNumber'));

        return $value === null ? null : 'f/'.$this->formatDecimal($value);
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function flash(array $metadata): ?string
    {
        $value = $this->integerValue($this->tag($metadata, 'Flash'));

        if ($value === null) {
            return null;
        }

        return ($value & 1) === 1 ? 'Disparado' : 'No disparado';
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function resolution(array $metadata): ?string
    {
        $horizontal = $this->rationalToFloat($this->tag($metadata, 'XResolution'));
        $vertical = $this->rationalToFloat($this->tag($metadata, 'YResolution'));

        if ($horizontal === null || $vertical === null) {
            return null;
        }

        $unit = match ($this->integerValue($this->tag($metadata, 'ResolutionUnit'))) {
            2 => 'ppp',
            3 => 'ppc',
            default => 'px',
        };

        return $this->formatDecimal($horizontal).' × '.$this->formatDecimal($vertical)." {$unit}";
    }

    private function measurement(mixed $value, string $unit): ?string
    {
        $number = $this->rationalToFloat($value);

        return $number === null ? null : $this->formatDecimal($number).$unit;
    }

    private function signedMeasurement(mixed $value, string $unit): ?string
    {
        $number = $this->rationalToFloat($value);

        if ($number === null) {
            return null;
        }

        $prefix = $number > 0 ? '+' : '';

        return $prefix.$this->formatDecimal($number).$unit;
    }

    /**
     * @param  array<int, string>  $values
     */
    private function mappedValue(mixed $value, array $values): ?string
    {
        $integer = $this->integerValue($value);

        if ($integer === null) {
            return null;
        }

        return $values[$integer] ?? (string) $integer;
    }

    private function coordinate(mixed $coordinate, mixed $reference): ?string
    {
        if (! is_array($coordinate) || count($coordinate) < 3) {
            return null;
        }

        $degrees = $this->rationalToFloat($coordinate[0] ?? null);
        $minutes = $this->rationalToFloat($coordinate[1] ?? null);
        $seconds = $this->rationalToFloat($coordinate[2] ?? null);

        if ($degrees === null || $minutes === null || $seconds === null) {
            return null;
        }

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        $direction = mb_strtoupper((string) $this->firstValue($reference));

        if (in_array($direction, ['S', 'W'], true)) {
            $decimal *= -1;
        }

        return number_format($decimal, 6, '.', '');
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function altitude(array $metadata): ?string
    {
        $altitude = $this->rationalToFloat($this->sectionValue($metadata, 'GPS', 'GPSAltitude'));

        if ($altitude === null) {
            return null;
        }

        $reference = $this->integerValue($this->sectionValue($metadata, 'GPS', 'GPSAltitudeRef'));

        if ($reference === 1) {
            $altitude *= -1;
        }

        return $this->formatDecimal($altitude).' m';
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function tag(array $metadata, string $key): mixed
    {
        foreach (['EXIF', 'IFD0', 'COMPUTED', 'GPS', 'FILE'] as $section) {
            $value = $this->sectionValue($metadata, $section, $key);

            if ($value !== null) {
                return $value;
            }
        }

        return $metadata[$key] ?? null;
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function sectionValue(array $metadata, string $section, string $key): mixed
    {
        $sectionData = $metadata[$section] ?? null;

        return is_array($sectionData) ? ($sectionData[$key] ?? null) : null;
    }

    private function stringValue(mixed $value): ?string
    {
        $value = $this->firstValue($value);

        if (! is_scalar($value)) {
            return null;
        }

        $string = trim((string) $value);

        if ($string !== '' && ! mb_check_encoding($string, 'UTF-8')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
        }

        return $string === '' ? null : $string;
    }

    private function integerValue(mixed $value): ?int
    {
        $value = $this->firstValue($value);

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value) && mb_strlen($value, '8bit') === 1 && ord($value) <= 1) {
            return ord($value);
        }

        return is_numeric($value) ? (int) $value : null;
    }

    private function rationalToFloat(mixed $value): ?float
    {
        $value = $this->firstValue($value);

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        if (! str_contains($value, '/')) {
            return is_numeric($value) ? (float) $value : null;
        }

        [$numerator, $denominator] = array_map('floatval', explode('/', $value, 2));

        return $denominator === 0.0 ? null : $numerator / $denominator;
    }

    private function firstValue(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        return reset($value);
    }

    private function formatDecimal(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    /**
     * @param  array<string, string|null>  $values
     * @return array<string, string>
     */
    private function withoutEmptyValues(array $values): array
    {
        return array_filter(
            $values,
            static fn (?string $value): bool => $value !== null && $value !== '',
        );
    }
}
