<?php

use App\Http\Controllers\{
    HomeController,
    PostsController,
    CategoryController,
    TagController
};
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

Route::get('/', HomeController::class)->name('home');
// Route::get('/b', [PostsController::class, 'index'])->name('posts');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/about', 'pages.about')->name('about');
Route::get('/t', [TagController::class, 'index'])->name('tag.index');
Route::get('/t/{tag:slug}', [TagController::class, 'show'])->name('tag.show');
Route::get('/c', [CategoryController::class, 'index'])->name('category.index');
Route::get('/{post:slug}', [PostsController::class, 'show'])->name('post.show');
Route::get('/c/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
