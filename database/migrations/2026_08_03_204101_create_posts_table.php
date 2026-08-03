<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('kind', 20)->default('note'); // note | image
            $table->string('kicker')->nullable();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->json('body')->nullable();
            $table->text('caption')->nullable();
            $table->string('image_path')->nullable();
            $table->string('alt')->nullable();
            $table->boolean('show_in_bento')->default(false);
            $table->string('bento_type', 20)->nullable(); // card | image
            $table->string('bento_grid_class')->nullable();
            $table->unsignedInteger('bento_sort')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
