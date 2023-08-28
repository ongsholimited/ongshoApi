<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\News\Post;
class PostController extends Controller
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
    public function postList()
    {
      if(request()->ajax()){
        $get=Post::with('categories','author.details')->get();
        return DataTables::of($get)
          ->addIndexColumn()
          ->addColumn('action',function($get){
          // $button  ='<div class="d-flex justify-content-center">';
          // $button.='<a data-url="'.route('category.edit',$get->id).'"  href="javascript:void(0)" class="btn btn-primary shadow btn-xs sharp me-1 editRow"><i class="fas fa-pencil-alt"></i></a>
          // <a data-url="'.route('category.destroy',$get->id).'" href="javascript:void(0)" class="btn btn-danger shadow btn-xs sharp ml-1 deleteRow"><i class="fa fa-trash"></i></a>';
          $button='<span class="nav-item dropdown"><a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                        <div class="dropdown-menu dropdown-menu p-0 dropdown-menu-right" style="left: inherit; right: 0px;">
                            <span class="dropdown-item dropdown-header">Preview</span>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-item dropdown-header">Edit</span>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-item dropdown-header">Reject</span>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-item dropdown-header">Aprove</span>
                            <div class="dropdown-divider"></div>
                        </div>';
                        
         $button.='</span>';
          
        return $button;
      })
      ->addColumn('author',function($get){
       return isset($get->author[0])?  $get->author[0]->details->first_name.' '.$get->author[0]->details->last_name : '';
      })
        ->rawColumns(['action'])->make(true);
      }
      return view('news.post.post_list');
    }
    public function index()
    {
        return view('news.post.post');
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
            'category'=>"required|max:20|min:1",
            'title'=>"required|max:250|min:1",
            'short_description'=>"required|max:250|min:1",
            'content'=>"required|max:1000|min:1",
            'tags'=>"required|max:250|min:1",
        ]);
        if($validator->passes()){
                $post=new Post;
                $post->category_id=$request->category;
                $post->title=$request->title;
                $post->short_description=$request->short_description;
                $post->content=$request->content;
                $post->tags=$request->tags;
                $post->date=strtotime(date('d-m-Y'));
                $post->author_id=auth()->user()->id;
                $post->status=1;
                $post->save();
            if ($post) {
                return response()->json(['message'=>'Post Added Success']);
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