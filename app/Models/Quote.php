<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Galleryable;
use App\Models\Concerns\HasEnglishTranslations;
use App\Models\Concerns\RemovesGalleryEntries;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model implements Galleryable
{
    use HasEnglishTranslations, RemovesGalleryEntries;

    protected $fillable = [
        'quote',
        'attribution',
        'quote_en',
        'attribution_en',
    ];

    public function galleryKind(): string
    {
        return 'quote';
    }

    /**
     * @return array<string, mixed>
     */
    public function toGalleryArray(): array
    {
        return [
            'quote' => $this->translated('quote'),
            'attribution' => $this->translated('attribution'),
        ];
    }
}
