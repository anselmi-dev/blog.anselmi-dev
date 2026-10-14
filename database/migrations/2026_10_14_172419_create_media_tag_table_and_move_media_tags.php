<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Las etiquetas de las fotos pasan de dos columnas JSON (tags / tags_en) a un
 * modelo Tag con su traducción. Las de tags_en se emparejan por posición.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_tag', function (Blueprint $table) {
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->primary(['media_id', 'tag_id']);
        });

        DB::table('media')->orderBy('id')->each(function (object $media): void {
            $names = array_values(array_filter((array) json_decode((string) $media->tags, true), 'filled'));
            $englishNames = array_values((array) json_decode((string) $media->tags_en, true));

            foreach ($names as $position => $name) {
                $slug = Str::slug($name) ?: 'tag-'.md5($name);
                $tagId = DB::table('tags')->where('slug', $slug)->value('id');

                if ($tagId === null) {
                    $tagId = DB::table('tags')->insertGetId([
                        'name' => $name,
                        'name_en' => filled($englishNames[$position] ?? null) ? $englishNames[$position] : null,
                        'slug' => $slug,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('media_tag')->insertOrIgnore([
                    'media_id' => $media->id,
                    'tag_id' => $tagId,
                    'sort_order' => $position,
                ]);
            }
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['tags', 'tags_en']);
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->json('tags')->nullable();
            $table->json('tags_en')->nullable();
        });

        DB::table('media')->orderBy('id')->each(function (object $media): void {
            $tags = DB::table('media_tag')
                ->join('tags', 'tags.id', '=', 'media_tag.tag_id')
                ->where('media_tag.media_id', $media->id)
                ->orderBy('media_tag.sort_order')
                ->get(['tags.name', 'tags.name_en']);

            DB::table('media')->where('id', $media->id)->update([
                'tags' => $tags->isEmpty() ? null : json_encode($tags->pluck('name')->all()),
                'tags_en' => $tags->whereNotNull('name_en')->isEmpty() ? null : json_encode($tags->map(fn (object $tag): ?string => $tag->name_en)->all()),
            ]);
        });

        Schema::dropIfExists('media_tag');
    }
};
