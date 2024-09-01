<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// 明示的な商品ルート
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy'); // DELETE → POST に変更
Route::post('/products/purchase', [ProductController::class, 'purchase'])->name('products.purchase');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 画像のテスト表示
Route::get('/test-image', function () {
    $path = storage_path('app/public/images/default_image.jpg');
    if (file_exists($path)) {
        return response()->file($path);
    } else {
        return response("File does not exist", 404);
    }
});
