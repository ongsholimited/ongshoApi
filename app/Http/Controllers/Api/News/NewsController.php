<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Post;
use Storage;
use Validator;
use Auth;
use Str;
use App\Models\News\Category;
use App\Models\News\PostHasCategory;
use App\Models\News\PostHasAuthor;
use App\Models\News\Slug;
use App\Helpers\Constant;
use DB;
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
        $post=Post::with(['categories.category','author.user'=>function($query){
          $query->with('badges')->select('id','first_name','last_name','photo');  
        }])->take(20)->get();
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
         return $request->all();

         $validator=Validator::make($request->all(),[
            'feature_image'=>"required|mimes:jpg,jpeg,png,gif|max:2048",
            'category'=>"required|array",
            'category.*'=>"required|regex:/^([0-9]+)$/",
            'title'=>"required|max:250|min:1",
            'meta_description'=>"required|max:250|min:1",
            'content'=>"required|max:5000|min:1",
            'focus_keyword'=>"required|max:500|min:1",
            'slug'=>"required|max:250|min:1|unique:ongsho_news.posts,slug",
        ]);
        if($validator->passes()){
            DB::transaction(function () {
   
            });
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
                $post->status=Constant::POST_STATUS['review'];
                if($request->hasFile('feature_image')){
                    $img=$request->feature_image;
                    $ext= $img->getClientOriginalExtension();
                    $f_name=time().'_'.date('d_m_Y');
                    Storage::putFileAs('public/post_images',$img,$f_name.'.'.$ext);
                    $post->feature_image=$f_name.'.'.$ext;
                }
                $post->save();
            if ($post) {
                $slug=new Slug;
                $slug->slug_name= Str::slug($request->title,'-');
                $slug->author_id= Auth::user()->id;
                $slug->post_id= $post->id;
                $slug->save();
                for($i=0;count($request->category);$i++){
                    $postHasCat=new PostHasCategory;
                    $postHasCat->post_id=$post->id;
                    $postHasCat->category_id=$request->category[$i];
                    $postHasCat->save();
                }
                $author=new PostHasAuthor;
                $author->post_id= $post->id;
                $author->author_id= Auth::user()->id;
                $author->save();
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
    public function getPostByCat(Request $request,$category_slug){
        
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:1|max:50",
        ]);
        
        if($validator->passes()){
            $category=Category::where('slug',$category_slug)->first();
            if($category==null){
                return response()->json(['status'=>false,'message'=>'data not found']);
            }
            $post=Post::with(['categories.category','author.user'=>function(){
                
            }])->where('category_id',$category->id)->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
            return response()->json($post);
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
    public function getPost(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:1|max:50",
        ]);
        
        if($validator->passes()){
            $post=Post::with('categories.category','author')->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
            return response()->json($post);
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
    public function getPostBySlug($slug=null)
    {
        $get_slug=Slug::where('slug_name',$slug)->first();
        switch ($get_slug) {
            case null:
              $data= ['status'=>false,'message'=>'data not found'];
                break;
            case $get_slug->slug_type=='post':
                $post=Post::with('author','categories.category')->where('slug',$get_slug->slug_name)->first();
                $data= ['status'=>true,'data'=>$post];
                break;
            case $get_slug->slug_type=='category':
                $post=Post::with('author','categories.category')->where('categories',function($query) use ($get_slug){
                    return $query->where('slug',$get_slug->slug_name);
                })->take(20)->get();
                $data= ['status'=>true,'data'=>$post];
                    break;
            default:
                # code...
                break;
        }
        return response()->json($data);
    }
}