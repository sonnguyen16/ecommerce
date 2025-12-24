<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Shop\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== PUBLIC ROUTES =====

// Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/cart', [PageController::class, 'cart'])->name('cart');
Route::get('/products/{slug}', [PageController::class, 'productShow'])->name('products.show');
Route::get('/categories/{slug}', [PageController::class, 'categoryShow'])->name('categories.show');
Route::get('/blog/{slug}', [PageController::class, 'blogShow'])->name('blog.show');

// Public API (no CSRF required for GET requests)
Route::get('/api/provinces', [LocationController::class, 'provinces']);
Route::get('/api/districts/{province_code}', [LocationController::class, 'districts']);
Route::get('/api/wards/{district_code}', [LocationController::class, 'wards']);
Route::get('/api/blogs', [BlogController::class, 'getBlogs']);

// ===== GUEST ROUTES (non-authenticated) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ===== AUTHENTICATED ROUTES =====
Route::middleware('auth')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile pages
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/api/profile/update', [AuthController::class, 'updateProfile']); // API route

    // Order tracking
    Route::get('/profile/tracking', [PageController::class, 'tracking'])->name('profile.tracking');
    Route::get('/profile/tracking/{id}', [PageController::class, 'orderDetail'])->name('profile.order.detail');

    // Cart API
    Route::get('/api/cart', [CartController::class, 'getCart']);
    Route::post('/api/add-to-cart', [CartController::class, 'addToCart']);
    Route::post('/api/add-many-to-cart', [CartController::class, 'addManyToCart']);

    // Order API
    Route::post('/api/order', [OrderController::class, 'order']);
    Route::get('/api/orders', [OrderController::class, 'getOrders']);
    Route::get('/api/orders/{order_detail_id}', [OrderController::class, 'getOrder']);

    // Shop API routes (matching api.php)
    Route::prefix('api/shop')->group(function () {
        // Products
        Route::post('/products/store', [\App\Http\Controllers\Shop\ShopProductController::class, 'storeProduct']);
        Route::delete('/products/delete-image', [\App\Http\Controllers\Shop\ShopProductController::class, 'deleteImage']);
        Route::delete('/products/{product_id}', [\App\Http\Controllers\Shop\ShopProductController::class, 'deleteProduct']);

        // Categories
        Route::post('/categories/store', [\App\Http\Controllers\Shop\ShopCategoryController::class, 'storeCategory']);
        Route::delete('/categories/{category_id}', [\App\Http\Controllers\Shop\ShopCategoryController::class, 'deleteCategory']);

        // Orders
        Route::post('/orders/update', [\App\Http\Controllers\Shop\ShopOrderController::class, 'updateOrder']);
    });

    // Blog API routes
    Route::post('/api/blogs', [BlogController::class, 'storeBlog']);
    Route::delete('/api/blogs/{blog_id}', [BlogController::class, 'deleteBlog']);
    Route::post('/api/upload-image', [BlogController::class, 'uploadImage']);

    // ===== ADMIN ROUTES =====
    Route::prefix('manage')->group(function () {
        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{id}/update', [AdminOrderController::class, 'update'])->name('admin.orders.update');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::get('/products/{id}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::post('/products/store', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::delete('/products/delete-image', [AdminProductController::class, 'deleteImage'])->name('admin.products.deleteImage');

        // Categories
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
        Route::get('/categories/{id}', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::post('/categories/store', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Blogs
        Route::get('/blogs', [AdminBlogController::class, 'index'])->name('admin.blogs');
        Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('admin.blogs.create');
        Route::get('/blogs/{slug}', [AdminBlogController::class, 'edit'])->name('admin.blogs.edit');
        Route::post('/blogs/store', [AdminBlogController::class, 'store'])->name('admin.blogs.store');
        Route::delete('/blogs/{id}', [AdminBlogController::class, 'destroy'])->name('admin.blogs.destroy');
        Route::post('/blogs/upload-image', [AdminBlogController::class, 'uploadImage'])->name('admin.blogs.uploadImage');
    });
});
