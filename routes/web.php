<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;

Route::get('/', function () {
    return Inertia::render('Welcome',[
        'bannerUrl' => asset('homebanner.png'),
    ]);
})->name('home');


Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('/category', CategoryController::class)->names([
        'index' => 'category',
        'create' => 'categorycreate',
        'store' => 'categorystore',
        ]);
    Route::get('/product',function(){
        return Inertia::render('DashProduct');
    })->name('product');
    Route::resource('/user', UserController::class)->names([
        'index' => 'user',
        'create' => 'user.create'
        ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
