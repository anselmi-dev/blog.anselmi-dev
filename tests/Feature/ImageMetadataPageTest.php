<?php

declare(strict_types=1);

use App\Livewire\Pages\ImageMetadata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('image metadata page is available from the services route', function () {
    $this->get(route('services.image_metadata'))
        ->assertSuccessful()
        ->assertSee('Lector de metadatos EXIF')
        ->assertSee('Arrastrá tu imagen hasta acá');
});

test('image without exif data shows a clear result', function () {
    Livewire::test(ImageMetadata::class)
        ->set('image', UploadedFile::fake()->image('photo.jpg', 1200, 800))
        ->assertHasNoErrors()
        ->assertSet('metadata', [])
        ->assertSet('analysisMessage', 'La imagen es válida, pero no contiene metadatos EXIF legibles.')
        ->assertSee('photo.jpg');
});

test('unsupported image format is rejected', function () {
    Livewire::test(ImageMetadata::class)
        ->set('image', UploadedFile::fake()->image('photo.png'))
        ->assertHasErrors(['image']);
});

test('oversized image is rejected', function () {
    Livewire::test(ImageMetadata::class)
        ->set('image', UploadedFile::fake()->image('photo.jpg')->size(51201))
        ->assertHasErrors(['image' => 'max']);
});
