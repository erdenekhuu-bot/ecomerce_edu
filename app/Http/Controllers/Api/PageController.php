<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomPageResource;
use App\Models\Page;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = new CustomPageResource(Page::where('slug', $slug)->first());
        if ($page) {
            return response()->json([
                'success' => true,
                'data' => $page,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => translate('Page not found!')
            ]);
        }
    }
}
