<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Category\CategoryRequest;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
