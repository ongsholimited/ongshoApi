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
        return response()->json(Constant::POST_STATUS);
    }
    public function getCategoryByMenu()
    {
        
    }
}