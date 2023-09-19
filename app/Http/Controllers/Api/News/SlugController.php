<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Slug;
use App\Http\Traits\SendDataApi;
use App\Http\Traits\SlugableTrait;
class SlugController extends Controller
{
    use SlugableTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function checkSlug($slug,$post_id=null)
    {
        // this condition for store and update option for frontend post
        if($post_id==null){
            $existance=SlugableTrait::slugCount($slug,$post_id);
        }else{
            $existance=SlugableTrait::slugCount($slug,$post_id);
        }
        if($existance>0){
            return SendDataApi::bind(['status'=>true,'count'=>$existance,'message'=>'the slug already exist']);
        }
        return SendDataApi::bind(['status'=>false,'count'=>$existance,'message'=>'the slug is not exist']);
    }
}