<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReadingBook extends Model
{
    use HasEnglishTranslations;

    protected $fillable = [
        'title',
        'author',
        'image_path',
        'sort_order',
        'is_published',
        'title_en',
        'author_en',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    /**
     * @return array{title: string, author: string, image: string}
     */
    public function toViewArray(): array
    {
        return [
            'title' => $this->translated('title'),
            'author' => $this->translated('author'),
            'image' => $this->imageUrl() ?? '',
        ];
    }
}
