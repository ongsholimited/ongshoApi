<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Category;
use App\Helpers\Constant;
class CategoryController extends Controller
{
    public function getCategory(){
        $category=Category::where('status',1)->get();
        return response()->json($category);
    }
    public function getPostType()
    {
        return response()->json(['status'=>Constant::POST_STATUS,'post_type'=>Constant::POST_TYPE]);
    }
    public function getCategoryByMenu()
    {
        
    }
}