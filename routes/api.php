<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 商品検索用の非同期API
Route::get('/products/search', [ProductController::class, 'search'])->name('api.products.search');

// 購入処理API
Route::post('/purchase', [ProductController::class, 'purchase'])->name('api.products.purchase');
