<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Category;
class CategoryController extends Controller
{
    public function getCategory(){
        $category=Category::all();
        return response()->json($category);
    }
}
