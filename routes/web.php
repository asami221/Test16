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

Route::resource('products', ProductController::class);

// ProductController routes
Route::resource('products', ProductController::class)->except(['index', 'show', 'edit', 'update', 'destroy', 'search']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

Route::get('/products/{id}/show', [ProductController::class, 'show'])->name('products.show');

Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::delete('/products/{id}', 'ProductController@destroy')->name('products.destroy');

Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');




// 追加: ログイン後のリダイレクト先
Route::get('/index', function () {
    return view('index');
 });
