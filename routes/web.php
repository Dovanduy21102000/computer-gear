<?php


use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Admin

Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');







Route::get('/dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');




Route::prefix('admin')->group(function () {
    $objects = [
        'categories'        => CategoryController::class,
        'attributes'        => AttributeController::class,
        'attributevalues'   => AttributeValueController::class,
        'brands'            => BrandController::class,
        'coupons'           => CouponController::class,
        'banners'           => BannerController::class,
        'products'          => ProductController::class,
        'posts'             => PostController::class
    ];
    foreach ($objects as $object => $controller) {
        Route::resource($object, $controller);
    };

    Route::post('posts/upload', [PostController::class, 'upload'])->name('posts.upload');
});
//User

Route::resource('users', UserController::class);
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');



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
Route::get('/shop', [ProductClientController::class, 'index'])->name('shop.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
