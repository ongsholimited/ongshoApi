<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Menu;
use DataTables;
use Validator;
use Str;
class MenuController extends Controller
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
        // return $get=Category::with('parent')->get();
        if(request()->ajax()){
            $get=Menu::with('category');
            return DataTables::eloquent($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('news.menu.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('news.menu.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->addColumn('category',function($get){
          return $get->category->name;
        })
        ->rawColumns(['action'])->make(true);
        }
        return view('news.menu.menu');
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
            'name'=>"required|max:200|min:1|unique:ongsho_news.menus,name",
            'serial'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
           
            $menu=new Menu;
            $menu->name=$request->name;
            $menu->slug=Str::slug($request->name,'-');
            $menu->serial=$request->serial;
            $menu->category_id=$request->category;
            $menu->author_id=auth()->user()->id;
            $menu->status=1;
            $menu->save();
            if ($menu) {
                return response()->json(['message'=>'Menu Added Success']);
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
        return response()->json(Menu::with('category')->find($id));
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
            'name'=>"required|max:200|min:1|unique:ongsho_news.menus,name,".$id,
            'serial'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            $menu=Menu::find($id);
            $menu->name=$request->name;
            $menu->slug=Str::slug($request->name,'-');
            $menu->serial=$request->serial;
            $menu->category_id=$request->category;
            $menu->author_id=auth()->user()->id;
            $menu->status=1;
            $menu->save();
            if ($menu) {
                return response()->json(['message'=>'Menu Updated Success']);
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
        $menu=Menu::find($id);
        $menu->delete();
        if($menu){
            return response()->json(['status'=>true,'message'=>"Menu Deleted Success"]);
        }
    }
}
