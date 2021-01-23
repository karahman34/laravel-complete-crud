<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class)
        ->name('products', 'products')
        ->middleware(['auth'])
        ->except('show');

Route::middleware(['auth'])->prefix('products')->name('products.')->group(function () {
    Route::get('/export', [ProductController::class, 'export'])->name('export');
    Route::post('/export', [ProductController::class, 'export']);
    
    Route::get('/import', [ProductController::class, 'import'])->name('import');
    Route::post('/import', [ProductController::class, 'import']);
});
