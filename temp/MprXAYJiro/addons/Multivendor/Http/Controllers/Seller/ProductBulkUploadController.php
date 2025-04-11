<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\ProductsImport;
use App\Models\ProductsExport;
use PDF;
use Excel;
use Auth;

class ProductBulkUploadController extends Controller
{
    public function __construct() {

        // $this->middleware(['permission:product_bulk_import'])->only('index');
        // $this->middleware(['permission:product_bulk_export'])->only('export');
    }

    public function index()
    {
        if(auth()->user()->shop->verification_status == 0 && auth()->user()->shop->verification_info == null){
            flash(translate("You are not verified. Please verify at first."))->warning();
            return redirect()->back();
        }
        if (Auth::user()->user_type == 'seller') {
            if(Auth::user()->shop->approval){
                return view('addon:multivendor::seller.bulk_upload.index');
            }
            else{
                flash(translate('Your shop is not verified yet!'))->warning();
                return back();
            }
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('addon:multivendor::seller.bulk_upload.index');
        }
    }

    public function export(){
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();

        return PDF::loadView('addon:multivendor::seller.downloads.category',[
            'categories' => $categories,
        ], [], [])->download('category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();

        return PDF::loadView('addon:multivendor::seller.downloads.brand',[
            'brands' => $brands,
        ], [], [])->download('brands.pdf');
    }

    public function bulk_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
            $import = new ProductsImport;
            Excel::import($import, request()->file('bulk_file'));
        }
        
        return back();
    }

}
