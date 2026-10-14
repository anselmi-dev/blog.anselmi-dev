<?php

declare(strict_types=1);

namespace App\Actions\Media;

use Illuminate\Support\Facades\Storage;

class GenerateThumbnail
{
    private const int MAX_DIMENSION = 480;

    private const int JPEG_QUALITY = 80;

    /**
     * Generates a resized JPEG copy of the given image (stored on the `public`
     * disk) and returns its relative path, or null if no thumbnail was made
     * (missing or unsupported file). Images already smaller than the maximum
     * keep their size: they are only re-encoded, never upscaled.
     */
    public function handle(string $imagePath): ?string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($imagePath)) {
            return null;
        }

        $source = $this->createImageResource($disk->path($imagePath));

        if ($source === null) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, self::MAX_DIMENSION / max($width, $height));

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

        $thumbPath = $this->thumbPathFor($imagePath);
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

    private function thumbPathFor(string $imagePath): string
    {
        $directory = pathinfo($imagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
        $prefix = $directory !== '.' ? "{$directory}/" : '';

        return "{$prefix}{$filename}-thumb.jpg";
    }
}
