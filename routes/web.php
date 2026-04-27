<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AnimeController;
use App\Models\Anime;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// Главная
Route::get('/', function () {
    $latestAnime = Anime::latest()->take(4)->get();
    $topAnime    = Anime::orderBy('rating', 'desc')->take(4)->get();
    $latestPosts = Post::latest()->take(3)->get();

    return view('home', compact('latestAnime', 'topAnime', 'latestPosts'));
})->name('home');

// Публичные — просмотр
Route::resource('posts', PostController::class)->only(['index', 'show']);
Route::resource('anime', AnimeController::class)->only(['index', 'show']);

// Дашборд (авторизованные)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Админские — создание/редактирование/удаление
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('posts', PostController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('anime', AnimeController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// Админ-панель
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
});

// Breeze auth
require __DIR__.'/auth.php';