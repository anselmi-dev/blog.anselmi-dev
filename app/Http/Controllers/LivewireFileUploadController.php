<?php

namespace App\Http\Controllers;

use App\Support\Livewire\StoresTemporaryUploads;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\FileUploadController as BaseLivewireFileUploadController;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class LivewireFileUploadController extends BaseLivewireFileUploadController
{
    public function validateAndStore($files, $disk)
    {
        Validator::make(['files' => $files], [
            'files.*' => FileUploadConfiguration::rules(),
        ])->validate();

        $fileHashPaths = collect($files)->map(
            fn ($file) => StoresTemporaryUploads::store($file, $disk)
        );

        return $fileHashPaths->map(function ($path) {
            $stripped = str_replace(FileUploadConfiguration::path('/'), '', $path);

            return TemporaryUploadedFile::signPath($stripped);
        });
    }
}
