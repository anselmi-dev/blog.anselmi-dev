<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->string('thumb_path')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->string('released_at')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('iso')->nullable();
            $table->string('aperture')->nullable();
            $table->string('shutter')->nullable();
            $table->string('focal_length')->nullable();
            $table->string('camera')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
