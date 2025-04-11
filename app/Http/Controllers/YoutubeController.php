<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function index(){
        return view('backend.product.youtube.index');
    }
}
