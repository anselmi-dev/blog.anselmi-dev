<?php

declare(strict_types=1);

use App\Http\Controllers\LivewireFileUploadController;
use Livewire\Features\SupportFileUploads\FileUploadController as LivewireUploadController;

test('models and http classes declare strict types', function () {
    $paths = [
        ...glob(base_path('app/Models/*.php')) ?: [],
        ...glob(base_path('app/Models/Concerns/*.php')) ?: [],
        ...glob(base_path('app/Http/Controllers/*.php')) ?: [],
        ...glob(base_path('app/Http/Middleware/*.php')) ?: [],
    ];

    expect($paths)->not->toBeEmpty();

    foreach ($paths as $path) {
        expect(file_get_contents($path))
            ->toContain('declare(strict_types=1);');
    }
});

test('livewire file upload controller binding stays windows-safe override', function () {
    $controller = app(LivewireUploadController::class);

    expect($controller)->toBeInstanceOf(LivewireFileUploadController::class)
        ->and(file_get_contents((new ReflectionClass($controller))->getFileName() ?: ''))
        ->toContain('declare(strict_types=1);')
        ->and(file_get_contents((new ReflectionClass($controller))->getFileName() ?: ''))
        ->toContain('StoresTemporaryUploads::store');
});
