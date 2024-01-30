<?php

use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthenticate;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

// barnd list
Route::post('/brandlist', [BrandController::class, 'BrandList'])->middleware(TokenAuthenticate::class);

// category list
Route::post('/categorylist', [CategoryController::class, 'CategoryList']);


// product list
Route::post('/listProductByCategory/{id}', [ProductController::class, 'ListProductByCategory']);
Route::post('/listProductByBrand/{id}', [ProductController::class, 'ListProductByBrand']);
Route::post('/listProductByRemark/{remark}', [ProductController::class, 'ListProductByRemark']);


// slider list
Route::post('/listProductBySlider', [ProductController::class, 'ListProductBySlider']);

// product details
Route::post('/productDetailsById/{id}', [ProductController::class, 'ProductDetailsById']);
Route::post('/listReviewByProduct/{product_id}', [ProductController::class, 'ListReviewByProduct']);


// policy
Route::post('/plicybytype/{type}', [PolicyController::class, 'PolicyByType']);



// user auth
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/user-login-verify', [UserController::class, 'userLoginVerify']);

Route::get('/logout', [UserController::class, 'userLogout']);


// product list details



// group route in middleware TokenAuthenticate
Route::middleware(TokenAuthenticate::class)->group(function () {
    Route::post('/user-create-profile',[ProfileController::class, 'createProfile']);
});
