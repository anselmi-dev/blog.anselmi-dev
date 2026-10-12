<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasEnglishTranslations;

    protected $fillable = [
        'name',
        'tagline',
        'url',
        'category',
        'icon',
        'sort_order',
        'is_published',
        'tagline_en',
        'category_en',
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

    /**
     * @return array<string, mixed>
     */
    public function toViewArray(): array
    {
        return [
            'name' => $this->name,
            'tagline' => $this->translated('tagline'),
            'url' => $this->url,
            'category' => $this->translated('category'),
            'icon' => $this->icon,
        ];
    }
}
