<?php

use App\Http\Controllers\Admin\AlbumImageController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ContactClientController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MOMOController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Client\ProductClientController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\VNPayController;
use App\Http\Controllers\Admin\CategoryPostController;

use App\Http\Controllers\Admin\ProfileController;


use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\SpecificationController;
use App\Http\Controllers\Client\UserOrderController;
use App\Http\Controllers\OrderItemController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('admin')->group(function () {
    // Đăng nhập và đăng xuất dành cho admin
    Route::get('login', [AuthController::class, 'index'])->name('auth.admin'); // Hiển thị form đăng nhập
    Route::post('login', [AuthController::class, 'login'])->name('auth.login'); // Xử lý đăng nhập
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout'); // Xử lý đăng xuất
    // Route admin cần quyền truy cập

    Route::middleware(['auth', 'admin'])->group(function () {
        // Routes cho Profile Admin

        Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index'); // Dashboard

        Route::prefix('profile')->name('backend.profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show'); // Trang hiển thị Profile
            Route::put('/update', [ProfileController::class, 'update'])->name('update'); // Xử lý cập nhật Profile
            Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
            Route::get('/delete-image', [ProfileController::class, 'deleteImage'])->name('deleteImage');
        });
        //
        Route::get('orders/cancel-tabs', [OrderController::class, 'cancelTabs'])->name('orders.cancelTabs');
        Route::put('orders/{id}/approve-cancel', [OrderController::class, 'approveCancel'])->name('orders.cancel-approve');
        Route::put('orders/{id}/reject-cancel', [OrderController::class, 'rejectCancel'])->name('orders.cancel-reject');
        //

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
        Route::post('posts/upload', [PostController::class, 'upload'])->name('posts.upload');
        // Route quản lý thông số sản phẩm
        Route::prefix('specifications')->name('admin.specifications.')->group(function () {
            Route::get('product/{product_id}', [SpecificationController::class, 'index'])
                ->name('index');

            Route::get('product/{product_id}/create', [SpecificationController::class, 'create'])
                ->name('create');

            Route::post('product/{product_id}', [SpecificationController::class, 'store'])
                ->name('store');

            Route::get('product/{product_id}/specification/{id}/edit', [SpecificationController::class, 'edit'])
                ->name('edit');

            Route::put('product/{product_id}/bulk-update', [SpecificationController::class, 'bulkUpdate'])
                ->name('bulkUpdate');
        });

        
        Route::prefix('products/{product_id}/images')->name('backend.product_images.')->group(function () {
            // Trang danh sách ảnh
            Route::get('/', [ProductImageController::class, 'index'])->name('index');
        
            // Thêm ảnh mới
            Route::get('/create', [ProductImageController::class, 'create'])->name('create');
            Route::post('/', [ProductImageController::class, 'store'])->name('store');
        
            // Sửa toàn bộ album ảnh
            Route::get('/edit', [ProductImageController::class, 'edit'])->name('edit');  // ✅ không có {key}
            Route::put('/', [ProductImageController::class, 'update'])->name('update');  // ✅ không có {key}
        
            // Xoá ảnh cụ thể theo index trong mảng
            Route::delete('/{key}', [ProductImageController::class, 'destroy'])->name('destroy');
        });
        


        // Đảm bảo rằng route này đã được thêm vào trong routes/web.php
        Route::put('/comments/{id}/toggle-status', [CommentController::class, 'toggleStatus'])->name('admin.comments.toggleStatus');

        Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::get('/comments/{id}/show', [CommentController::class, 'show'])->name('comments.show');
    });
    //Biêns thể
    Route::prefix('products/{product}/variants')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('variants.index');
        Route::get('/create', [ProductVariantController::class, 'create'])->name('variants.create');
        Route::post('/store', [ProductVariantController::class, 'store'])->name('variants.store');
        Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
        Route::put('/{variant}/update', [ProductVariantController::class, 'update'])->name('variants.update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
        Route::get('/{variant}', [ProductVariantController::class, 'show'])->name('variants.show');
    });
});



// Client Routes
Route::middleware(['web'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form'); // Form đăng nhập client
    Route::post('login', [LoginController::class, 'login'])->name('login'); // Xử lý đăng nhập client
    Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // Xử lý đăng xuất client

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.form'); // Form đăng ký
    Route::post('register', [RegisterController::class, 'register'])->name('register'); // Xử lý đăng ký


    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/show_user', [\App\Http\Controllers\Client\UserController::class, 'show'])->name('user.show');
    Route::post('/save_user', [\App\Http\Controllers\Client\UserController::class, 'save'])->name('user.save');
    Route::post('/change_password', [\App\Http\Controllers\Client\UserController::class, 'changePassword'])->name('user.change-password');




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
    Route::get('/products/filter', [ProductClientController::class, 'filteredProducts'])->name('client.products.filter');



    Route::get('/products/category/{slug}', [ProductClientController::class, 'categoryProducts'])->name('client.products.category');
    Route::get('/get-variant', [ProductClientController::class, 'getVariant'])->name('getVariant');
    Route::get('/search', [ProductClientController::class, 'search'])->name('search');
   
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/about_us', [HomeController::class, 'about_us'])->name('about_us');
    Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
    Route::get('/track-order', [CheckoutController::class, 'trackOrderView'])->name('order.track');
    Route::match(['get', 'post'], '/track-order/check', [CheckoutController::class, 'trackOrder'])->name('order.trackOrder');
    //thay doi ne
    Route::get('/orders', [UserOrderController::class, 'index'])->name('client.orders.index');
    Route::get('/orders/{code}', [UserOrderController::class, 'show'])->name('client.orders.show');
    Route::put('/orders/{code}/cancel', [UserOrderController::class, 'cancel'])->name('client.orders.cancel');
    Route::put('/orders/{code}/confirm-received', [UserOrderController::class, 'confirmReceived'])->name('client.orders.confirmReceived');
    //
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
Route::post('/checkout/method', [CheckoutController::class, 'checkoutMethod'])->name('checkout.method');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('applyCoupon');
Route::get('/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('removeCoupon');


Route::post('/vnpay/create', [VNPayController::class, 'createPayment'])->name('vnpay.create');
Route::get('/vnpay/return', [VNPayController::class, 'paymentReturn'])->name('vnpay.return');
Route::post('/vnpay/ipn', [VNPayController::class, 'ipn'])->name('vnpay.ipn');

Route::post('/momo/create', [MomoController::class, 'createPayment'])->name('momo.create');
Route::get('/momo-return', [MOMOController::class, 'handleReturn'])->name('momo.return');
Route::get('/momo/ipn', [MomoController::class, 'ipn'])->name('momo.ipn');



Route::post('/cart/bulk-delete', [CartController::class, 'bulkDelete'])->name('cart.bulkDelete');


//
Route::get('/comments/{productId}', [CommentController::class, 'getComments']);
