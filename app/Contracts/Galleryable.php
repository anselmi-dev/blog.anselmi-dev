<?php

declare(strict_types=1);

namespace App\Contracts;

interface Galleryable
{
    /**
     * @return array<string, mixed>
     */
    public function toGalleryArray(): array;

    public function galleryKind(): string;
}
