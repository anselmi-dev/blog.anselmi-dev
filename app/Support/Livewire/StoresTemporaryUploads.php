<?php

namespace App\Support\Livewire;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StoresTemporaryUploads
{
    /**
     * Store a Livewire temporary upload, with a Windows/Laragon-safe fallback.
     *
     * On some Windows setups, uploaded temps exist at getPathname() while
     * getRealPath() returns false. Laravel's storeAs/putFileAs then fails with
     * "Path must not be empty".
     */
    public static function store(UploadedFile $file, string $disk): string
    {
        $filename = TemporaryUploadedFile::generateHashName($file);
        $metaFilename = $filename.'.json';

        Storage::disk($disk)->put('/'.FileUploadConfiguration::path($metaFilename), json_encode([
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'hash' => $file->hashName(),
        ]));

        $realPath = $file->getRealPath();
        if ($realPath !== false && $realPath !== '') {
            return $file->storeAs('/'.FileUploadConfiguration::path(), $filename, [
                'disk' => $disk,
            ]);
        }

        $target = trim('/'.FileUploadConfiguration::path().'/'.$filename, '/');
        $stream = fopen($file->getPathname(), 'r');
        Storage::disk($disk)->put($target, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $target;
    }
}
