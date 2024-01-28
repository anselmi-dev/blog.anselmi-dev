<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;


class Post extends Model
{
    use HasFactory;

    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "title",
        "status",
        "slug",
        "short_description",
        "content",
        "published_at",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $casts = [
        "published_at" => 'datetime'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
                ->generateSlugsFrom('title')
                ->saveSlugsTo('slug');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'tags_models');
    }

    public function likes()
    {
        return $this->morphMany(LikesModel::class, 'model');
    }

    /**
     * Get the Category that wrote the Post
     *
     * @return BelongsTo
     */
    public function category () : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get URL the Post
     *
     * @return string
     */
    public function getUrlAttribute () : string
    {
        return route('post.show', $this->slug);
    }

    public function getPublishedAtForHumansAttribute() : string
    {
        try {
            $published_at = Carbon::parse($this->published_at);

            return $published_at->isoFormat('LL');

        } catch (\Throwable $th) {
            return $this->published_at;
        }
    }
}
