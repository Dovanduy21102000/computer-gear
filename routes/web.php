<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


//Admin
//Authentication
Route::get('dashboard/index', [DashboardController::class,'index'])->name('dashboard.index')
->middleware('admin');
Route::get('admin', [AuthController::class,'index'])->name('auth.admin')
->middleware('login');
Route::post('login', [AuthController::class,'login'])->name('auth.login');
Route::get('logout', [AuthController::class,'logout'])->name('auth.logout');

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
        'users'             => UserController::class,
        'orders'            => OrderController::class,
    ];
    foreach ($objects as $object => $controller) {
        Route::resource($object, $controller);
    };

    Route::post('posts/upload', [PostController::class, 'upload'])->name('posts.upload');
});
Route::get('/api/districts/{province_id}', function ($province_id) {
    $response = Http::get("https://provinces.open-api.vn/api/p/{$province_id}?depth=2");
    $data = json_decode($response->body(), true);
    return response()->json($data['districts'] ?? []);
});
Route::get('/get-districts/{provinceId}', [OrderController::class, 'getDistricts'])->name('get.districts');

//Client 

// Trang chủ client
// Client Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/', [HomeController::class, 'index'])->name('home.index');


Route::get('/products', [ProductClientController::class, 'index'])->name('client.products.index');
Route::get('/product/{slug}', [ProductClientController::class, 'show'])->name('client.products.detail');


Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


// Route::prefix('auth')->group(function() {
//     Auth::routes();
// });
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', action: [ResetPasswordController::class, 'reset']);



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
