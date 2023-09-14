<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\MetaKeyword;
use App\Http\Traits\SendDataApi;
class MetaKeywordController extends Controller
{
    public function getMeta($slug)
    {
        $meta= MetaKeyword::where('slug',$slug)->first();
        if($meta!=null){
            return SendDataApi::bind($meta,200);
        }
        return SendDataApi::bind('data not found',404);
    }
}