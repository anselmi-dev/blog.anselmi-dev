<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Livewire\StoresTemporaryUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\FileUploadController as BaseLivewireFileUploadController;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Override;

class LivewireFileUploadController extends BaseLivewireFileUploadController
{
    /**
     * @param  array<int, UploadedFile>  $files
     * @param  string  $disk
     * @return Collection<int, string>
     */
    #[Override]
    public function validateAndStore($files, $disk)
    {
        /** @var array<int, UploadedFile> $files */
        $files = is_array($files) ? $files : [];
        $diskName = (string) $disk;

        Validator::make(['files' => $files], [
            'files.*' => FileUploadConfiguration::rules(),
        ])->validate();

        $fileHashPaths = collect($files)->map(
            fn (UploadedFile $file): string => StoresTemporaryUploads::store($file, $diskName)
        );

        return $fileHashPaths->map(function (string $path): string {
            $stripped = str_replace(FileUploadConfiguration::path('/'), '', $path);

            return TemporaryUploadedFile::signPath($stripped);
        });
    }
}
