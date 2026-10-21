<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Modules\ImageMetadata\Exceptions\ImageMetadataException;
use App\Modules\ImageMetadata\ExtractImageMetadata;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.site')]
#[Title('Lector de metadatos EXIF')]
final class ImageMetadata extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $image = null;

    /**
     * @var array<string, array<string, string>>
     */
    public array $metadata = [];

    /**
     * @var array{name: string, size: string, mime: string}|array{}
     */
    public array $file = [];

    public ?string $analysisMessage = null;

    /**
     * @return array<string, list<string>>
     */
    protected function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                'extensions:jpg,jpeg,tif,tiff',
                'mimes:jpg,jpeg,tif,tiff',
                'mimetypes:image/jpeg,image/tiff,image/x-tiff',
                'max:51200',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'image.required' => 'Seleccioná una imagen para analizar.',
            'image.file' => 'El archivo seleccionado no es válido.',
            'image.extensions' => 'La extensión debe ser JPG, JPEG, TIF o TIFF.',
            'image.mimes' => 'Usá una imagen JPG, JPEG, TIF o TIFF.',
            'image.mimetypes' => 'El contenido del archivo no corresponde a una imagen JPEG o TIFF.',
            'image.max' => 'La imagen no puede superar los 50 MB.',
        ];
    }

    public function updatedImage(ExtractImageMetadata $extractImageMetadata): void
    {
        $this->resetAnalysisState();
        $this->validateOnly('image');

        if (! $this->image instanceof TemporaryUploadedFile) {
            return;
        }

        $this->file = [
            'name' => $this->image->getClientOriginalName(),
            'size' => $this->formatFileSize((int) $this->image->getSize()),
            'mime' => $this->image->getMimeType() ?: 'Desconocido',
        ];

        $path = $this->image->getRealPath();
        $path = $path !== false && $path !== '' ? $path : $this->image->getPathname();

        try {
            $this->metadata = $extractImageMetadata->handle($path);
        } catch (ImageMetadataException $exception) {
            $this->addError('image', $exception->getMessage());

            return;
        }

        $this->analysisMessage = $this->metadata === []
            ? 'La imagen es válida, pero no contiene metadatos EXIF legibles.'
            : 'Metadatos EXIF extraídos correctamente.';
    }

    public function removeImage(): void
    {
        $this->reset('image');
        $this->resetAnalysisState();
        $this->resetValidation('image');
    }

    public function render(): View
    {
        return view('livewire.pages.image-metadata');
    }

    private function resetAnalysisState(): void
    {
        $this->metadata = [];
        $this->file = [];
        $this->analysisMessage = null;
        $this->resetValidation('image');
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return "{$bytes} B";
        }

        if ($bytes < 1024 * 1024) {
            return number_format($bytes / 1024, 1, ',', '.').' KB';
        }

        return number_format($bytes / (1024 * 1024), 1, ',', '.').' MB';
    }
}
