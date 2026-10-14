<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasEnglishTranslations;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use HasEnglishTranslations;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('sort_order');
    }

    public function publishedItems(): HasMany
    {
        return $this->items()->published();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }

    /**
     * Última vez que cambió la colección o alguno de sus ítems publicados.
     */
    public function lastUpdatedAt(): ?CarbonInterface
    {
        $itemsUpdatedAt = $this->publishedItems->max('updated_at');

        return collect([$this->updated_at, $itemsUpdatedAt])->filter()->max();
    }

    /**
     * Tarjeta del índice de colecciones: hasta 3 ítems para el abanico
     * (el primero va adelante) y cuántos hay de cada tipo.
     * Requiere publishedItems.galleryable cargado.
     *
     * @return array{
     *     name: string,
     *     slug: string,
     *     description: string|null,
     *     total: int,
     *     counts: array<string, int>,
     *     preview: list<array<string, mixed>>,
     *     updated_at: CarbonInterface|null
     * }
     */
    public function toEntryArray(): array
    {
        $items = $this->publishedItems->filter(fn (Gallery $item): bool => $item->galleryable !== null)->values();

        return [
            'name' => (string) $this->translated('name'),
            'slug' => $this->slug,
            'description' => $this->translated('description'),
            'total' => $items->count(),
            'counts' => $items->countBy(fn (Gallery $item): string => $item->galleryable->galleryKind())->all(),
            'preview' => $items->take(3)->map(fn (Gallery $item): array => $item->toViewArray())->all(),
            'updated_at' => $this->lastUpdatedAt(),
        ];
    }
}
