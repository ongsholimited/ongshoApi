<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Post;
use Storage;
use Validator;
use Auth;
use Str;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth:api')->only(['store','destroy','update']);
    }
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
            'content'=>"required|max:5000|min:1",
            'tags'=>"required|max:500|min:1",
            'slug'=>"required|max:250|min:1",
        ]);
        if($validator->passes()){
                $existed_slug=Post::where('slug','like',$request->slug.'%')->count();
                $post=new Post;
                $post->category_id=$request->category;
                $post->title=$request->title;
                $post->short_description=$request->short_description;
                $post->content=$request->content;
                $post->tags=$request->tags;
                $post->date=strtotime(date('d-m-Y'));
                $post->slug=Str::slug($request->slug,'-').($existed_slug>0? '-'.($existed_slug+1):'');
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
                return response()->json(['status'=>true,'message'=>'Post Added Success']);
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
            'feature_image'=>"required|mimes:jpg,jpeg,png,gif|max:2048",
            'category'=>"required|max:20|min:1",
            'title'=>"required|max:250|min:1",
            'short_description'=>"required|max:250|min:1",
            'content'=>"required|max:5000|min:1",
            'tags'=>"required|max:500|min:1",
            'slug'=>"required|max:250|min:1|unique:ongsho_news.posts,slug,".$id,
        ]);
        if($validator->passes()){
                $post=Post::find($id);
                $post->category_id=$request->category;
                $post->title=$request->title;
                $post->short_description=$request->short_description;
                $post->content=$request->content;
                $post->tags=$request->tags;
                $post->date=strtotime(date('d-m-Y'));
                $post->slug=Str::slug($request->slug,'-');
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
                return response()->json(['status'=>true,'message'=>'Post Added Success']);
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
        $post=Post::where('id',$id)->where('author_id',Auth::user()->id)->first();
        if(isset($post->feature_image) and $post->feature_image!=null){
            unlink(storage_path('app/public/post_images/'.$post->feature_image));
        }
        $post->delete();
        if($post){
            return response()->json(['status'=>true,'message'=>'Post Deleted Success']);
        }
        return response()->json(['status'=>false,'error'=>'Something Went Wrong']);

    }
    public function getPostByCat($category_id){
        
    }
    public function getPost($limit=10,$offset=0)
    {
        if($limit<=200){
            $post=Post::with('category','author')->skip($offset)->take($limit)->orderBy('id','desc')->get();
            return response()->json($post);
        }else{
            return response()->json(['status'=>false,'error'=>'data limit exceeded']);
        }
    }
}
