<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Convierte la imagen de un formulario (ya guardada en el disco public o
 * recién subida) en un bloque de imagen para Claude, achicada para que
 * cueste pocos tokens.
 */
final class ImageForClaude
{
    private const int MAX_DIMENSION = 1024;

    /**
     * @param  mixed  $state  Estado de un FileUpload: ruta, archivo temporal o lista de ellos.
     * @return array{type: 'image', source: array{type: 'base64', mediaType: 'image/jpeg', data: string}}|null
     */
    public static function fromState(mixed $state): ?array
    {
        $path = self::localPath($state);

        if ($path === null) {
            return null;
        }

        $info = @getimagesize($path);

        if ($info === false) {
            return null;
        }

        $source = match ($info['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            'image/gif' => @imagecreatefromgif($path),
            default => false,
        };

        if (! $source instanceof \GdImage) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, self::MAX_DIMENSION / max($width, $height));
        $resized = imagecreatetruecolor(max(1, (int) round($width * $scale)), max(1, (int) round($height * $scale)));
        imagecopyresampled($resized, $source, 0, 0, 0, 0, imagesx($resized), imagesy($resized), $width, $height);

        ob_start();
        imagejpeg($resized, null, 80);
        $jpeg = (string) ob_get_clean();

        return [
            'type' => 'image',
            'source' => ['type' => 'base64', 'mediaType' => 'image/jpeg', 'data' => base64_encode($jpeg)],
        ];
    }

    private static function localPath(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = reset($state) ?: null;
        }

        if ($state instanceof TemporaryUploadedFile) {
            return $state->getRealPath() ?: null;
        }

        if (is_string($state) && $state !== '' && Storage::disk('public')->exists($state)) {
            return Storage::disk('public')->path($state);
        }

        return null;
    }
}
