<?php

declare(strict_types=1);

namespace App\Actions\Media;

use Illuminate\Support\Facades\Storage;

class GenerateThumbnail
{
    /** Miniatura: grillas chicas y tarjetas. */
    public const int THUMB = 480;

    /** Versión mediana: fotos grandes en la galería, sin bajar el original. */
    public const int MEDIUM = 1600;

    private const int JPEG_QUALITY = 82;

    /**
     * Generates a resized JPEG copy of the given image (stored on the `public`
     * disk) whose longest side is at most $maxDimension, and returns its
     * relative path, or null if none was made (missing or unsupported file,
     * or with $onlyIfSmaller when the image is not bigger than that size).
     * Images already smaller than the maximum are never upscaled.
     */
    public function handle(string $imagePath, int $maxDimension = self::THUMB, string $suffix = 'thumb', bool $onlyIfSmaller = false): ?string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($imagePath)) {
            return null;
        }

        // Sin decodificar la imagen: si no es más grande que el tamaño pedido, no hace falta.
        $size = @getimagesize($disk->path($imagePath));

        if ($onlyIfSmaller && $size !== false && max($size[0], $size[1]) <= $maxDimension) {
            return null;
        }

        $source = $this->createImageResource($disk->path($imagePath));

        if ($source === null) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, $maxDimension / max($width, $height));

        $thumbWidth = max(1, (int) round($width * $scale));
        $thumbHeight = max(1, (int) round($height * $scale));

        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        imagedestroy($source);

        ob_start();
        imagejpeg($thumb, null, self::JPEG_QUALITY);
        $contents = ob_get_clean();
        imagedestroy($thumb);

        if (! is_string($contents) || $contents === '') {
            return null;
        }

        $thumbPath = $this->pathFor($imagePath, $suffix);
        $disk->put($thumbPath, $contents);

        return $thumbPath;
    }

    private function createImageResource(string $path): ?\GdImage
    {
        $info = @getimagesize($path);

        if ($info === false) {
            return null;
        }

        $resource = match ($info['mime']) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        return $resource instanceof \GdImage ? $resource : null;
    }

    private function pathFor(string $imagePath, string $suffix): string
    {
        $directory = pathinfo($imagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
        $prefix = $directory !== '.' ? "{$directory}/" : '';

        return "{$prefix}{$filename}-{$suffix}.jpg";
    }
}
