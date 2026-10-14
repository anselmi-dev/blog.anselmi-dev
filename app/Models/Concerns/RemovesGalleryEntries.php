<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Para los modelos que se agregan a las colecciones (fotos, videos, citas):
 * al borrarlos se borran también sus entradas en la galería, así no quedan
 * entradas huérfanas.
 */
trait RemovesGalleryEntries
{
    public static function bootRemovesGalleryEntries(): void
    {
        static::deleting(function (self $model): void {
            $model->galleryEntries()->delete();
        });
    }

    public function galleryEntries(): MorphMany
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }
}
