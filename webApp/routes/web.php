<?php

use App\Http\Controllers\CounterpartyController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
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
Route::resource('products', ProductController::class);
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.delete');

Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');
Route::resource('sales', SalesController::class);
Route::get('/sales/create', [App\Http\Controllers\SalesController::class, 'create'])->name('sales.create');
Route::post('/sales', [App\Http\Controllers\SalesController::class, 'store'])->name('sales.store');
Route::get('/sales/{sale}/edit', [App\Http\Controllers\SalesController::class, 'edit'])->name('sales.edit');
Route::put('/sales/{sale}', [App\Http\Controllers\SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/{sale}', [App\Http\Controllers\SalesController::class, 'destroy'])->name('sales.destroy');
Route::get('/sales/{sale}', [App\Http\Controllers\SalesController::class, 'show'])->name('sales.show');
Route::get('/get-products/{counterparty}', [SalesController::class, 'getProducts']);
Route::get('/get-product-price/{productId}', [SalesController::class, 'getProductPrice']);
Route::get('/get-products/{counterpartyId}', [SalesController::class, 'getProducts']);