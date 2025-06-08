<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FumettoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rotte fumetti (accessibili anche agli ospiti)
Route::get('/fumetti', [FumettoController::class, 'index'])->name('fumetti.index');
Route::get('/fumetti/{fumetto}', [FumettoController::class, 'show'])->name('fumetti.show');

// Rotte contatti (accessibili anche agli ospiti)
Route::get('/contattaci', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contattaci', [ContactController::class, 'store'])->name('contact.store');
Route::get('/messaggio-inviato', [ContactController::class, 'success'])->name('contact.success');

// Profilo pubblico (accessibile anche agli ospiti)
Route::get('/user/{id}', [ProfileController::class, 'showPublic'])->name('profile.public');

// Rotte profilo utente (autenticazione richiesta)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Rotte fumetti per utenti autenticati
Route::middleware(['auth'])->group(function () {
    Route::get('/fumetti/create', [FumettoController::class, 'create'])->name('fumetti.create');
    Route::post('/fumetti', [FumettoController::class, 'store'])->name('fumetti.store');
    Route::get('/fumetti/{fumetto}/edit', [FumettoController::class, 'edit'])->name('fumetti.edit');
    Route::put('/fumetti/{fumetto}', [FumettoController::class, 'update'])->name('fumetti.update');
    Route::delete('/fumetti/{fumetto}', [FumettoController::class, 'destroy'])->name('fumetti.destroy');
});

// Rotte admin per gestione contatti
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/contatti', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contatti/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::put('/contatti/{contact}', [ContactController::class, 'update'])->name('contacts.update');
});
