<?php


use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;


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

$objects = [
    'categories'        => CategoryController::class,
    'attributes'        => AttributeController::class,
    'attributevalues'   => AttributeValueController::class,
    'brands'            => BrandController::class,
    'coupons'           => CouponController::class,
    'banners'           => BannerController::class
];




Route::get('/dashboard/index', [DashboardController::class,'index'])->name('dashboard.index');




Route::prefix('admin')->group(function () {
    foreach ($objects as $object => $controller) {
    Route::resource($object, $controller);
  };
});
//User

Route::resource('users', UserController::class);
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

