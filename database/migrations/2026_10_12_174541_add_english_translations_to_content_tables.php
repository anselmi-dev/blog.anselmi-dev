<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columnas "{campo}_en" para la versión en inglés del sitio.
     *
     * @var array<string, array<string, string>>
     */
    private array $columns = [
        'posts' => ['kicker_en' => 'string', 'title_en' => 'string', 'excerpt_en' => 'text', 'body_en' => 'json', 'caption_en' => 'text', 'alt_en' => 'string'],
        'faqs' => ['question_en' => 'string', 'answer_en' => 'text'],
        'tools' => ['tagline_en' => 'string', 'category_en' => 'string'],
        'fonts' => ['category_en' => 'string', 'sample_en' => 'string', 'note_en' => 'text'],
        'site_colors' => ['name_en' => 'string'],
        'reading_books' => ['title_en' => 'string', 'author_en' => 'string'],
        'songs' => ['note_en' => 'text'],
        'quotes' => ['quote_en' => 'text', 'attribution_en' => 'string'],
        'media' => ['title_en' => 'string', 'category_en' => 'string', 'location_en' => 'string', 'description_en' => 'text', 'tags_en' => 'json'],
        'home_snapshots' => ['map_label_en' => 'string'],
    ];

    public function up(): void
    {
        foreach ($this->columns as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($columns) {
                foreach ($columns as $column => $type) {
                    $table->{$type}($column)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($columns) {
                $table->dropColumn(array_keys($columns));
            });
        }
    }
};
