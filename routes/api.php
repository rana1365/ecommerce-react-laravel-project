<?php

use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\front\OrderController as FrontOrderController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SizeController;
use App\Http\Controllers\front\AccountController;
use App\Http\Controllers\front\ProductController as FrontProductController;
use App\Http\Controllers\front\ShippingController as FrontShippingController;
use App\Http\Controllers\TempImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/admin/login', [AuthController::class, 'authenticate']);

Route::get('latest-products', [FrontProductController::class, 'latestProducts']);

Route::get('featured-products', [FrontProductController::class, 'featuredProducts']);

Route::get('get-products', [FrontProductController::class, 'getProducts']);

Route::get('get-categories', [FrontProductController::class, 'getCategories']);

Route::get('get-brands', [FrontProductController::class, 'getBrands']);

Route::get('get-product/{id}', [FrontProductController::class, 'getProduct']);

Route::post('register', [AccountController::class, 'register']);

Route::post('login', [AccountController::class, 'authenticate']);

Route::get('get-shipping-front', [FrontShippingController::class, 'getShipping']);

// Customer/user routes
Route::group(['middleware' => ['auth:sanctum', 'checkUserRole']], function () {

    Route::post('save-order', [FrontOrderController::class, 'saveOrder']);

    Route::get('get-order-details/{id}', [AccountController::class, 'getOrderDetails']);

    Route::get('get-orders', [AccountController::class, 'getOrders']);

    Route::post('update-profile', [AccountController::class, 'updateProfile']);

    Route::get('get-profile-details', [AccountController::class, 'getProfileDtails']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'checkAdminRole']], function () {
    
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    Route::post('categories', [CategoryController::class, 'store']);

    Route::resource('brands', BrandController::class);

    Route::resource('products', ProductController::class);

    Route::get('sizes', [SizeController::class, 'index']);

    Route::post('temp-images', [TempImageController::class, 'store']);

    Route::post('save-product-image', [ProductController::class, 'saveProductImage']);

    Route::get('change-product-default-image', [ProductController::class, 'updateDefaultImage']);

    Route::delete('delete-product-image/{id}', [ProductController::class, 'deleteProductImage']);

    Route::get('orders', [OrderController::class, 'index']);

    Route::get('orders/{id}', [OrderController::class, 'details']);

    Route::post('update-order/{id}', [OrderController::class, 'updateOrder']);

    Route::get('get-shipping', [ShippingController::class, 'getShipping']);

    Route::post('save-shipping', [ShippingController::class, 'updateShipping']);

});
