<?php

use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('gallery page lists published photos and quotes from the first published collection', function () {
    $collection = Collection::create([
        'name' => 'Galería principal',
        'slug' => 'principal',
        'is_published' => true,
        'sort_order' => 0,
    ]);

    $photo = Media::create([
        'title' => 'Línea del horizonte',
        'category' => 'Viajes',
        'width' => 1200,
        'height' => 675,
    ]);

    $quote = Quote::create([
        'quote' => 'La luz correcta llega cuando dejás de perseguirla.',
        'attribution' => '— nota de campo',
    ]);

    $unpublishedPhoto = Media::create([
        'title' => 'Foto sin publicar',
    ]);

    Gallery::create([
        'collection_id' => $collection->id,
        'galleryable_type' => Media::class,
        'galleryable_id' => $photo->id,
        'span' => 'wide',
        'sort_order' => 0,
        'is_published' => true,
    ]);

    Gallery::create([
        'collection_id' => $collection->id,
        'galleryable_type' => Quote::class,
        'galleryable_id' => $quote->id,
        'span' => 'tall',
        'sort_order' => 1,
        'is_published' => true,
    ]);

    Gallery::create([
        'collection_id' => $collection->id,
        'galleryable_type' => Media::class,
        'galleryable_id' => $unpublishedPhoto->id,
        'span' => 'tall',
        'sort_order' => 2,
        'is_published' => false,
    ]);

    $response = $this->get(route('gallery'));

    $response->assertOk()
        ->assertSee('Línea del horizonte', false)
        ->assertSee('La luz correcta llega cuando dejás de perseguirla.', false)
        ->assertDontSee('Foto sin publicar');
});

test('gallery page is empty when no collection is published', function () {
    Collection::create([
        'name' => 'Borrador',
        'slug' => 'borrador',
        'is_published' => false,
        'sort_order' => 0,
    ]);

    $response = $this->get(route('gallery'));

    $response->assertOk();
});
