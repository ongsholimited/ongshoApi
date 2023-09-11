<?php

namespace App\Http\Controllers\Admin\CrudMaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Crud;
class CrudMakerController extends Controller
{
    public function __construct($middleware=[]){
        $this->middleware($middleware);
    }
    public function call(Request $request,$type)
    {
        $store=new Crud;
        $store->$type ($request());
    }
}