<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;

// 商品検索用の非同期API
Route::get('/products/search', [SalesController::class, 'search'])->name('api.products.search');


Route::post('/purchase', [SalesController::class, 'purchase'])->name('api.products.purchase');  // ここでルート名を変更


Route::delete('/products/{id}', [ProductController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
