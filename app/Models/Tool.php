<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use App\Support\JarPalette;
use App\Support\JarShape;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasEnglishTranslations;

    protected $fillable = [
        'name',
        'tagline',
        'description',
        'url',
        'category',
        'icon',
        'label_color',
        'fill_color',
        'jar_shape',
        'sort_order',
        'is_published',
        'tagline_en',
        'category_en',
        'description_en',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'jar_shape' => JarShape::class,
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
            'description' => $this->translated('description'),
            'url' => $this->url,
            'category' => $this->translated('category'),
            'icon' => $this->icon,
            'palette' => JarPalette::fromColors($this->label_color, $this->fill_color),
            'shape' => $this->jar_shape?->value,
        ];
    }
}
