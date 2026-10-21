<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Galleryable;
use App\Models\Concerns\HasEnglishTranslations;
use App\Models\Concerns\RemovesGalleryEntries;
use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model implements Galleryable
{
    /** @use HasFactory<VideoFactory> */
    use HasEnglishTranslations, HasFactory, RemovesGalleryEntries;

    protected $fillable = [
        'video_path',
        'poster_path',
        'width',
        'height',
        'title',
        'title_en',
        'category',
        'category_en',
        'location',
        'location_en',
        'released_at',
        'description',
        'description_en',
    ];

    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function videoUrl(): ?string
    {
        return $this->video_path ? Storage::disk('public')->url($this->video_path) : null;
    }

    public function posterUrl(): ?string
    {
        return $this->poster_path ? Storage::disk('public')->url($this->poster_path) : null;
    }

    public function galleryKind(): string
    {
        return 'video';
    }

    /**
     * @return array<string, mixed>
     */
    public function toGalleryArray(): array
    {
        return [
            'title' => $this->translated('title'),
            'category' => $this->translated('category'),
            'video_url' => $this->videoUrl(),
            'poster_url' => $this->posterUrl(),
            'w' => $this->width ?? 1920,
            'h' => $this->height ?? 1080,
            'released_at' => $this->released_at,
            'location' => $this->translated('location'),
            'description' => $this->translated('description'),
        ];
    }
}
