<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Galleryable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

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

    /**
     * Publicados y con su contenido todavía existente: una entrada cuya foto,
     * video o cita se borró no se muestra (y no rompe la vista).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereHasMorph('galleryable', '*')
            ->orderBy('sort_order');
    }

    /**
     * Carga el contenido de cada entrada (y las etiquetas de las fotos) en
     * pocas consultas.
     */
    public function scopeWithContent(Builder $query): Builder
    {
        return $query->with(['galleryable' => fn (MorphTo $morphTo) => $morphTo->morphWith([Media::class => ['tags']])]);
    }

    /**
     * Miniatura y tipo para el panel: la foto, la portada del video o, si no
     * hay imagen, solo el tipo (para mostrar un ícono).
     *
     * @return array{kind: string, image: string|null, label: string}
     */
    public function adminPreview(): array
    {
        return self::previewOf($this->galleryable);
    }

    /**
     * @return array{kind: string, image: string|null, label: string}
     */
    public static function previewOf(?Model $item): array
    {
        return match (true) {
            $item instanceof Media => ['kind' => 'photo', 'image' => $item->thumbUrl(), 'label' => $item->title ?: "Foto #{$item->id}"],
            $item instanceof Video => ['kind' => 'video', 'image' => $item->posterUrl(), 'label' => $item->title ?: "Video #{$item->id}"],
            $item instanceof Quote => ['kind' => 'quote', 'image' => null, 'label' => Str::limit($item->quote, 80)],
            default => ['kind' => 'missing', 'image' => null, 'label' => 'Contenido borrado'],
        };
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
