<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Setting;
use App\Models\Upload;
use App\Models\Background;
use DB;

class SwipperBackground extends Controller
{
    public function index(Request $request){
        return Background::all();
    }
}
