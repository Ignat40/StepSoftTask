<?php

use App\Http\Controllers\CounterpartyController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::post('/counterparties', [CounterpartyController::class, 'store'])->name('counterparties.store');
Route::put('/counterparties/{counterparty}', [CounterpartyController::class, 'update'])->name('counterparties.update');
Route::delete('/counterparties/{counterparty}', [CounterpartyController::class, 'delete'])->name('counterparties.delete');

Route::middleware(['auth'])->group(function () {
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
});
Route::resource('products', ProductController::class);
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.delete');


Route::middleware(['auth'])->group(function () {
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
});
Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
Route::get('/sales/{sale}/edit', [SalesController::class, 'edit'])->name('sales.edit');
Route::put('/sales/{sale}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy');
Route::get('/get-products/{counterparty}', [SalesController::class, 'getProducts']);
Route::get('/get-product-price/{productId}', [SalesController::class, 'getProductPrice']);
