<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Galleryable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Gallery extends Model
{
    protected $fillable = [
        'collection_id',
        'galleryable_type',
        'galleryable_id',
        'span',
        'featured',
        'play',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'play' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function galleryable(): MorphTo
    {
        return $this->morphTo();
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
        $galleryable = $this->galleryable;

        $content = $galleryable instanceof Galleryable ? $galleryable->toGalleryArray() : [];
        $kind = $galleryable instanceof Galleryable ? $galleryable->galleryKind() : null;

        return [
            'kind' => $kind,
            'span' => $this->span,
            'featured' => $this->featured,
            'play' => $this->play,
            ...$content,
        ];
    }
}
