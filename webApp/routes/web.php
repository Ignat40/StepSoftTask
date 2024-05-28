<?php

use App\Http\Controllers\CounterpartyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/counterparties', [CounterpartyController::class, 'store'])->name('counterparties.store');

