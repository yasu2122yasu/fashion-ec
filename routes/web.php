<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LineItemController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::controller(ProductController::class)->group(function () {
    Route::name('product.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/product/{id}', 'show')->name('show');
    });
});

// Route::name('line_item.')
//     ->group(function () {
//         Route::post('/line_item/create', 'LineItemController@create')->name('create');
//         Route::post('/line_item/delete', 'LineItemController@delete')->name('delete');
//     });

Route::controller(LineItemController::class)->group(function () {
    Route::name('line_item.')->group(function () {
        Route::post('/line_item/create', 'create')->name('create');
        Route::post('/line_item/delete', 'delete')->name('delete');
    });
});

Route::controller(CartController::class)->group(function () {
    Route::name('cart.')->group(function () {
        Route::get('/cart', 'index')->name('index');
        Route::get('/cart/checkout', 'checkout')->name('checkout');
        Route::get('cart/success', 'success')->name('success');
    });
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__ . '/auth.php';
