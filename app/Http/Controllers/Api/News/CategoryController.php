<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Category;
use App\Helpers\Constant;
class CategoryController extends Controller
{
    public function getCategory(){
        $category=Category::all();
        return response()->json($category);
    }
    public function getPostType()
    {
        return response()->json(Constant::POST_TYPE);
    }
    public function getCategoryByMenu()
    {
        
    }
}
