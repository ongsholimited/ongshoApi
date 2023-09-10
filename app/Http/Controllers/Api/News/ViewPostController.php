<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\PostView;
class ViewPostController extends Controller
{
    public function viewCount($id){
        return json_encode(request());
        // $count=PostView::create([
        //     'post_id'=>$id,
        //     'ip'=>request()->ip()
        // ]);
    }
}