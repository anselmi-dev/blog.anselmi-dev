<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Photo extends Model implements HasMedia
{
    use InteractsWithMedia;

    use HasFactory;

    const DISK = 'photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "title",
        "description",
        "text",
        "size",
        "height",
        "width",
        "name",
        "type",
        "path",
        "localization",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $casts = [
        "localization" => 'array'
    ];

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'tags_models');
    }

    public function getImageAttribute() : Media|null
    {
        return $this->getFirstMedia(self::DISK);
    }

    public function registerMediaConversions (Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(1000)
              ->height(1000)
              ->sharpen(10);
    }
}
