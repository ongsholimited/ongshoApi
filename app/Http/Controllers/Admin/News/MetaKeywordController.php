<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MetaKeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        
        // return $get=Category::with('parent')->get();
        if(request()->ajax()){
            $get=Category::with('children')->get();
            return DataTables::of($get)
            ->addIndexColumn()
            ->addColumn('action',function($get){
            $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('news.category.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
            <a data-url="'.route('news.category.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
            $button.='</div>';
            return $button;
        })
        ->addColumn('name',function($get){
            if($get->parent!=null){
                return $get->parent->name.'-'.$get->name;
            }else{
                return $get->name;
            }
        })
        ->rawColumns(['action'])->make(true);
        }

        //$fields= [
        //     'name'=>'',
        //     'label'=>'',
        //     'styles'=>'',
        //     'classes'=>'',
        //     'placeholder'=>'',
        //     'type'=>'text',
        //     'attribute'=>'',
        //     'options'=>[]
        // ]
        $data=
        [
           'form'=>[
            'name'=>'Category',
           ],
           'fields'=> [
                [
                    'name'=>'title',
                    'label'=>'Title',
                    'placeholder'=>'Enter Title',
                    'type'=>'text',
                    'classes'=>'form-control',

                ],
                [
                    'name'=>'slug',
                    'label'=>'Slug',
                    'placeholder'=>'Enter Slug',
                    'type'=>'text',
                    'classes'=>'form-control',
                ],
                [
                    'name'=>'description',
                    'label'=>'Description',
                    'placeholder'=>'Enter Description',
                    'type'=>'textarea',
                    'classes'=>'form-control',

                ],
                [
                    'name'=>'keyword',
                    'label'=>'keyword',
                    'placeholder'=>'Enter Description',
                    'type'=>'text',
                    'classes'=>'form-control',

                ],
                [
                    'name'=>'robots',
                    'label'=>'Robots',
                    'placeholder'=>'Enter Description',
                    'type'=>'text',
                    'classes'=>'form-control',

                ],
            ]
        ];
        return view('crud_maker.crud_maker',compact('data'));
        
    }
}