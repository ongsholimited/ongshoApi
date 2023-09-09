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
    public function checkSlug($slug,$post_id=null)
    {
        // this condition for store and update option for frontend post
        if($post_id==null){
            $existance=Slug::where('slug_name',$slug)->count();
        }else{
            $existance=Slug::where('slug_name',$slug)->whereNotIn('post_id',[$post_id])->count();
        }
        if($existance>0){
            return response()->json(['status'=>true,'count'=>$existance,'message'=>'the slug already exist']);
        }
        return response()->json(['status'=>false,'count'=>$existance,'message'=>'the slug is not exist']);
    }
}