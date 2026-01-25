<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class Welcome extends Controller
{
    public function index():Response {
        $category=DB::table("categories")->where('meta','=','products')->get();
        $service=DB::table("categories")->where('meta','=','services')->get();
        return Inertia::render('Home',[
            'bannerUrl' => asset('home.png'),
            'category' => $category,
            'service' => $service,
            'musicbanner'=> asset('musicbanner.png'),
        ]);
    }
    public function contact():Response {
        return Inertia::render('Contact');
    }
    public function about():Response {
        return Inertia::render('About');
    }
}
