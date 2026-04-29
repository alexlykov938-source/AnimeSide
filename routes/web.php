<?php

use App\Http\Controllers\{AdminController, PostController, AnimeController, EpisodeController};
use App\Models\{Anime, Post};
use Illuminate\Support\Facades\Route;

// Главная
Route::get('/', function () {
    $latestAnime = Anime::latest()->take(4)->get();
    $topAnime    = Anime::orderBy('rating', 'desc')->take(4)->get();
    $latestPosts = Post::latest()->take(3)->get();

    return view('home', compact('latestAnime', 'topAnime', 'latestPosts'));
})->name('home');

// Дашборд
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ======= АНИМЕ =======
// Публичные
Route::get('/anime', [AnimeController::class, 'index'])->name('anime.index');

// Админские (create/store — до {anime})
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/anime/create', [AnimeController::class, 'create'])->name('anime.create');
    Route::post('/anime', [AnimeController::class, 'store'])->name('anime.store');
});

// С параметром {anime} — после create
Route::get('/anime/{anime}/watch', [AnimeController::class, 'watch'])->name('anime.watch');
Route::get('/anime/{anime}', [AnimeController::class, 'show'])->name('anime.show');

// Остальные админские (edit/update/destroy — с {anime})
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/anime/{anime}/edit', [AnimeController::class, 'edit'])->name('anime.edit');
    Route::put('/anime/{anime}', [AnimeController::class, 'update'])->name('anime.update');
    Route::delete('/anime/{anime}', [AnimeController::class, 'destroy'])->name('anime.destroy');
});

// Поиск через AniList
Route::post('/anime/fetch', [AnimeController::class, 'fetch'])
    ->middleware(['auth', 'admin'])
    ->name('anime.fetch');

// ======= ПОСТЫ =======
// Публичные
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Админские (create/store — до {post})
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});

// С параметром {post} — после create
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Остальные админские (edit/update/destroy — с {post})
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/anime/{anime}/episodes', [EpisodeController::class, 'index'])->name('episodes.index');
    Route::get('/anime/{anime}/episodes/create', [EpisodeController::class, 'create'])->name('episodes.create');
    Route::post('/anime/{anime}/episodes', [EpisodeController::class, 'store'])->name('episodes.store');
    Route::get('/anime/{anime}/episodes/{episode}/edit', [EpisodeController::class, 'edit'])->name('episodes.edit');
    Route::put('/anime/{anime}/episodes/{episode}', [EpisodeController::class, 'update'])->name('episodes.update');
    Route::delete('/anime/{anime}/episodes/{episode}', [EpisodeController::class, 'destroy'])->name('episodes.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
    });

// Breeze auth
require __DIR__.'/auth.php';