<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Menu;
use App\Http\Traits\SendDataApi;
class MenuController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    public function getMenu()
    {
        $data=Menu::with('category')->get();
        if($data->count()>0){
            return SendDataApi::bind($data,200);
        }
        // return SendDataApi::bind('data not found',404);
    }
}