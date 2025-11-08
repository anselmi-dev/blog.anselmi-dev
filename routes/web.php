<?php

use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\{
//     TagController
// };
use App\Livewire\{
    Pages\Home,
    Pages\About,
    Pages\Contact,
    Pages\Projects,
    Pages\Project,
    Pages\Gallery,
    Blog\Posts,
    Blog\Post,
    Blog\Categories,
};

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
Route::get('/blog', Posts::class)->name('blog.index');
Route::get('/gallery', Gallery::class)->name('gallery.index');
Route::get('/blog/categories', Categories::class)->name('categories.index');
Route::get('/blog/categories/{category:slug}', Categories::class)->name('category.show');
Route::get('/blog/{post:slug}', Post::class)->name('post.show');
Route::get('/blog/t/{tag:slug}', Posts::class)->name('tag.show');

Route::get('/contact', Contact::class)->name('contact');
Route::get('/about', About::class)->name('about');

Route::get('/projects', Projects::class)->name('projects');
Route::get('/projects/{project}', Project::class)->name('project');
