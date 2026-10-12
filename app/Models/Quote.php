<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Galleryable;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model implements Galleryable
{
    protected $fillable = [
        'quote',
        'attribution',
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
            'quote' => $this->quote,
            'attribution' => $this->attribution,
        ];
    }
}
