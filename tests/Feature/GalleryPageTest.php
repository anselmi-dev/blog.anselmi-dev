<?php

use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Livewire\Pages\Collections;
use App\Livewire\Pages\Gallery as GalleryPage;
use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Quote;
use App\Models\User;
use App\Models\Video;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('a collection page lists its published photos and quotes', function () {
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

    $response = $this->get(route('gallery.show', ['slug' => 'principal']));

    $response->assertOk()
        ->assertSee('Galería principal')
        ->assertSee('Línea del horizonte', false)
        ->assertSee('La luz correcta llega cuando dejás de perseguirla.', false)
        ->assertDontSee('Foto sin publicar');
});

test('unpublished collections are not listed and their page is not found', function () {
    Collection::create([
        'name' => 'Borrador',
        'slug' => 'borrador',
        'is_published' => false,
        'sort_order' => 0,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertDontSee('Borrador')
        ->assertSee('Todavía no hay colecciones publicadas.');

    $this->get(route('gallery.show', ['slug' => 'borrador']))->assertNotFound();
});

function addToCollection(Collection $collection, Model $galleryable, int $sortOrder, bool $isPublished = true): Gallery
{
    return Gallery::create([
        'collection_id' => $collection->id,
        'galleryable_type' => $galleryable::class,
        'galleryable_id' => $galleryable->id,
        'sort_order' => $sortOrder,
        'is_published' => $isPublished,
    ]);
}

test('the gallery index shows a card per published collection with its counts', function () {
    $travel = Collection::create(['name' => 'Viajes', 'slug' => 'viajes', 'sort_order' => 0]);
    $notes = Collection::create(['name' => 'Notas', 'slug' => 'notas', 'sort_order' => 1]);

    addToCollection($travel, Media::create(['title' => 'Puerto']), 0);
    addToCollection($travel, Media::create(['title' => 'Faro']), 1);
    addToCollection($travel, Quote::create(['quote' => 'Mirar dos veces.']), 2);
    addToCollection($travel, Media::create(['title' => 'Oculta']), 3, isPublished: false);
    addToCollection($notes, Quote::create(['quote' => 'Una sola nota.']), 0);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeInOrder(['Viajes', 'Notas'])
        ->assertSee('2 fotos · 1 cita')
        ->assertSee('1 cita')
        ->assertSee('Mirar dos veces.')
        ->assertSee(route('gallery.show', ['slug' => 'viajes']), false)
        ->assertSee('Actualizada hace');
});

test('the gallery index only shows the first three items of each collection', function () {
    $collection = Collection::create(['name' => 'Muchas', 'slug' => 'muchas']);

    foreach (['Primera', 'Segunda', 'Tercera', 'Cuarta'] as $index => $title) {
        addToCollection($collection, Media::create(['title' => $title]), $index);
    }

    $content = $this->get(route('gallery'))->assertOk()->getContent();

    expect($content)->toContain('alt="Primera"')
        ->and(substr_count($content, 'object-cover'))->toBe(3);
});

test('the gallery index can be sorted by name or by last update', function () {
    $older = Collection::create(['name' => 'Zeta', 'slug' => 'zeta', 'sort_order' => 0]);
    $newer = Collection::create(['name' => 'Alfa', 'slug' => 'alfa', 'sort_order' => 1]);
    $older->forceFill(['updated_at' => now()->subWeek()])->saveQuietly();

    Livewire::test(Collections::class)
        ->assertSeeInOrder(['Zeta', 'Alfa'])
        ->set('sort', 'name')
        ->assertSeeInOrder(['Alfa', 'Zeta'])
        ->set('sort', 'recent')
        ->assertSeeInOrder(['Alfa', 'Zeta'])
        ->set('sort', 'whatever')
        ->assertSet('sort', 'order');

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('role="listbox"', false)
        ->assertSee("\$set('sort', 'name')", false)
        ->assertDontSee('<select', false);

    expect($newer->exists)->toBeTrue();
});

test('collections show their english name under /en', function () {
    Collection::create([
        'name' => 'Viajes',
        'name_en' => 'Travels',
        'slug' => 'viajes',
        'description' => 'Fotos de ruta',
        'description_en' => 'Road photos',
    ]);

    $this->get(localized_route('gallery', [], 'en'))
        ->assertOk()
        ->assertSee('Travels')
        ->assertDontSee('Viajes');

    $this->get(localized_route('gallery.show', ['slug' => 'viajes'], 'en'))
        ->assertOk()
        ->assertSee('Travels')
        ->assertSee('Road photos');
});

test('the collection counts are translated under /en', function () {
    $collection = Collection::create(['name' => 'Viajes', 'slug' => 'viajes']);
    addToCollection($collection, Media::create(['title' => 'Puerto']), 0);
    addToCollection($collection, Quote::create(['quote' => 'Una.']), 1);
    addToCollection($collection, Quote::create(['quote' => 'Dos.']), 2);

    $this->get(localized_route('gallery', [], 'en'))
        ->assertOk()
        ->assertSee('1 photo · 2 quotes');
});

test('videos show up in their collection and open in the viewer', function () {
    $collection = Collection::create(['name' => 'Movimiento', 'slug' => 'movimiento']);
    $video = Video::factory()->withPoster()->create(['title' => 'Olas en la rambla']);
    addToCollection($collection, Media::create(['title' => 'Faro']), 0);
    addToCollection($collection, $video, 1);
    addToCollection($collection, Quote::create(['quote' => 'Sin play.']), 2);

    $this->get(route('gallery.show', ['slug' => 'movimiento']))
        ->assertOk()
        ->assertSee('Olas en la rambla')
        ->assertSee($video->posterUrl(), false);

    Livewire::test(GalleryPage::class, ['slug' => 'movimiento'])
        ->call('openLightbox', 1)
        ->assertSet('lightboxIndex', 1)
        ->assertSeeHtml('<video')
        ->assertSeeHtml('src="'.$video->videoUrl().'"')
        ->call('lightboxPrev')
        ->assertSet('lightboxIndex', 0)
        ->call('lightboxPrev')
        ->assertSet('lightboxIndex', 1);
});

test('a video without a poster shows its first frame', function () {
    $collection = Collection::create(['name' => 'Movimiento', 'slug' => 'movimiento']);
    $video = Video::factory()->create(['poster_path' => null]);
    addToCollection($collection, $video, 0);

    $this->get(route('gallery.show', ['slug' => 'movimiento']))
        ->assertOk()
        ->assertSee($video->videoUrl().'#t=0.1', false);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee($video->videoUrl().'#t=0.1', false);
});

test('the gallery index counts videos', function () {
    $collection = Collection::create(['name' => 'Mixta', 'slug' => 'mixta']);
    addToCollection($collection, Media::create(['title' => 'Una']), 0);
    addToCollection($collection, Video::factory()->create(), 1);
    addToCollection($collection, Video::factory()->create(), 2);
    addToCollection($collection, Quote::create(['quote' => 'Nota.']), 3);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('1 foto · 2 videos · 1 cita');
});

test('admin can upload a video', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(CreateVideo::class)
        ->fillForm([
            'title' => 'Atardecer',
            'video_path' => UploadedFile::fake()->create('atardecer.mp4', 2048, 'video/mp4'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $video = Video::query()->sole();

    expect($video->title)->toBe('Atardecer');
    Storage::disk('public')->assertExists($video->video_path);
});

test('entries whose photo was deleted do not break the home or the collection page', function () {
    $collection = Collection::create(['name' => 'Principal', 'slug' => 'principal']);
    $kept = Media::create(['title' => 'Sigue estando']);
    addToCollection($collection, $kept, 0);
    addToCollection($collection, Media::create(['title' => 'Borrada']), 1);

    // Simula una entrada huérfana de antes (la foto se borró sin tocar la galería).
    Media::query()->where('title', 'Borrada')->delete();

    $this->get(route('home'))->assertOk()->assertSee('Sigue estando');
    $this->get(route('gallery.show', ['slug' => 'principal']))->assertOk()->assertSee('Sigue estando');
    $this->get(route('gallery'))->assertOk()->assertSee('1 foto');
});

test('deleting a photo, video or quote removes it from its collections', function () {
    $collection = Collection::create(['name' => 'Principal', 'slug' => 'principal']);
    $photo = Media::create(['title' => 'Foto']);
    $video = Video::factory()->create();
    $quote = Quote::create(['quote' => 'Cita.']);

    foreach ([$photo, $video, $quote] as $index => $galleryable) {
        addToCollection($collection, $galleryable, $index);
    }

    $photo->delete();
    $video->delete();
    $quote->delete();

    expect(Gallery::query()->count())->toBe(0);
});
