<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News\HomeSection;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use Validator;
use Str;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:admin');   
    }
    public function index()
    {
        if(request()->ajax()){
            $get=HomeSection::with('category')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('news.section.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('news.section.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('category',function($get){
          return $get->category->name;
        })
        ->rawColumns(['action','category'])->make(true);
        }
        return view('news.section.section');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'category'=>"required|max:20|min:1",
            'name'=>"required|max:200|min:1|unique:ongsho_news.home_sections,name",
            'serial'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
           
            $menu=new HomeSection();
            $menu->name=$request->name;
            $menu->serial=$request->serial;
            $menu->category_id=$request->category;
            $menu->author_id=auth()->user()->id;
            $menu->status=1;
            $menu->save();
            if ($menu) {
                return response()->json(['message'=>'Section Added Success']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'category'=>"required|max:20|min:1",
            'name'=>"required|max:200|min:1|unique:ongsho_news.menus,name",
            'serial'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
           
            $menu=HomeSection::find($id);
            $menu->name=$request->name;
            $menu->serial=$request->serial;
            $menu->category_id=$request->category;
            $menu->author_id=auth()->user()->id;
            $menu->status=1;
            $menu->save();
            if ($menu) {
                return response()->json(['message'=>'Section Updated Success']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}