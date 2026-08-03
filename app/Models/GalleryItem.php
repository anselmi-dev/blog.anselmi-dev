<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        'kind',
        'span',
        'title',
        'category',
        'image_path',
        'width',
        'height',
        'featured',
        'play',
        'released_at',
        'location',
        'description',
        'iso',
        'aperture',
        'shutter',
        'focal_length',
        'camera',
        'tags',
        'quote',
        'attribution',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'play' => 'boolean',
            'is_published' => 'boolean',
            'tags' => 'array',
            'width' => 'integer',
            'height' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order');
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

    /**
     * @return array<string, mixed>
     */
    public function toViewArray(): array
    {
        return [
            'kind' => $this->kind,
            'span' => $this->span,
            'title' => $this->title,
            'category' => $this->category,
            'image_path' => $this->image_path,
            'image_url' => $this->imageUrl($this->width, $this->height),
            'w' => $this->width ?? 800,
            'h' => $this->height ?? 1200,
            'featured' => $this->featured,
            'play' => $this->play,
            'released_at' => $this->released_at,
            'location' => $this->location,
            'description' => $this->description,
            'iso' => $this->iso,
            'aperture' => $this->aperture,
            'shutter' => $this->shutter,
            'focal_length' => $this->focal_length,
            'camera' => $this->camera,
            'tags' => $this->tags ?? [],
            'quote' => $this->quote,
            'attribution' => $this->attribution,
        ];
    }
}
