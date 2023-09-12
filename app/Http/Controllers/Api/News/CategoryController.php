<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Category;
use App\Helpers\Constant;
use App\Http\Traits\SendDataApi;
use Google\Service\Gmail\SendAs;

class CategoryController extends Controller
{
    public function getCategory(){
        $category=Category::where('status',1)->get();
        if($category->count()>0){
            return SendDataApi::bind($category,200);
        }
        return SendDataApi::bind('data not found',404);
    }
    public function getPostType()
    {
        $data=['status'=>Constant::POST_STATUS,'post_type'=>Constant::POST_TYPE];
        return SendDataApi::bind($data,200);
    }
    public function getCategoryByMenu()
    {
        
    }
}