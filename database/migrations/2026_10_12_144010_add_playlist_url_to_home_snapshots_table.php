<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_snapshots', function (Blueprint $table) {
            $table->string('playlist_url')->nullable()->after('spotify_embed_url');
        });

        DB::table('home_snapshots')
            ->whereNull('playlist_url')
            ->update(['playlist_url' => 'https://open.spotify.com/playlist/6zZEmocQXHdXIFunj8UJUS']);
    }

    public function down(): void
    {
        Schema::table('home_snapshots', function (Blueprint $table) {
            $table->dropColumn('playlist_url');
        });
    }
};
