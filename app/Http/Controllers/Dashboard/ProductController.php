<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\ProductDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $product=DB::table('products')->select('id','name','slug','image','description','price')->get();
        return Inertia::render('dashboard/menulist/DashProduct', [
            'products' => $product
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $category=DB::table('categories')->select('id','name','description')->get();
        return Inertia::render('dashboard/form/FormProduct',[
            'categories'=>$category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $rule=$request->validated();
        if($request->hasFile('image')) {
             $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $imagePath = 'images/'.$imageName;
           
        }
        DB::table('products')->insert([
                'name' => $rule['name'],
                'slug' => $rule['slug'],
                'image'=>$imagePath,
                'description' => $rule['description'],
                'category_id' => $rule['category_id'],
                'price' => $rule['price'],
                'created_at' => now(),
                'updated_at' => now(),
        ]);
        return redirect()->route('product');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $record=DB::table('products')->where('id','=',(int)$id)->select('id','image')->first();
        return Inertia::render('dashboard/detail/ProductImage',[
            'detail'=>$record
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $record=DB::table('products')->where('id','=',(int)$id)->first();
        $category=DB::table('categories')->select('id','name','description')->get();
        return Inertia::render('dashboard/detail/ProductDetail',[
            'detail'=>$record,
            'categories'=>$category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductDetails $request, string $id): RedirectResponse
    {
        return redirect()->route('productedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        DB::table('products')->where('id','=',(int)$id)->delete();
        return redirect()->route('product');
    }
}
