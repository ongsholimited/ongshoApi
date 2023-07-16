<?php

namespace App\Http\Controllers\Admin\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use Validator;
use DataTables;
use Auth;
use DB;
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

        if(request()->ajax()){
            $get=CourseCategory::all();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('category.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('category.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
          ->rawColumns(['action'])->make(true);
        }
        return view('institute.category.category');
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
            'name'=>"required|max:200|min:1|unique:institute.course_categories,name",
        ]);
        if($validator->passes()){
            $cat=new CourseCategory;
            $cat->name=$request->name;
            $cat->author_id=auth()->user()->id;
            $cat->slug=Str::slug($request->name);
            $cat->status=1;
            $cat->save();
            if ($cat) {
                return response()->json(['message'=>'Course Category Added Success']);
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
        return response()->json(CourseCategory::find($id));
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
            'name'=>"required|max:200|min:1|unique:institute.course_categories,name,".$id,
        ]);
        if($validator->passes()){
            $cat=CourseCategory::find($id);
            $cat->name=$request->name;
            $cat->slug=Str::slug($request->name);
            $cat->author_id=auth()->user()->id;
            $cat->status=1;
            $cat->save();
            if ($cat) {
                return response()->json(['message'=>'Course Category Updated Success']);
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
        $delete=CourseCategory::where('id',$id)->delete();
        if ($delete) {
            return response()->json(['message'=>'Course Category Deleted Success']);
        }else{
            return response()->json(['warning'=>'Something Wrong !']);
        }
    }

    public function getCategory(Request $r){
        $data = DB::connection('institute')->select("SELECT id,name from course_categories where name like :key limit 10",['key'=>'%'.$r->searchTerm.'%']);
            foreach ($data as $value) {
                $set_data[] = ['id' => $value->id, 'text' => $value->name];
            }
            return $set_data;
    }
}
