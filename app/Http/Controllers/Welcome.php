<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class Welcome extends Controller
{
    public function index():Response {
        $category=DB::table("categories")->where('meta','=','products')->get();
        $service=DB::table("categories")->where('meta','=','services')->get();
        $products=DB::table('products')->limit(5)->get();
        return Inertia::render('Home',[
            'bannerUrl' => asset('home.png'),
            'category' => $category,
            'service' => $service,
            'musicbanner'=> asset('musicbanner.png'),
            'products'=>$products
        ]);
    }
    public function contact():Response {
        return Inertia::render('Contact');
    }
    public function about():Response {
        return Inertia::render('About');
    }
    public function product($id):Response {
        $record=DB::table('products')
            ->leftJoin('categories','products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->where('products.id', (int)$id)
            ->first();
        return Inertia::render('ProductDetail',[
            'product'=>$record
        ]);
    }
    public function categorylist($id): Response{
        return Inertia::render('');
    }
}
