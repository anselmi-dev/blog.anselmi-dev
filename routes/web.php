<?php

use App\Livewire\Pages\About;
use App\Livewire\Pages\Colors;
use App\Livewire\Pages\Faq;
use App\Livewire\Pages\Fonts;
use App\Livewire\Pages\Gallery;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Posts;
use App\Livewire\Pages\PostShow;
use App\Livewire\Pages\Projects;
use App\Livewire\Pages\ProjectShow;
use App\Livewire\Pages\Tools;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Home::class)->name('home');

Route::livewire('/sobre-mi', About::class)->name('about');

Route::livewire('/faq', Faq::class)->name('faq');

Route::livewire('/galeria', Gallery::class)->name('gallery');

Route::livewire('/proyectos', Projects::class)->name('projects');
Route::livewire('/proyectos/{slug}', ProjectShow::class)->name('projects.show');

Route::livewire('/blog', Posts::class)->name('blog');
Route::livewire('/blog/{slug}', PostShow::class)->name('blog.show');

Route::livewire('/servicios/fuentes', Fonts::class)->name('services.fonts');
Route::livewire('/servicios/colores', Colors::class)->name('services.colors');
Route::livewire('/servicios/herramientas', Tools::class)->name('services.tools');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
