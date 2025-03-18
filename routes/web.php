<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

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
Route::get('/api/districts/{province_id}', function ($province_id) {
    $response = Http::get("https://provinces.open-api.vn/api/p/{$province_id}?depth=2");
    $data = json_decode($response->body(), true);
    return response()->json($data['districts'] ?? []);
});
Route::get('/get-districts/{provinceId}', [OrderController::class, 'getDistricts'])->name('get.districts');
