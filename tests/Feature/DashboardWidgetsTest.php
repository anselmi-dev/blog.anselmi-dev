<?php

use App\Filament\Widgets\ContentOverview;
use App\Filament\Widgets\PendingOverview;
use App\Models\Collection;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\HomeSnapshot;
use App\Models\Media;
use App\Models\Post;
use App\Models\ReadingBook;
use App\Models\Song;
use App\Models\Tag;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');
});

test('the dashboard shows the content and the pending widgets', function () {
    $this->get('/admin')
        ->assertOk()
        ->assertSeeLivewire(ContentOverview::class)
        ->assertSeeLivewire(PendingOverview::class);
});

test('the content widget counts each kind of content and links to it', function () {
    Post::create(['kind' => 'note', 'title' => 'Una nota', 'slug' => 'una-nota', 'is_published' => true]);
    Post::create(['kind' => 'image', 'title' => 'Una imagen', 'slug' => 'una-imagen', 'is_published' => false]);
    ReadingBook::create(['title' => 'Rayuela', 'author' => 'Julio Cortázar', 'is_published' => true]);
    ReadingBook::create(['title' => 'Ficciones', 'author' => 'Jorge Luis Borges', 'is_published' => false]);
    Song::create(['title' => 'Todo se transforma', 'artist' => 'Jorge Drexler', 'is_published' => true]);
    $collection = Collection::create(['name' => 'Principal', 'slug' => 'principal']);
    $inGallery = Media::create(['title' => 'En la galería']);
    Media::create(['title' => 'Suelta']);
    Gallery::create(['collection_id' => $collection->id, 'galleryable_type' => Media::class, 'galleryable_id' => $inGallery->id]);

    Livewire::test(ContentOverview::class)
        ->assertSeeInOrder(['Posts y notas', '2', '1 nota · 1 imagen · 1 oculto'])
        ->assertSeeInOrder(['Fotos', '2', '1 en colecciones · 1 sin usar'])
        ->assertSeeInOrder(['Colecciones', '1', '1 publicada · 1 ítem'])
        ->assertSeeInOrder(['Libros', '2', '1 publicado · 1 oculto'])
        ->assertSeeInOrder(['Playlist', '1', '1 publicado'])
        ->assertSeeInOrder(['Videos', '0', 'Todavía no hay'])
        ->assertSeeHtml('href="'.route('filament.admin.resources.reading-books.index').'"');
});

test('the pending widget counts unread messages, untranslated texts and the showcase', function () {
    ContactMessage::create(['name' => 'Ana', 'email' => 'ana@example.com', 'message' => 'Hola']);
    ContactMessage::create(['name' => 'Beto', 'email' => 'beto@example.com', 'message' => 'Hola'])->forceFill(['read_at' => now()])->save();
    Tag::create(['name' => 'Costa']);
    Tag::create(['name' => 'Lluvia', 'name_en' => 'Rain']);
    Song::create(['title' => 'Una', 'artist' => 'Alguien', 'note' => 'Nota sin traducir']);
    Song::create(['title' => 'Otra', 'artist' => 'Alguien']);

    expect(PendingOverview::untranslated())->toMatchArray(['Etiquetas' => 1, 'Playlist' => 1, 'Posts' => 0])
        ->and(PendingOverview::showcaseMissing())->toBe(['imagen del mapa', 'etiqueta del mapa', 'música']);

    Livewire::test(PendingOverview::class)
        ->assertSeeInOrder(['Mensajes sin leer', '1', 'Esperan respuesta'])
        ->assertSeeInOrder(['Sin traducir al inglés', '2'])
        ->assertSee('Etiquetas 1')
        ->assertSeeInOrder(['Vitrina de la home', 'Incompleta', 'Falta: imagen del mapa, etiqueta del mapa, música']);
});

test('the pending widget says when everything is done', function () {
    HomeSnapshot::create(['map_image_path' => 'snapshot/mapa.jpg', 'map_label' => 'MONTEVIDEO', 'spotify_embed_url' => 'https://open.spotify.com/embed/playlist/x']);

    Livewire::test(PendingOverview::class)
        ->assertSeeInOrder(['Mensajes sin leer', '0', 'Nada nuevo'])
        ->assertSeeInOrder(['Sin traducir al inglés', '0', 'Todo traducido'])
        ->assertSeeInOrder(['Vitrina de la home', 'Completa']);
});
