<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;


Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/product/{id}', [CatalogController::class, 'product'])->name('catalog.product');
Route::get('/catalog/load-more', [CatalogController::class, 'loadMoreProducts'])->name('catalog.load-more');
