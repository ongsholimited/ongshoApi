<?php

namespace App\Http\Controllers\Admin\CrudMaker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Crud;
class CrudMakerController extends Controller
{
    public function __construct($middleware=[]){
        $this->middleware('auth:admin');
    }
    public function call(Request $request,$type)
    {
        // return $request->all();
        $store=new Crud;
        return $store->$type ($request->all());
    }
}