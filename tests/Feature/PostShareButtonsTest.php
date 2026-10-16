<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Post::create([
        'kind' => 'note',
        'title' => 'Iterar sin drama',
        'slug' => 'iterar-sin-drama',
        'excerpt' => 'Notas sobre avanzar de a poco.',
        'is_published' => true,
    ]);
});

test('a post shows buttons to share it', function () {
    $url = url('/blog/iterar-sin-drama');

    $this->get('/blog/iterar-sin-drama')
        ->assertOk()
        ->assertSee('data-share-buttons', escape: false)
        ->assertSee('¿Te gustó? Compartilo')
        ->assertSee('Copiar enlace')
        ->assertSee('https://wa.me/?text='.rawurlencode('Iterar sin drama '.$url), escape: false)
        ->assertSee('https://x.com/intent/post?text='.rawurlencode('Iterar sin drama').'&amp;url='.rawurlencode($url), escape: false)
        ->assertSee('https://www.linkedin.com/sharing/share-offsite/?url='.rawurlencode($url), escape: false)
        ->assertSee('https://www.facebook.com/sharer/sharer.php?u='.rawurlencode($url), escape: false);
});

test('the share buttons are translated and share the english url under /en', function () {
    $this->get('/en/blog/iterar-sin-drama')
        ->assertOk()
        ->assertSee('Liked it? Share it')
        ->assertSee('Copy link')
        ->assertSee(rawurlencode(url('/en/blog/iterar-sin-drama')), escape: false);
});
