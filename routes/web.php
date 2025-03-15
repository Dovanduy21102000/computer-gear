<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
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
Route::get('dashboard/index', [DashboardController::class,'index'])->name('dashboard.index');

// comment
Route::get('comment/index', [CommentController::class, 'index'])->name('comment.index');
Route::put('/comment/toggle/{id}', [CommentController::class, 'toggleStatus'])->name('comment.toggleStatus');

// Attribute
Route::resource('attributes', AttributeController::class);
// Attribute_values
Route::resource('attribute-values', AttributeValueController::class);