<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Shop\ShopOrderController;
use App\Http\Controllers\Shop\ShopProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Shop\ShipmentLocationController;

Route::get('products', [ProductController::class, 'getProducts']);
Route::get('categories', [CategoryController::class, 'getCategories']);

Route::prefix('auth')->group(function (){
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('profile', [AuthController::class, 'profile'])->name('profile');
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('refresh-token', [AuthController::class, 'refresh'])->name('refresh');
    });
});

Route::middleware('auth:sanctum')->group(function (){
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::post('add-many-to-cart', [CartController::class, 'addManyToCart']);
    Route::get('cart', [CartController::class, 'getCart']);
    Route::post('order', [OrderController::class, 'order']);
    Route::get('orders', [OrderController::class, 'getOrders']);
});

Route::prefix('shop')->middleware('auth:sanctum')->group(function (){
    Route::get('orders', [ShopOrderController::class, 'getOrders']);
    Route::get('products', [ShopProductController::class, 'getProducts']);
    Route::post('products/store', [ShopProductController::class, 'storeProduct']);
    Route::post('orders/update', [ShipmentLocationController::class, 'store']);
});

Route::get('provinces', function (){
    $jsonContent = Storage::disk('local')->get('province.json');
    $data = json_decode($jsonContent, true);
    return response()->json($data);
});

