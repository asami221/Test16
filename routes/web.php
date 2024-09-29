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
Route::get('/products', [ProductController::class, 'index'])->name('products.index');  // 商品一覧
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');  // 商品作成フォーム
Route::post('/products', [ProductController::class, 'store'])->name('products.store');  // 商品登録
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');  // 商品詳細
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');  // 商品編集フォーム
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');  // 商品更新
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');  // 商品削除
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');  // 検索機能

// 商品購入処理
Route::post('/products/purchase', [ProductController::class, 'purchase'])->name('products.purchase');

// 画像のテスト表示
Route::get('/test-image', function () {
    $path = storage_path('app/public/images/default_image.jpg');
    if (file_exists($path)) {
        return response()->file($path);
    } else {
        return response("File does not exist", 404);
    }
});
