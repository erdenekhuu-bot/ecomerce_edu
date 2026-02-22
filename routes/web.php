<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\DashBoard\AboutUsController;
use App\Http\Controllers\Welcome;

Route::prefix('/')->group(function () {
    Route::get('', [Welcome::class,'index'])->name('home');
    Route::get('/contact', [Welcome::class,'contact'])->name('contact');    
    Route::get('/about', [Welcome::class,'about'])->name('about');
    Route::get('/detail/{id}', [Welcome::class,'product'])->name('detail');
    Route::get('/category/list/{id}',[Welcome::class,'categorylist'])->name('categorylist');
});
Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('/category', CategoryController::class)->names([
        'index' => 'category',
        'create' => 'categorycreate',
        'store' => 'categorystore',
        ]);

    Route::resource('/product', ProductController::class)->names([
        'index' => 'product',
        'create' => 'productcreate',
        'store' => 'productstore',
        'edit'=>'productedit',
        'update'=>'productupdate',
        'show'=>'productshow',
        ]);
    
    Route::patch('/product/update-image/{id}', [ProductController::class, 'updateImage'])->name('productupdateimage');
    
    Route::resource('/about',AboutUsController::class)->names([
        'index'=>'abouts',
        'create'=>'aboutscreate'
    ]);
    
    Route::resource('/user', UserController::class)->names([
        'index' => 'user',
        'create' => 'user.create'
        ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
