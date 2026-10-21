<?php

use App\Livewire\Pages\About;
use App\Livewire\Pages\Collections;
use App\Livewire\Pages\Colors;
use App\Livewire\Pages\CuriousCode;
use App\Livewire\Pages\CuriousCodeShow;
use App\Livewire\Pages\Faq;
use App\Livewire\Pages\Fonts;
use App\Livewire\Pages\Gallery;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\ImageMetadata;
use App\Livewire\Pages\Playlist;
use App\Livewire\Pages\Posts;
use App\Livewire\Pages\PostShow;
use App\Livewire\Pages\Projects;
use App\Livewire\Pages\ProjectShow;
use App\Livewire\Pages\Tools;
use Illuminate\Support\Facades\Route;

/**
 * Páginas públicas del sitio. Se registran dos veces: en español sin prefijo
 * y en inglés bajo /en, con nombres "en.*" y slugs traducidos.
 *
 * @param  array<string, string>  $paths
 */
$registerSiteRoutes = function (array $paths): void {
    Route::livewire($paths['home'], Home::class)->name('home');

    Route::livewire($paths['about'], About::class)->name('about');

    Route::livewire($paths['faq'], Faq::class)->name('faq');

    Route::livewire($paths['gallery'], Collections::class)->name('gallery');
    Route::livewire($paths['gallery'].'/{slug}', Gallery::class)->name('gallery.show');

    Route::livewire($paths['playlist'], Playlist::class)->name('playlist');

    Route::livewire($paths['projects'], Projects::class)->name('projects');
    Route::livewire($paths['projects'].'/{slug}', ProjectShow::class)->name('projects.show');

    Route::livewire($paths['curious_code'], CuriousCode::class)->name('curious-code');
    Route::livewire($paths['curious_code'].'/{slug}', CuriousCodeShow::class)->name('curious-code.show');

    Route::livewire($paths['blog'], Posts::class)->name('blog');
    Route::livewire($paths['blog'].'/{slug}', PostShow::class)->name('blog.show');

Route::livewire('/servicios/fuentes', Fonts::class)->name('services.fonts');
Route::livewire('/servicios/colores', Colors::class)->name('services.colors');
Route::livewire('/servicios/herramientas', Tools::class)->name('services.tools');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/admin')->name('dashboard');
});

require __DIR__.'/settings.php';
