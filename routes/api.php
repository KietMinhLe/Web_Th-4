<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WatchController;

Route::apiResource('watches', WatchController::class);

Route::apiResource('image', ImageController::class);

Route::apiResource('size', SizeController::class);

Route::apiResource('brands', BrandController::class);

Route::apiResource('admin', AdminController::class);

Route::apiResource('user', UserController::class);

Route::apiResource('useraddress', UserAddressController::class);

Route::apiResource('order', OrderController::class);

Route::apiResource('transaction', TransactionController::class);

Route::apiResource('orderdetail', OrderDetailController::class);

Route::apiResource('cart', CartController::class);

Route::apiResource('cartitem', CartItemController::class);