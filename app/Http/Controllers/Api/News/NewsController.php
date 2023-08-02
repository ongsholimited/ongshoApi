<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Post;
use Storage;
use Validator;
use Auth;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post=Post::with('category','author')->take(20)->get();
        return response()->json($post);
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
            'feature_image'=>"required|mimes:jpg,jpeg,png,gif|max:2048",
            'category'=>"required|max:20|min:1",
            'title'=>"required|max:250|min:1",
            'short_description'=>"required|max:250|min:1",
            'content'=>"required|max:1000|min:1",
            'tags'=>"required|max:500|min:1",
            'slug'=>"required|max:250|min:1",
        ]);
        if($validator->passes()){
                
                $post=new Post;
                $post->category_id=$request->category;
                $post->title=$request->title;
                $post->short_description=$request->short_description;
                $post->content=$request->content;
                $post->tags=$request->tags;
                $post->date=strtotime(date('d-m-Y'));
                $post->author_id=Auth::user()->id;
                $post->status=1;
                if($request->hasFile('feature_image')){
                    $img=$request->feature_image;
                    $ext= $img->getClientOriginalExtension();
                    $f_name=time().'_'.date('d_m_Y');
                   Storage::putFileAs('public/post_images',$img,$f_name.'.'.$ext);
                   $post->feature_image=$f_name.'.'.$ext;
                }
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
    public function getPostByCat($category_id){
        $post=Post::where('category_id',$category_id)->get();
        return response()->json($post);
    }
}
