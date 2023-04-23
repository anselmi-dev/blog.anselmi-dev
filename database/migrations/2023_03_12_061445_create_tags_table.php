<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug")->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tag_posts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBiginteger('tag_id');

            $table->unsignedBiginteger('post_id');

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_posts');

        Schema::dropIfExists('tags');
    }
};
