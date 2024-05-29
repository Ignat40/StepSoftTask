<?php

use App\Http\Controllers\CounterpartyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/counterparties', [CounterpartyController::class, 'store'])->name('counterparties.store');
Route::put('/counterparties/{counterparty}', [CounterpartyController::class, 'update'])->name('counterparties.update');
Route::delete('/counterparties/{counterparty}', [CounterpartyController::class, 'delete'])->name('counterparties.delete');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');


