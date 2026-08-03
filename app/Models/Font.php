<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'family',
        'tailwind',
        'category',
        'weights',
        'sample',
        'google_url',
        'bunny_url',
        'css',
        'note',
        'sort_order',
        'is_published',
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
            'family' => $this->family,
            'tailwind' => $this->tailwind,
            'category' => $this->category,
            'weights' => $this->weights,
            'sample' => $this->sample,
            'google' => $this->google_url,
            'bunny' => $this->bunny_url,
            'css' => $this->css,
            'note' => $this->note,
        ];
    }
}
