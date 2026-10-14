<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Media\GenerateThumbnail;
use App\Contracts\Galleryable;
use App\Models\Concerns\HasEnglishTranslations;
use App\Models\Concerns\RemovesGalleryEntries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Media extends Model implements Galleryable
{
    use HasEnglishTranslations, RemovesGalleryEntries;

    protected $table = 'media';

    protected $fillable = [
        'image_path',
        'thumb_path',
        'width',
        'height',
        'title',
        'category',
        'location',
        'released_on',
        'description',
        'iso',
        'aperture',
        'shutter',
        'focal_length',
        'camera',
        'title_en',
        'category_en',
        'location_en',
        'description_en',
    ];

    protected function casts(): array
    {
        return [
            'released_on' => 'date',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Media $media): void {
            if (blank($media->image_path)) {
                $media->thumb_path = null;

                return;
            }

            // Se genera al cambiar la imagen y también si la miniatura falta
            // (no se creó en su momento o se borró el archivo).
            if ($media->isDirty('image_path') || ! $media->hasThumbnailFile()) {
                $media->thumb_path = app(GenerateThumbnail::class)->handle($media->image_path);
            }

            if ($media->isDirty('image_path') && (blank($media->width) || blank($media->height))) {
                $media->fillDimensionsFromImage();
            }
        });
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withPivot('sort_order');
    }

    /**
     * Completa ancho y alto con los de la imagen subida (la grilla de la
     * galería los usa para el tamaño de cada foto).
     */
    public function fillDimensionsFromImage(): void
    {
        $disk = Storage::disk('public');

        if (blank($this->image_path) || ! $disk->exists($this->image_path)) {
            return;
        }

        $size = @getimagesize($disk->path($this->image_path));

        if ($size !== false) {
            [$this->width, $this->height] = $size;
        }
    }

    /**
     * Fecha corta en el idioma actual, como "12 mar 2025" o "12 Mar 2025".
     */
    public function releasedOnLabel(): ?string
    {
        if ($this->released_on === null) {
            return null;
        }

        $date = $this->released_on->locale(app()->getLocale());

        return $date->format('j').' '.rtrim($date->translatedFormat('M'), '.').' '.$date->format('Y');
    }

    public function hasThumbnailFile(): bool
    {
        return filled($this->thumb_path) && Storage::disk('public')->exists($this->thumb_path);
    }

    public function imageUrl(?int $w = null, ?int $h = null): string
    {
        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }

        return sprintf(
            'https://picsum.photos/seed/gallery-%d/%d/%d',
            $this->id ?: 1,
            $w ?? $this->width ?? 800,
            $h ?? $this->height ?? 1200
        );
    }

    public function thumbUrl(): string
    {
        if ($this->thumb_path) {
            return Storage::disk('public')->url($this->thumb_path);
        }

        return $this->imageUrl();
    }

    public function galleryKind(): string
    {
        return 'photo';
    }

    /**
     * @return array<string, mixed>
     */
    public function toGalleryArray(): array
    {
        return [
            'title' => $this->translated('title'),
            'category' => $this->translated('category'),
            'image_url' => $this->imageUrl($this->width, $this->height),
            'thumb_url' => $this->thumbUrl(),
            'w' => $this->width ?? 800,
            'h' => $this->height ?? 1200,
            'released_at' => $this->releasedOnLabel(),
            'location' => $this->translated('location'),
            'description' => $this->translated('description'),
            'iso' => $this->iso,
            'aperture' => $this->aperture,
            'shutter' => $this->shutter,
            'focal_length' => $this->focal_length,
            'camera' => $this->camera,
            'tags' => $this->tags
                ->sortBy(fn (Tag $tag): int => (int) $tag->pivot?->sort_order)
                ->map(fn (Tag $tag): string => (string) $tag->translated('name'))
                ->values()
                ->all(),
        ];
    }
}
