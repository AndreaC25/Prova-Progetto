<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FumettoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rotte pubbliche fumetti
Route::get('/fumetti', [FumettoController::class, 'index'])->name('fumetti.index');
Route::get('/fumetti/{fumetto}', [FumettoController::class, 'show'])->name('fumetti.show');

// Rotte contatti pubbliche
Route::get('/contattaci', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contattaci', [ContactController::class, 'store'])->name('contact.store');
Route::get('/messaggio-inviato', [ContactController::class, 'success'])->name('contact.success');

// Profilo pubblico
Route::get('/user/{id}', [ProfileController::class, 'showPublic'])->name('profile.public');

// Recensioni pubbliche
Route::get('/fumetti/{fumetto}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

/*
|--------------------------------------------------------------------------
| Rotte Autenticazione
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rotte Utenti Autenticati
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profilo utente
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/preferiti', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/fumetti/{fumetto}/preferiti', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
        Route::delete('/preferiti', [App\Http\Controllers\FavoriteController::class, 'clear'])->name('favorites.clear');
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar', [ProfileController::class, 'uploadAvatar'])->name('upload-avatar');
        Route::delete('/avatar', [ProfileController::class, 'removeAvatar'])->name('remove-avatar');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });

    // Gestione fumetti
    Route::prefix('fumetti')->name('fumetti.')->group(function () {
        Route::get('/create', [FumettoController::class, 'create'])->name('create');
        Route::post('/', [FumettoController::class, 'store'])->name('store');
        Route::get('/{fumetto}/edit', [FumettoController::class, 'edit'])->name('edit');
        Route::put('/{fumetto}', [FumettoController::class, 'update'])->name('update');
        Route::delete('/{fumetto}', [FumettoController::class, 'destroy'])->name('destroy');
        Route::post('/{fumetto}/publish', [FumettoController::class, 'publish'])->name('publish');
        Route::post('/{fumetto}/unpublish', [FumettoController::class, 'unpublish'])->name('unpublish');
        Route::get('/dashboard/fumettista', [FumettoController::class, 'dashboard'])->name('dashboard');
    });

    // Recensioni
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/fumetti/{fumetto}', [ReviewController::class, 'store'])->name('store');
        Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // Preferiti
    Route::prefix('preferiti')->name('favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::post('/fumetti/{fumetto}', [FavoriteController::class, 'toggle'])->name('toggle');
        Route::delete('/', [FavoriteController::class, 'clear'])->name('clear');
    });

    // Admin area
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('contatti')->name('contacts.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
            Route::put('/{contact}', [ContactController::class, 'update'])->name('update');
        });

        Route::post('/favorites/{fumetto}/toggle', [FavoriteController::class, 'toggle'])
    ->name('favorites.toggle')
    ->middleware('auth');
    });

    Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
});
