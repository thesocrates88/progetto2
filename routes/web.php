<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContoController;
use App\Http\Controllers\TransazioneController;
use App\Http\Controllers\CartaController;
use App\Http\Controllers\Api\CheckoutApiController;
use Illuminate\Support\Facades\Route;

//rotta usata per far il login dopo chiamata API
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/confirm', [CheckoutApiController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/checkout/pay', [CheckoutApiController::class, 'pay'])->name('checkout.pay');
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//rotte comuni a profili loggati
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/conto', [ContoController::class, 'show'])->name('conto.show');
    Route::get('/transazioni', [TransazioneController::class, 'index'])->name('transazioni.index');

    //solo utente può autorizzare la transazione
    Route::post('/transazione/{id}/autorizza', [TransazioneController::class, 'autorizza'])->name('transazione.autorizza');
    //carte
    Route::resource('carte', CartaController::class)->only(['index', 'create', 'store', 'destroy']);

});

require __DIR__.'/auth.php';
