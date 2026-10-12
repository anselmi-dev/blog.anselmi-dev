<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Media\GenerateThumbnail;
use App\Contracts\Galleryable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model implements Galleryable
{
    protected $table = 'media';

    protected $fillable = [
        'image_path',
        'thumb_path',
        'width',
        'height',
        'title',
        'category',
        'location',
        'released_at',
        'description',
        'tags',
        'iso',
        'aperture',
        'shutter',
        'focal_length',
        'camera',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Media $media): void {
            if ($media->isDirty('image_path')) {
                $media->thumb_path = $media->image_path
                    ? app(GenerateThumbnail::class)->handle($media->image_path)
                    : null;
            }
        });
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
            'title' => $this->title,
            'category' => $this->category,
            'image_url' => $this->imageUrl($this->width, $this->height),
            'thumb_url' => $this->thumbUrl(),
            'w' => $this->width ?? 800,
            'h' => $this->height ?? 1200,
            'released_at' => $this->released_at,
            'location' => $this->location,
            'description' => $this->description,
            'iso' => $this->iso,
            'aperture' => $this->aperture,
            'shutter' => $this->shutter,
            'focal_length' => $this->focal_length,
            'camera' => $this->camera,
            'tags' => $this->tags ?? [],
        ];
    }
}
