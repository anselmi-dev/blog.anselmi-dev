<?php

use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const string DEFAULT_COLLECTION_SLUG = 'principal';

    public function up(): void
    {
        if (! Schema::hasTable('gallery_items')) {
            return;
        }

        $items = DB::table('gallery_items')->orderBy('sort_order')->get();

        if ($items->isEmpty()) {
            return;
        }

        $collection = Collection::query()->firstOrCreate(
            ['slug' => self::DEFAULT_COLLECTION_SLUG],
            ['name' => 'Galería principal', 'is_published' => true, 'sort_order' => 0]
        );

        foreach ($items as $item) {
            if ($item->kind === 'quote') {
                $galleryable = Quote::query()->create([
                    'quote' => $item->quote,
                    'attribution' => $item->attribution,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ]);
            } else {
                $galleryable = Media::query()->create([
                    'image_path' => $item->image_path,
                    'width' => $item->width,
                    'height' => $item->height,
                    'title' => $item->title,
                    'category' => $item->category,
                    'location' => $item->location,
                    'released_at' => $item->released_at,
                    'description' => $item->description,
                    'tags' => $item->tags ? json_decode($item->tags, true) : null,
                    'iso' => $item->iso,
                    'aperture' => $item->aperture,
                    'shutter' => $item->shutter,
                    'focal_length' => $item->focal_length,
                    'camera' => $item->camera,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ]);
            }

            Gallery::query()->create([
                'collection_id' => $collection->id,
                'galleryable_type' => $galleryable::class,
                'galleryable_id' => $galleryable->id,
                'span' => $item->span,
                'featured' => $item->featured,
                'play' => $item->play,
                'sort_order' => $item->sort_order,
                'is_published' => $item->is_published,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        // Data migration: not automatically reversible. Rolling back removes the
        // migrated rows so the migration can be re-run, but does not restore
        // `gallery_items` (that table is dropped by a later migration).
        Gallery::query()->delete();
        Media::query()->delete();
        Quote::query()->delete();
        Collection::query()->where('slug', self::DEFAULT_COLLECTION_SLUG)->delete();
    }
};
