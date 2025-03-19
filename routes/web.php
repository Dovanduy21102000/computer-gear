<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CouponController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\PostController;


use App\Http\Controllers\ProductClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Admin

Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('admin')->group(function () {
    $objects = [
        
        'categories'        => CategoryController::class,
        'attributes'        => AttributeController::class,
        'attributevalues'   => AttributeValueController::class,
        'brands'            => BrandController::class,
        'coupons'           => CouponController::class,
        'banners'           => BannerController::class,
        'products'          => ProductController::class,
        'posts'             => PostController::class,
        'users'             =>UserController::class,
    ];
    foreach ($objects as $object => $controller) {
        Route::resource($object, $controller);
    };

    Route::post('posts/upload', [PostController::class, 'upload'])->name('posts.upload');
});
<<<<<<< HEAD
=======

Route::resource('users', UserController::class);
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');


>>>>>>> acf26489b6efda40ac4b8213f9c61158c0f1f2af

//Client 

// Trang chủ client
Route::get('/', [HomeController::class, 'index'])->name('home.index');
<<<<<<< HEAD
Route::get('/products', [ProductClientController::class, 'index']);
=======

Route::get('/products', [ProductClientController::class, 'index'])->name('client.products.index');
Route::get('/product/{slug}', [ProductClientController::class, 'show'])->name('client.products.detail');


>>>>>>> acf26489b6efda40ac4b8213f9c61158c0f1f2af
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');