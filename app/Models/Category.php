<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Post;


class Category extends Model
{
    use HasFactory;
    
    use SoftDeletes;

    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "slug",
        "description"
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get URL the Tag
     *
     * @return string
     */
    public function getUrlAttribute () : string
    {
        return route('category.show', $this->slug);
    }

    /**
     * Get the post for the Category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
