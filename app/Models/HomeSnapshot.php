<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeSnapshot extends Model
{
    protected $fillable = [
        'map_image_path',
        'maps_url',
        'map_label',
        'spotify_embed_url',
        'carousel_interval',
    ];

    protected function casts(): array
    {
        return [
            'carousel_interval' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'maps_url' => 'https://maps.app.goo.gl/6jtvkALmD64tEbkY9',
            'map_label' => 'MONTEVIDEO · URUGUAY',
            'spotify_embed_url' => 'https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW?utm_source=generator',
            'carousel_interval' => 4500,
        ]);
    }

    public function mapImageUrl(): ?string
    {
        if (! $this->map_image_path) {
            return null;
        }

        if (str_starts_with($this->map_image_path, 'http://') || str_starts_with($this->map_image_path, 'https://')) {
            return $this->map_image_path;
        }

        return Storage::disk('public')->url($this->map_image_path);
    }
}
