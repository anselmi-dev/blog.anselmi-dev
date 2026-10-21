<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasEnglishTranslations, HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
    ];

    protected static function booted(): void
    {
        static::saving(function (Tag $tag): void {
            if (blank($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)->withPivot('sort_order');
    }
}
