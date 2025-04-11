<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Page;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SiteMapController extends Controller
{
    public function generateSitemap()
    {
        $products = Product::all();
        $categories = Category::all();
        $brands = Brand::all();
        $blogs = Blog::all();
        $pages = Page::where('type','!=', 'home_page')->get();
        $offers = Offer::all();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Home Page
        $xml .= '<url>
                    <loc>'  . env('APP_URL') . '</loc>
                    <priority>1.0</priority>
                    <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
                </url> ';

        // Product url
        // $xml .= '<url>
        //             <loc>' . env('APP_URL') . '/all-blogs' . '</loc>
        //             <priority>0.90</priority>
        //             <lastmod>' . Carbon::now()->timestamp . '</lastmod>
        //         </url> ';
        foreach ($products as $product) {
            $xml .= '<url>
                        <loc>' . env('APP_URL') . '/product' . '/' . $product->slug . '</loc>
                        <priority>0.9</priority>
                        <lastmod>' . $product->updated_at->format('Y-m-d') . '</lastmod>
                    </url> ';
        }


        // ============Category url==============
        $xml .= '<url>
                    <loc>' . env('APP_URL') . '/all-categories' . '</loc>
                    <priority>0.9</priority>
                    <lastmod>' . Carbon::now()->format('Y-m-d'). '</lastmod>
                </url> ';
        foreach ($categories as $category) {
            $xml .= '<url>
                        <loc>' . env('APP_URL') . '/category' . '/' . $category->slug . '</loc>
                        <priority>0.80</priority>
                        <lastmod>' . $category->updated_at->format('Y-m-d') . '</lastmod>
                    </url> ';
        }


        // Brand url
        $xml .= '<url>
                    <loc>' . env('APP_URL') . '/all-brands' . '</loc>
                    <priority>0.9</priority>
                    <lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>
                </url> ';
        foreach ($brands as $brand) {
            $xml .= '<url>
                        <loc>' . env('APP_URL') . '/brand' . '/' . $brand->id . '</loc>
                        <priority>0.8</priority>
                        <lastmod>' . $brand->updated_at->format('Y-m-d') . '</lastmod>
                    </url> ';
        }


        // Brand url
        $xml .= '<url>
                        <loc>' . env('APP_URL') . '/all-offers' . '</loc>
                        <priority>0.9</priority>
                        <lastmod>' . Carbon::now()->format('Y-m-d'). '</lastmod>
                    </url> ';
        foreach ($offers as $offer) {
            $xml .= '<url>
                        <loc>' . env('APP_URL') . '/offer' . '/' . $offer->slug . '</loc>
                        <priority>0.8</priority>
                        <lastmod>' . $offer->updated_at->format('Y-m-d') . '</lastmod>
                    </url> ';
        }


        // Blog url
        $xml .= '<url>
                        <loc>' . env('APP_URL') . '/all-blogs' . '</loc>
                        <priority>0.9</priority>
                        <lastmod>' . Carbon::now()->format('Y-m-d'). '</lastmod>
                    </url> ';
        foreach ($blogs as $blog) {
            $xml .= '<url>
                        <loc>' . env('APP_URL') . '/blog-details' . '/' . $blog->slug . '</loc>
                        <priority>0.8</priority>
                        <lastmod>' . $blog->updated_at->format('Y-m-d') . '</lastmod>
                    </url> ';
        }

        foreach ($pages as $page) {
            $xml .= '<url>
                        <loc>' . env('APP_URL') . '/page' . '/' . $page->slug . '</loc>
                        <priority>0.9</priority>
                        <lastmod>' . $page->updated_at->format('Y-m-d') . '</lastmod>
                    </url> ';
        }


        $xml .= '</urlset>';
        // 

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml';
        File::put($filePath, $xml);
        // 
        flash(translate('Sitemap has generated successfully!'))->success();
        return redirect()->back();
        // return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
