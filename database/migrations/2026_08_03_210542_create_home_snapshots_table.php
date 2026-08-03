<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('map_image_path')->nullable();
            $table->string('maps_url')->nullable();
            $table->string('map_label')->nullable();
            $table->string('spotify_embed_url')->nullable();
            $table->unsignedInteger('carousel_interval')->default(4500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_snapshots');
    }
};
