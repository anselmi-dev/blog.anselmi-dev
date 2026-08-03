<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex', 7);
            $table->json('rgb')->nullable();
            $table->json('cmyk')->nullable();
            $table->string('span', 10)->default('md'); // sm | md | lg | xl
            $table->string('ink', 10)->default('dark'); // dark | light
            $table->unsignedTinyInteger('column_index')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_colors');
    }
};
