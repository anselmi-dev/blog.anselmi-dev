<?php

use App\Http\Controllers\{
    TagController
};
use App\Http\Controllers\Livewire\{
    Pages\Home,
    Pages\About,
    Pages\Contact,
    Blog\Blog,
    Blog\Post,
    Blog\Categories
};
use App\Http\Controllers\Livewire\Pages\Gallery;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('home');
Route::get('/blog', Blog::class)->name('blog.index');
Route::get('/gallery', Gallery::class)->name('gallery.index');
Route::get('/blog/categories', Categories::class)->name('categories.index');
Route::get('/blog/categories/{category:slug}', Categories::class)->name('category.show');
Route::get('/blog/{post:slug}', Post::class)->name('post.show');
Route::get('/blog/t/{tag:slug}', Blog::class)->name('tag.show');

Route::get('/contact', Contact::class)->name('contact');
Route::get('/about', About::class)->name('about');

// Route::get('/b', [PostsController::class, 'index'])->name('posts');
Route::get('/t', [TagController::class, 'index'])->name('tag.index');

