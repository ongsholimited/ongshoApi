<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Category;
use App\Http\Traits\SendDataApi;
class MenuController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    public function getMenu()
    {
        $data=Category::where('parent_id',null)->where('serial','<>',null)->orderBy('serial','asc')->get();
        if($data->count()>0){
            return SendDataApi::bind($data,200);
        }
        return SendDataApi::bind('data not found',404);
    }
}