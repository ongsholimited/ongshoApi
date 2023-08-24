<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Slug;
class SlugController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function checkSlug($slug)
    {
        $existance=Slug::where('slug_name',$slug)->count();
        if($existance>0){
            return response()->json(['status'=>true,'message'=>'the slug already exist']);
        }
        return response()->json(['status'=>false,'message'=>'the slug is not exist']);
    }
}