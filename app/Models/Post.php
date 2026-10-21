<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasEnglishTranslations;

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
        'featured_in_menu',
        'sort_order',
        'is_published',
        'published_at',
        'kicker_en',
        'title_en',
        'excerpt_en',
        'body_en',
        'caption_en',
        'alt_en',
    ];

    protected function casts(): array
    {
        return [
            'body_en' => 'array',
            'body' => 'array',
            'show_in_bento' => 'boolean',
            'featured_in_menu' => 'boolean',
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

    /**
     * Posts destacados en el menú del header, los más recientes primero.
     */
    public function scopeFeaturedInMenu(Builder $query): Builder
    {
        return $query->where('featured_in_menu', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
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
            'kicker' => $this->translated('kicker'),
            'title' => $this->translated('title'),
            'excerpt' => $this->translated('excerpt'),
            'body' => $this->translated('body') ?? [],
            'caption' => $this->translated('caption'),
            'alt' => $this->translated('alt'),
            'slug' => $this->slug,
            'image_path' => $this->image_path,
            'image_url' => $this->imageUrl(),
        ];
    }
}
