<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('DashProduct');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $category=DB::table('categories')->select('id','name','description')->get();
        return Inertia::render('dashboard/FormProduct',[
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
            $image=$request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $request->file('image')->store('images', 'public'); 
           
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
