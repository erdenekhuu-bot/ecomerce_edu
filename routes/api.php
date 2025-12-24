<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\RequestAll;
use Illuminate\Support\Facades\DB;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/checkout', function(Request $request):void{
    DB::table('request_alls')->updateOrInsert(
        ['ip_address' => $request->ip()],
        [
            'name' => $request->input('name'),
            'request_count' => DB::raw('request_count + 1'),
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
});
