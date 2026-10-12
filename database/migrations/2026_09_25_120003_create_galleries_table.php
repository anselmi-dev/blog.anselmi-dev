<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->string('galleryable_type');
            $table->unsignedBigInteger('galleryable_id');
            $table->string('span', 20)->default('tall'); // wide | tall
            $table->boolean('featured')->default(false);
            $table->boolean('play')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['galleryable_type', 'galleryable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
