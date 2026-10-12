<?php

use App\Filament\Resources\Posts\Pages\EditPost;
use App\Models\HomeSnapshot;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function menuPost(array $attributes = []): Post
{
    return Post::create([
        'slug' => fake()->unique()->slug(2),
        'kind' => 'note',
        'title' => 'Nota cualquiera',
        'is_published' => true,
        'published_at' => now(),
        ...$attributes,
    ]);
}

test('header menu lists the site sections with links in the current language', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('id="site-menu"', false)
        ->assertSee('Explorar')
        ->assertSee('href="'.url('/servicios/fuentes').'"', false)
        ->assertSee('href="'.url('/proyectos').'"', false);

    $this->get('/en')
        ->assertOk()
        ->assertSee('Explore')
        ->assertSee('href="'.url('/en/services/fonts').'"', false)
        ->assertSee('href="'.url('/en/projects').'"', false);
});

test('header menu shows the two most recent featured posts', function () {
    menuPost(['title' => 'Foto destacada', 'kind' => 'image', 'featured_in_menu' => true, 'published_at' => now()->subDay()]);
    menuPost(['title' => 'Nota destacada', 'featured_in_menu' => true, 'published_at' => now()]);
    menuPost(['title' => 'Destacada antigua', 'featured_in_menu' => true, 'published_at' => now()->subYear()]);
    menuPost(['title' => 'Nota sin destacar']);
    menuPost(['title' => 'Borrador destacado', 'featured_in_menu' => true, 'is_published' => false]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Posts destacados')
        ->assertSeeInOrder(['Nota destacada', 'Foto destacada'])
        ->assertDontSee('Destacada antigua')
        ->assertDontSee('Nota sin destacar')
        ->assertDontSee('Borrador destacado');
});

test('featured posts in the menu use their english version', function () {
    $post = menuPost(['title' => 'Nota destacada', 'title_en' => 'Featured note', 'featured_in_menu' => true]);

    $this->get('/en')
        ->assertOk()
        ->assertSee('Featured note')
        ->assertSee('href="'.url('/en/blog/'.$post->slug).'"', false);
});

test('header menu hides the featured column when nothing is featured', function () {
    menuPost();

    $this->get('/')
        ->assertOk()
        ->assertDontSee('Posts destacados');
});

test('admin can feature a post in the menu', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    $post = menuPost();

    Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
        ->fillForm(['featured_in_menu' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh()->featured_in_menu)->toBeTrue();
});

test('header menu links to the playlist only when there is one', function () {
    HomeSnapshot::current()->update(['playlist_url' => null]);

    $this->get('/')
        ->assertOk()
        ->assertDontSee('href="'.url('/playlist').'"', false);

    HomeSnapshot::current()->update(['playlist_url' => 'https://open.spotify.com/playlist/6zZEmocQXHdXIFunj8UJUS']);

    $this->get('/')
        ->assertOk()
        ->assertSee('href="'.url('/playlist').'"', false);
});
