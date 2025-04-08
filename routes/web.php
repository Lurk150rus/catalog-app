<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;


Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/group/{id}', [CatalogController::class, 'group'])->name('catalog.group');
Route::get('/product/{id}', [CatalogController::class, 'product'])->name('catalog.product');
