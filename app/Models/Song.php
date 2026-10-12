<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\SpotifyEmbed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Song extends Model
{
    protected $fillable = [
        'title',
        'artist',
        'album',
        'spotify_url',
        'image_path',
        'note',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    /**
     * @return array{title: string, artist: string, album: string, note: string, image: string, spotify_url: string, embed_url: string}
     */
    public function toViewArray(): array
    {
        $spotifyUrl = SpotifyEmbed::sanitize($this->spotify_url);

        return [
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album ?? '',
            'note' => $this->note ?? '',
            'image' => $this->imageUrl() ?? '',
            'spotify_url' => $spotifyUrl ?? '',
            'embed_url' => SpotifyEmbed::toEmbedUrl($spotifyUrl) ?? '',
        ];
    }
}
