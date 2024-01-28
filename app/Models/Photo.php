<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

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

}
