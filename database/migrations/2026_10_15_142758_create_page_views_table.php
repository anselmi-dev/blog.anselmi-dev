<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);
            $table->string('route_name')->nullable();
            $table->string('locale', 5)->nullable();
            // Huella del día (no se guarda la IP): cuenta visitantes únicos por día.
            $table->char('visitor_hash', 16);
            $table->char('country', 2)->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('device', 10);
            $table->string('browser', 40)->nullable();
            $table->string('os', 40)->nullable();
            $table->string('referrer_host')->nullable();
            $table->string('utm_source')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('created_at');
            $table->index(['visitor_hash', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
