<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

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
});

Route::get('provinces', function (){
    $jsonContent = Storage::disk('local')->get('province.json');
    $data = json_decode($jsonContent, true);
    return response()->json($data);
});

