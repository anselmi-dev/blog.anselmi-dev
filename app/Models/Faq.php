<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasEnglishTranslations;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_published',
        'question_en',
        'answer_en',
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
}
