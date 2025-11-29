<?php

namespace App\Http\Controllers\DashBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Category\CategoryRequest;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $record=DB::table('categories')->paginate(10);
        return Inertia::render('DashCategory',[
            'record'=>$record,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request):Response
    {
       return Inertia::render('dashboard/FormCategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request):RedirectResponse
    {  
       $data=$request->validated();
       DB::table('categories')->insert([
        'name' => $data->name,
        'description' => $data->description,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
        return redirect()->route('category');
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
