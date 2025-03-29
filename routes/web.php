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
use App\Http\Controllers\CategoryPostController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MOMOController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\VNPayController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Admin Routes
Route::prefix('admin')->group(function () {
    // Đăng nhập và đăng xuất dành cho admin
    Route::get('login', [AuthController::class, 'index'])->name('auth.admin'); // Hiển thị form đăng nhập
    Route::post('login', [AuthController::class, 'login'])->name('auth.login'); // Xử lý đăng nhập
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout'); // Xử lý đăng xuất
    // Route admin cần quyền truy cập
    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index'); // Dashboard

        // Các route resource dành cho admin
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
            'orderitems'        => OrderItemController::class,
            'contacts'          => ContactController::class,
            'productvariants'   => ProductVariantController::class,
            'category_post'     => CategoryPostController::class,

        ];
        foreach ($objects as $object => $controller) {
            Route::resource($object, $controller);
        };


        // Route upload bài viết
        Route::post('posts/upload', [PostController::class, 'upload'])->name('posts.upload');
    });
});

//Biêns thể
Route::prefix('products/{product}/variants')->group(function () {
    Route::get('/', [ProductVariantController::class, 'index'])->name('variants.index');
    Route::get('/create', [ProductVariantController::class, 'create'])->name('variants.create');
    Route::post('/store', [ProductVariantController::class, 'store'])->name('variants.store');
    Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
    Route::put('/{variant}/update', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
});


// Client Routes
Route::middleware(['web'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login'); // Form đăng nhập client
    Route::post('login', [LoginController::class, 'login'])->name('login'); // Xử lý đăng nhập client
    Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // Xử lý đăng xuất client

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register'); // Form đăng ký
    Route::post('register', [RegisterController::class, 'register']); // Xử lý đăng ký


    Route::get('/', [HomeController::class, 'index'])->name('home.index');


    // Route liên hệ client
    // Route::get('/contacts', [ContactClientController::class, 'index'])->name('client.contacts.index');
    // Route::post('/contacts', [ContactClientController::class, 'store'])->name('client.contacts.store');


    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/bulk-delete', [CartController::class, 'bulkDelete'])->name('cart.bulkDelete');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


    Route::get('/products', [ProductClientController::class, 'index'])->name('client.products.index');
    Route::get('/product/{slug}', [ProductClientController::class, 'show'])->name('client.products.detail');

    Route::get('/products/brand/{brandSlug}', [ProductClientController::class, 'showByBrand'])->name('client.products.brand');

    Route::get('/products/category/{slug}', [ProductClientController::class, 'categoryProducts'])->name('client.products.category');
    Route::get('/get-variant', [ProductClientController::class, 'getVariant'])->name('getVariant');
    Route::get('/search', [ProductClientController::class, 'search'])->name('search');



    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');



    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/about_us', [App\Http\Controllers\HomeController::class, 'about_us'])->name('about_us');
    Route::get('/faqs', [App\Http\Controllers\HomeController::class, 'faqs'])->name('faqs');
    Route::get('/track-order', [CheckoutController::class, 'trackOrderView'])->name('order.track');
    Route::match(['get', 'post'], '/track-order/check', [CheckoutController::class, 'trackOrder'])->name('order.trackOrder');
});

Route::get('/api/districts/{province_id}', function ($province_id) {
    $response = Http::get("https://provinces.open-api.vn/api/p/{$province_id}?depth=2");
    $data = json_decode($response->body(), true);
    return response()->json($data['districts'] ?? []);
});

Route::get('/get-districts/{provinceId}', [OrderController::class, 'getDistricts'])->name('get.districts');
Route::get('/contact', [ContactClientController::class, 'index'])->name('client.contacts.index');
Route::post('/contact', [ContactClientController::class, 'store'])->name('client.contacts.store');


Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

Route::post('/vnpay/create', [VNPayController::class, 'createPayment'])->name('vnpay.create');
Route::get('/vnpay/return', [VNPayController::class, 'paymentReturn'])->name('vnpay.return');
Route::post('/vnpay/ipn', [VNPayController::class, 'ipn'])->name('vnpay.ipn');

Route::post('/momo/create', [MomoController::class, 'createPayment'])->name('momo.create');
Route::get('/momo-return', [MOMOController::class, 'handleReturn'])->name('momo.return');
Route::get('/momo/ipn', [MomoController::class, 'ipn'])->name('momo.ipn');

Route::post('/cart/bulk-delete', [CartController::class, 'bulkDelete'])->name('cart.bulkDelete');
