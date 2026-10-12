<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('gallery_items');
    }

    public function down(): void
    {
        // Recreates the original structure only; data was migrated forward by
        // 2026_09_25_120004_migrate_gallery_items_to_new_schema and is not restored here.
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20)->default('photo');
            $table->string('span', 20)->default('tall');
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('play')->default(false);
            $table->string('released_at')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('iso')->nullable();
            $table->string('aperture')->nullable();
            $table->string('shutter')->nullable();
            $table->string('focal_length')->nullable();
            $table->string('camera')->nullable();
            $table->json('tags')->nullable();
            $table->text('quote')->nullable();
            $table->string('attribution')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }
};
