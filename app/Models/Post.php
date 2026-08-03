<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'kind',
        'kicker',
        'title',
        'excerpt',
        'body',
        'caption',
        'image_path',
        'alt',
        'show_in_bento',
        'bento_type',
        'bento_grid_class',
        'bento_sort',
        'sort_order',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'body' => 'array',
            'show_in_bento' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'bento_sort' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeInBento(Builder $query): Builder
    {
        return $query->where('show_in_bento', true)->orderBy('bento_sort');
    }

    public function imageUrl(?int $w = 900, ?int $h = 1100): ?string
    {
        if ($this->image_path) {
            return Storage::disk('public')->url($this->image_path);
        }

        if ($this->kind === 'image') {
            return sprintf(
                'https://picsum.photos/seed/post-%s/%d/%d',
                $this->slug ?: ($this->id ?: 'blog'),
                $w ?? 900,
                $h ?? 1100
            );
        }

        return null;
    }

    /**
     * Array shape compatible with the previous config/blog entries.
     *
     * @return array<string, mixed>
     */
    public function toEntryArray(): array
    {
        return [
            'kind' => $this->kind,
            'kicker' => $this->kicker,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body ?? [],
            'caption' => $this->caption,
            'alt' => $this->alt,
            'slug' => $this->slug,
            'image_path' => $this->image_path,
            'image_url' => $this->imageUrl(),
        ];
    }
}
