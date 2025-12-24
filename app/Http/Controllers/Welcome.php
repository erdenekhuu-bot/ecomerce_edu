<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class Welcome extends Controller
{
    public function index():Response {
        $category=DB::table("categories")->where('meta','=','product')->get();
        $service=DB::table("categories")->where('meta','=','services')->get();
        return Inertia::render('Welcome',[
            'bannerUrl' => asset('home.png'),
            'category' => $category,
            'service' => $service
        ]);
    }
}
