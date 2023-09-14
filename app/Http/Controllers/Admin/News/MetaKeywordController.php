<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\MetaKeyword;
use DataTables;
class MetaKeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
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
            'name'=>'Meta_Keyword',
           ],
           'datatable'=>[
            'title',
            'description',
            'keyword',
            'robots',
            'action',
           ],
           'route'=>route('news.meta_keyword'),
           'fields'=> [
                [
                    'name'=>'title',
                    'label'=>'Title',
                    'placeholder'=>'Enter Title',
                    'type'=>'text',
                    'classes'=>'form-control text-danger',
                    
                ],
                [
                    'name'=>'slug',
                    'label'=>'Slug',
                    'placeholder'=>'Enter Slug',
                    'type'=>'text',
                    'classes'=>'form-control',
                ],
                [
                    'name'=>'slogan',
                    'label'=>'Slogan',
                    'placeholder'=>'Enter Slogan',
                    'type'=>'text',
                    'classes'=>'form-control',
                ],
                [
                    'name'=>'description',
                    'label'=>'Description',
                    'placeholder'=>'Enter Description',
                    'type'=>'textarea',
                    'classes'=>'form-control',
                    'attribute'=>'rows="5"'

                ],
                [
                    'name'=>'keyword',
                    'label'=>'keyword (Separted By Comma)',
                    'placeholder'=>'Enter keyword',
                    'type'=>'text',
                    'classes'=>'form-control',

                ],
                [
                    'name'=>'robots',
                    'label'=>'Robots',
                    'placeholder'=>'Select Robots',
                    'type'=>'select',
                    'classes'=>'form-control',
                    'options'=>[
                        "Active"=>1,
                        "Deactive"=>0,
                    ]
                ],
            ]
        ];
        // return $get=Category::with('parent')->get();
        if(request()->ajax()){
            $get=MetaKeyword::all();
            return DataTables::of($get)
            ->addIndexColumn()
            ->addColumn('action',function($get)use($data){
            $button  ='<div class="d-flex justify-content-center">';
            $button.='<a data-url="'.url('crud_maker/edit').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
            <a data-url="'.url('crud_maker/destroy').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
            $button.='</div>';
            return $button;
        })
        ->rawColumns(['action'])->make(true);
        }
        
        return view('crud_maker.crud_maker',compact('data')); 
    }
}