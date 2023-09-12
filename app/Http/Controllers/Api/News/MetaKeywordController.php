<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\MetaKeyword;
class MetaKeywordController extends Controller
{
    public function getMeta($slug)
    {
        $meta= MetaKeyword::where('slug',$slug)->first();
        if($meta!=null){
            return response()->json(['status'=>true,'data'=>$meta]);
        }
        return response()->json(['status'=>false,'data'=>'Data not found.'],404);

    }
}