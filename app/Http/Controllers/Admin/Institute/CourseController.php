<?php

namespace App\Http\Controllers\Admin\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Validator;
use DataTables;
use DB;
use Str;
class CourseController extends Controller
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
            $get=Course::with('user','category')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
              $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('course.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
              <a data-url="'.route('course.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
              $button.='</div>';
            return $button;
          })
            ->addColumn('category',function($get){
            return $get->category->name;
            })
            ->addColumn('user',function($get){
                return $get->user->first_name.' '.$get->user->last_name;
            })
            ->addColumn('thumbnail',function($get){
            return "<img style='width:80px;height:60px;' src='".asset('storage/thumbnail').'/'.$get->thumbnail."'>";
            })
          ->rawColumns(['action','thumbnail'])->make(true);
        }
        return view('institute.course.course');
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
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'category'=>"required|max:200|min:1",
            'title'=>"required|max:200|min:1",
            'description'=>"required|max:200|min:1",
            'thumbnail'=>"required|max:2048|mimes:jpg,jpeg,png",
            'slug'=>"required|max:200|unique:courses,slug|regex:^[a-zA-Z0-9_-]+$",
        ]);
        if($validator->passes()){
            $course=new Course;
            $course->title=$request->title;
            $course->description=$request->description;
            $course->user_id=$request->user;
            $course->category_id=$request->category;
            $course->author_id=auth()->user()->id;
            $course->slug=$request->slug;
            $course->status=1;
            if ($request->hasFile('thumbnail')) {
                $ext = $request->thumbnail->getClientOriginalExtension();
                $name = auth()->user()->id .'_'. time() . '.' . $ext;
                $request->thumbnail->storeAs('public/thumbnail', $name);
                $course->thumbnail = $name;
            }
            $course->save();
            if ($course) {
                return response()->json(['message'=>'Course Added Success']);
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
        return response()->json(Course::with('category','user')->find($id));
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
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'category'=>"required|max:200|min:1",
            'title'=>"required|max:200|min:1",
            'description'=>"required|max:200|min:1",
            'thumbnail'=>"nullable|max:2048|mimes:jpg,jpeg,png",
        ]);
        if($validator->passes()){
            $course=Course::find($id);
            $course->title=$request->title;
            $course->description=$request->description;
            $course->user_id=$request->user;
            $course->category_id=$request->category;
            $course->author_id=auth()->user()->id;
            $course->slug=Str::slug($request->title,'-');
            $course->status=1;
            if ($request->hasFile('thumbnail')){
                if($course->thumbnail!=null){
                    unlink(storage_path('app/public/thumbnail/'.$course->thumbnail));
                }
                $ext = $request->thumbnail->getClientOriginalExtension();
                $name = auth()->user()->id .'_'. time() . '.' . $ext;
                $request->thumbnail->storeAs('public/thumbnail', $name);
                $course->thumbnail = $name;
            }
            $course->save();
            if ($course) {
                return response()->json(['message'=>'Course Added Success']);
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
        $data=Course::find($id);
        if($data->thumbnail!=null){
            unlink(storage_path('app/public/thumbnail/'.$data->thumbnail));
        }
        $delete=Course::where('id',$id)->delete();
        if ($delete) {
            return response()->json(['message'=>'Course Deleted Success']);
        }else{
            return response()->json(['warning'=>'Something Wrong !']);
        }
    }

    public function getCourse(Request $r)
    {
        $data = DB::connection('institute')->select("SELECT id,title from courses where title like :key limit 10",['key'=>'%'.$r->searchTerm.'%']);
            foreach ($data as $value) {
                $set_data[] = ['id' => $value->id, 'text' => $value->title];
            }
            return $set_data;
    }


}
