<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Shop\ShopOrderController;
use App\Http\Controllers\Shop\ShopProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\ShipmentLocationController;
use App\Http\Controllers\Shop\ShopCategoryController;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

Route::get('products', [ProductController::class, 'getProducts']);
Route::get('categories', [CategoryController::class, 'getCategories']);
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function (){
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('refresh-token', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('profile/update', [AuthController::class, 'updateProfile'])->name('updateProfile');

    Route::get('cart', [CartController::class, 'getCart']);
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::post('add-many-to-cart', [CartController::class, 'addManyToCart']);

    Route::post('order', [OrderController::class, 'order']);
    Route::get('orders', [OrderController::class, 'getOrders']);
    Route::get('orders/{order_detail_id}', [OrderController::class, 'getOrder']);
    Route::get('orders-list', [OrderController::class, 'listOrder']);
    Route::prefix('shop')->group(function (){
        Route::get('orders', [ShopOrderController::class, 'getOrders']);
        Route::get('orders-list', [ShopOrderController::class, 'listOrder']);
        Route::get('orders/{order_detail_id}', [ShopOrderController::class, 'getOrder']);
        Route::post('orders/update', [ShipmentLocationController::class, 'store']);

        Route::get('products', [ShopProductController::class, 'getProducts']);
        Route::get('products/{product_id}', [ShopProductController::class, 'getProduct']);
        Route::post('products/store', [ShopProductController::class, 'storeProduct']);
        Route::delete('products/delete-image', [ShopProductController::class, 'deleteImage']);

        Route::get('categories', [ShopCategoryController::class, 'getCategories']);
        Route::get('categories/{category_id}', [ShopCategoryController::class, 'getCategory']);
        Route::post('categories/store', [ShopCategoryController::class, 'storeCategory']);

        Route::get('customers', [CustomerController::class, 'index']);
    });
});

Route::get('media/{filename}', function ($filename){
    $path = storage_path('app/public/' . $filename);
    if (!file_exists($path)){
        abort(404);
    }
    return response()->file($path);
});

Route::get('media/{folder}/{filename}', function ($folder, $filename){
    $path = storage_path('app/public/' . $folder . '/' . $filename);
    if (!file_exists($path)){
        abort(404);
    }
    return response()->file($path);
});

Route::get('provinces', function (){
    $provinces = Province::all();
    return response()->json($provinces);
});

Route::get('districts/{province_code}', function ($province_code){
    $districts = District::where('province_code', $province_code)->get();
    return response()->json($districts);
});

Route::get('wards/{district_code}', function ($district_code){
    $wards = Ward::where('district_code', $district_code)->get();
    return response()->json($wards);
});

