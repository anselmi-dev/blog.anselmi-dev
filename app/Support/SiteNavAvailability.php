<?php

namespace App\Support;

use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class SiteNavAvailability
{
    private const CACHE_TTL_SECONDS = 60;

    private const CACHE_KEY = 'site.nav.availability';

    /** @var array{hasPublishedFaqs: bool, hasPublishedGalleryPhotos: bool, hasPublishedPosts: bool}|null */
    private static ?array $memo = null;

    /**
     * @return array{hasPublishedFaqs: bool, hasPublishedGalleryPhotos: bool, hasPublishedPosts: bool}
     */
    public static function flags(): array
    {
        if (self::$memo !== null) {
            return self::$memo;
        }

        self::$memo = Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, static function (): array {
            return [
                'hasPublishedFaqs' => Faq::query()->published()->exists(),
                'hasPublishedGalleryPhotos' => GalleryItem::query()
                    ->published()
                    ->where('kind', 'photo')
                    ->exists(),
                'hasPublishedPosts' => Post::query()->published()->exists(),
            ];
        });

        return self::$memo;
    }

    public static function forget(): void
    {
        self::$memo = null;
        Cache::forget(self::CACHE_KEY);
    }
}
