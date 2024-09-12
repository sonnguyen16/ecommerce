<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Storage;

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

Route::get('provinces', function (){
    $jsonContent = Storage::disk('local')->get('province.json');
    $data = json_decode($jsonContent, true);
    return response()->json($data);
});

