<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;

Route::get('/', [Welcome::class,'index'])->name('home');


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
        ]);
    
    Route::resource('/user', UserController::class)->names([
        'index' => 'user',
        'create' => 'user.create'
        ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
