<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
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
Route::prefix('admin')->group(function () {
    Route::resource('orders', OrderController::class);
});
Route::get('provinces', [OrderController::class, 'getProvinces']);
Route::get('districts/{provinceCode}', [OrderController::class, 'getDistricts']);
