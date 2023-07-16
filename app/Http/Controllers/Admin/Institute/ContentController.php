<?php

namespace App\Http\Controllers\Admin\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use DataTables;
use Storage;
use Validator;
class ContentController extends Controller
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
            $get=Content::with('course','chapter')->get();
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('course',function($get){
              return $get->course->title;
            })
            ->addColumn('chapter',function($get){
                return $get->chapter->name;
              })
            ->addColumn('action',function($get){
                $button  ='<div class="d-flex justify-content-center">';
                $button.='<a data-url="'.route('chapter.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
                <a data-url="'.route('chapter.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
                $button.='</div>';
            return $button;
            })
            ->rawColumns(['action'])->make(true);
        }
        return view('institute.content.content');
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
        // return response()->json($request->all());
        $validator=Validator::make($request->all(),[
            'course'=>"required|max:200|min:1",
            'user'=>"required|max:200|min:1",
            'chapter'=>"required|max:200|min:1",
            'title'=>"required|max:200|min:1",
            'video'=>"required|max:512000|mimes:mp4",
        ]);
        if($validator->passes()){
            if ($request->hasFile('video')) {
                $ext = $request->video->getClientOriginalExtension();
                $video_name = auth()->user()->id .'_'. time() . '.' . $ext;
                $upload=Storage::disk('gcs')->put('videos/'.$video_name,file_get_contents($request->video));
            }
            if($upload){
                $course=new Content;
                $course->course_id=$request->course;
                $course->chapter_id=$request->chapter;
                $course->title=$request->title;
                $course->video_url=$video_name;
                $course->user_id=$request->user;
                $course->save();
                if ($course) {
                    return response()->json(['message'=>'Content Added Success']);
                }
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
        //
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
