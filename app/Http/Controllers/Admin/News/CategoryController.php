<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Category;
use App\Models\News\Slug;
use DataTables;
use DB;
use Validator;
use Str;
class CategoryController extends Controller
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
        return view('news.category.category');
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
            'parent_category'=>"nullable|max:200|min:1",
            'name'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            DB::transaction(function() use($request){
                if($request->parent_category=='null'){
                    $category=Category::create([
                        'name'=>$request->name,
                        'slug'=>Str::slug($request->name,'-'),
                        'author_id'=>auth()->user()->id,
                        'status'=>1,
                    ]);
                }else{
                    $category=Category::create([
                        'name'=>$request->name,
                        'slug'=>Str::slug($request->name,'-'),
                        'parent_id'=>$request->parent_category,
                        'author_id'=>auth()->user()->id,
                        'status'=>1
                    ]);
                }
                Slug::create([
                    'slug_name'=>Str::slug($request->name,'-'),
                    'slug_type'=>'category',
                    'category_id'=>$category->id,
                    'status'=>1,
                ]);
                return response()->json(['message'=>'Course Category Added Success']);
            });
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
        return response()->json(Category::find($id));
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
            'parent_category'=>"nullable|max:200|min:1",
            'name'=>"required|max:200|min:1",
        ]);
        if($validator->passes()){
            if($request->parent_category=='null'){
                $cat=new Category;
                $cat->name=$request->name;
                $cat->slug=Str::slug($request->name,'-');
                $cat->author_id=auth()->user()->id;
                $cat->status=1;
                $cat->save();
            }else{
                $cat=new Category;
                $cat->name=$request->name;
                $cat->parent_id=$request->parent_category;
                $cat->author_id=auth()->user()->id;
                $cat->status=1;
                $cat->save();
            }
            
            if ($cat) {
                return response()->json(['message'=>'Course Category Added Success']);
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

    public function getCategory(Request $request){
       $search_term='%'.$request->searchTerm.'%';

        $data=DB::connection('ongsho_news')->select("
        SELECT * FROM categories where name like :search_term and parent_id is null order by id limit 25
        ",['search_term'=>$search_term]);

        foreach($data as $dat){
            $category[]=['text'=>$dat->name,'id'=>$dat->id];
        }
        if(isset($category)){
            return $category;
        }
        return [];
    }
}