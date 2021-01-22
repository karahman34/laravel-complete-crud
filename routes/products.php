<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class)
        ->name('products', 'products')
        ->middleware(['auth'])
        ->except('show');
