<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class Welcome extends Controller
{
    public function index():Response {
        $category=DB::table("categories")->where('meta','=','products')->get();
        $service=DB::table("categories")->where('meta','=','services')->get();
        $products=DB::table('products')->limit(12)->get();
        return Inertia::render('home/Home',[
            'bannerUrl' => asset('home.png'),
            'category' => $category,
            'service' => $service,
            'musicbanner'=> asset('musicbanner.png'),
            'products'=>$products,
            'playstation'=> asset('playstation.png'),
            'womenbanner'=> asset('womencollection.png'),
            'speakerbanner'=> asset('speaker.png'),
            'perfunebanner'=> asset('perfune.png')
        ]);
    }
    public function contact():Response {
        return Inertia::render('home/Contact');
    }
    public function about():Response {
        return Inertia::render('home/About');
    }
    public function product($id):Response {
        $record=DB::table('products')
            ->leftJoin('categories','products.category_id', '=', 'categories.id')
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->select('products.*', 'categories.name as category_name', 'product_images.image1', 'product_images.image2', 'product_images.image3', 'product_images.image4')
            ->where('products.id', (int)$id)
            ->first();

        $related=DB::table('products')
            ->where('category_id', $record->category_id)
            ->where('id', '!=', (int)$id)
            ->limit(4)
            ->get();
        return Inertia::render('home/ProductDetail',[
            'product'=>$record,
            'related'=>$related
        ]);
        
    }
    public function categorylist($id): Response{
        $record=DB::table('products')->where('category_id','=',(int)$id)->get();
        return Inertia::render('home/CategoryProduct',['list'=>$record]);
    }

    public function appendPurchase($id): RedirectResponse{
        return redirect()->route('categorylist');
    }

    public function appendFavorite($id):RedirectResponse {
        return redirect()->route('categorylist');
    }
}
