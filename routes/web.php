<?php

use App\Http\Controllers\{
    TagController
};
use App\Livewire\{
    Pages\Projects,
    Pages\Project,
};

use App\Livewire\Admin\{
    PostFormAdmin,
    PostsAdmin,
    PhotosAdmin,
    PhotoFormAdmin,
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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function() {

    Route::get('/', About::class)->name('home');
    Route::get('/home-2', Home::class);
    Route::get('/blog', Blog::class)->name('blog.index');
    Route::get('/gallery', Gallery::class)->name('gallery.index');
    Route::get('/blog/categories', Categories::class)->name('categories.index');
    Route::get('/blog/categories/{category:slug}', Categories::class)->name('category.show');
    Route::get('/blog/{post:slug}', Post::class)->name('post.show');
    Route::get('/blog/t/{tag:slug}', Blog::class)->name('tag.show');

    Route::get('/contact', Contact::class)->name('contact');
    Route::get('/about', About::class)->name('about');

    Route::get('/projects', Projects::class)->name('projects');
    Route::get('/projects/{project}', Project::class)->name('project');

    // Route::get('/b', [PostsController::class, 'index'])->name('posts');
    Route::get('/t', [TagController::class, 'index'])->name('tag.index');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('dashaboard/posts', PostsAdmin::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.posts');

Route::get('dashaboard/posts/edit/{post:slug}', PostFormAdmin::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.posts.edit');

Route::get('dashaboard/posts/new', PostFormAdmin::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.posts.create');

Route::get('dashaboard/photos', PhotosAdmin::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.photos');

Route::get('dashaboard/photos/edit/{post:slug}', PhotoFormAdmin::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.photo.edit');

Route::get('dashaboard/photos/new', PhotoFormAdmin::class)
    ->middleware(['auth', 'verified'])
    ->name('admin.photo.create');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
