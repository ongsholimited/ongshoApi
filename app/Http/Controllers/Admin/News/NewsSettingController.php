<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\News\NewsSetting;
class NewsSettingController extends Controller
{
    public function index(){
        $data=
        [
           'form'=>[
            'name'=>'News_Setting',
           ],
           'datatable'=>[
            'key',
            'value',
            'action',
           ],
           'route'=>route('news_setting'),
           'fields'=> [
                [
                    'name'=>'key',
                    'label'=>'Key Name',
                    'placeholder'=>'Select Key Name',
                    'type'=>'options',
                    'classes'=>'form-control',
                    'options'=>[
                        'Active'=>1,
                        'request_limit'=>'request_limit',
                        'pin_post'=>'pin_post',
                        'section_1'=>'section_1',
                        'section_2'=>'section_2',
                        'section_3'=>'section_3',
                        'section_4'=>'section_4',
                    ]
                ],
                [
                    'name'=>'value',
                    'label'=>'Status',
                    'placeholder'=>'Select Key Name',
                    'type'=>'number',
                    'classes'=>'form-control',
                ],
            ]
        ];
        // return $get=Category::with('parent')->get();
        if(request()->ajax()){
            $get=Category::all();
            return DataTables::of($get)
            ->addIndexColumn()
            ->addColumn('action',function($get)use($data){
            $button  ='<div class="d-flex justify-content-center">';
            $button.='<a data-url="'.url('crud_maker/edit').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
            <a data-url="'.url('crud_maker/destroy').'" data-id="'.$get->id.'" data-form="'.$data['form']['name'].'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
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
        
        return view('crud_maker.crud_maker',compact('data')) ;
    }
}