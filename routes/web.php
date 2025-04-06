<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\WatchController;
use App\Models\Brand;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Welcome');
});

// Các nào có cần giao diện thì mới cần khai báo route || Còn lại xem php artisan route:list

// =============================Watch
//Xem các sản phẩm
Route::get('/list-watches', [WatchController::class, 'list'])->name('watches.list');

//Form thêm
Route::get('/watches', [WatchController::class, 'create'])->name('watches');

// // Hiển thị form cập nhật đồng hồ
Route::get('/watches/{watch}/edit', [WatchController::class, 'edit'])->name('watches.edit');


// =============================Brands
// Xem các thương hiệu
Route::get('/list-brands', [BrandController::class, 'list'])->name('brands.list');

// Form thêm
Route::get('/brands', [BrandController::class, 'create'])->name('brands');

// Form edit
Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');