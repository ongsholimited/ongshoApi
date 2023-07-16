<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
class CategoryController extends Controller
{
    public function getCategory(){
        $data=Category::all();
        foreach($data as $category){
            $cat[]=['value'=>$category->id,'label'=>$category->name];
        }
        return response()->json($cat);
    }
}
