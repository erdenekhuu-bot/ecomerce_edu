<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome',[
        'bannerUrl' => asset('homebanner.png'),
    ]);
})->name('home');


Route::middleware(['auth','verified'])->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/category', function () {
        return Inertia::render('DashCategory');
    })->name('category');
    Route::get('/product',function(){
        return Inertia::render('DashProduct');
    })->name('product');
    Route::get('/user',function(){
        return Inertia::render('DashUser');
    })->name('user');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
