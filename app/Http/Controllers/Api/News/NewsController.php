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
use App\Models\News\HomeSection;
use App\Rules\PostStatusRule;
use DB;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $post;
    public function __construct() {
        $this->middleware('auth:api')->only(['store','destroy','update']);
    }
    public function index()
    {
        $post=Post::with(['categories.category','author.details'=>function($query){
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
        //  return $request->all();
            if($request->status==Constant::POST_STATUS['review']){
                $isRequired='required';
            }else{
                $isRequired='nullable';
            }   
            $validator=Validator::make($request->all(),[
            'feature_image'=>$isRequired."|max:250|min:1",
            'category'=>$isRequired."|array",
            'category.*'=>$isRequired."|regex:/^([0-9]+)$/",
            'title'=>"required|max:250|min:1",
            'meta_description'=>$isRequired."|max:250|min:1",
            'content'=>$isRequired."|max:60000|min:1",
            'focus_keyword'=>"nullable|max:500|min:1",
            'slug'=>"required|max:250|min:1",
            'status'=>['required','max:250','min:1',new PostStatusRule],
            'post_type'=>"required|max:250|min:1",
            'date'=>"required|max:30",
            'is_scheduled'=>"required|numeric|min:0|max:1",
        ]);
        if($validator->passes()){
            DB::transaction(function() use($request){
                $existed_slug=Post::where('slug','like',$request->slug.'%')->count();
                
                $post=Post::create([
                    'title'=>$request->title,
                    'meta_description'=>$request->meta_description,
                    'content'=>$request->content,
                    'focus_keyword'=>$request->focus_keyword,
                    'slug'=>Str::slug($request->slug,'-').($existed_slug>0? '-'.($existed_slug+1):''),
                    'date'=>(isset($request->date) ? strtotime($request->date) : strtotime(date('d-m-Y h:i:s'))  ),
                    'status'=>$request->status,
                    'feature_image'=>isset($request->feature_image)  ? $request->feature_image : 'no-image.jpg' ,
                    'post_type'=>$request->post_type,
                    'is_scheduled'=>$request->is_scheduled,
                ]);
                $this->post=$post;
                Slug::create([
                    'slug_name'=> Str::slug($request->slug,'-').($existed_slug>0? '-'.($existed_slug+1):''),
                    'slug_type'=> 'post',
                    'post_id'=> $post->id,
                ]);
                if(isset($request->category)>0){
                    for($i=0;$i<count($request->category);$i++){
                        PostHasCategory::create([
                            'post_id'=>$post->id,
                            'category_id'=>$request->category[$i],
                        ]);
                    }
                }   
                PostHasAuthor::create([
                    'post_id'=> $post->id,
                    'author_id'=> Auth::user()->id,
                ]);
            });
            
            return response()->json(['status'=>true,'message'=>'Post Added Success','post_id'=>$this->post->id,'slug'=>$this->post->slug]);
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
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json();
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
        //  return $request->all();
        if($request->status==Constant::POST_STATUS['review']){
            $isRequired='required';
        }else{
            $isRequired='nullable';
        } 
        $validator=Validator::make($request->all(),[
            'feature_image'=>$isRequired."|max:250|min:1",
            'category'=>$isRequired."|array",
            'category.*'=>$isRequired."|regex:/^([0-9]+)$/",
            'title'=>"required|max:250|min:1",
            'meta_description'=>$isRequired."|max:250|min:1",
            'content'=>$isRequired."|max:60000|min:1",
            'focus_keyword'=>"nullable|max:500|min:1",
            'slug'=>"required|max:250|min:1",
            'status'=>['required','max:250','min:1',new PostStatusRule],
            'post_type'=>"required|max:250|min:1",
            'date'=>"required|max:30",
            'is_scheduled'=>"required|numeric|min:0|max:1",
        ]);
        if($validator->passes()){

            DB::transaction(function() use($request,$id){
                $existed_slug=Post::where('slug','like',$request->slug.'%')->count();
              
                $post=Post::where('id',$id)->update([
                    'title'=>$request->title,
                    'meta_description'=>$request->meta_description,
                    'content'=>$request->content,
                    'focus_keyword'=>$request->focus_keyword,
                    'slug'=>Str::slug($request->slug,'-').($existed_slug>0? '-'.($existed_slug+1):''),
                    'date'=>(isset($request->date) ? strtotime($request->date) : strtotime(date('d-m-Y h:i:s'))  ),
                    'status'=>$request->status,
                    'feature_image'=>isset($request->feature_image)  ? $request->feature_image : 'no-image.jpg' ,
                    'post_type'=>$request->post_type,
                    'is_scheduled'=>$request->is_scheduled,
                ]);
                
                Slug::where('post_id',$id)->update([
                    'slug_name'=> Str::slug($request->slug,'-').($existed_slug>0? '-'.($existed_slug+1):''),
                    'slug_type'=> 'post',
                    'post_id'=> $id,
                ]);
                if(isset($request->category)>0){
                    PostHasCategory::where('post_id',$post->id)->delete();
                    for($i=0;$i<count($request->category);$i++){
                        PostHasCategory::create([
                            'post_id'=>$id,
                            'category_id'=>$request->category[$i],
                        ]);
                    }
                }   
                PostHasAuthor::where('post_id',$id)->where('author_id',Auth::user()->id)->update([
                    'post_id'=> $id,
                    'author_id'=> Auth::user()->id,
                ]);
            });
                return response()->json(['status'=>true,'message'=>'Post Updated Success']);
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
        $post=PostHasAuthor::where('post_id',$id)->where('author_id',Auth::user()->id)->count();
        if($post>0){
            $del=Post::where('id',$id)->update(['status'=>Constant::POST_STATUS['deleted']]);
            if($del){
                return response()->json(['status'=>true,'message'=>'Post Deleted Success']);
            }
        }
        return response()->json(['status'=>false,'error'=>'Something Went Wrong']);

    }
    public function getPostByCat(Request $request,$category_slug){
        
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:0|max:50",
            'offset'=>"required|numeric|min:0|max:50",
        ]);
        if($validator->passes()){
            // return 'xx';
            $post=Category::with(['post'=>function($q) use($request){
                $q->with('author.details.badges')->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->skip($request->offset)->take($request->limit)->orderBy('date','desc');
            }])->whereHas('post')->where('slug',$category_slug)->first();
            
            // where('category_id',$category->id)->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
            return response()->json($post);
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
    public function getPostByUser(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0|max:50",
        ]);
        if($validator->passes()){
            $post=PostHasAuthor::with('post.categories.category','details')->whereHas('post',function($q){
                $q->where('status','!=',Constant::POST_STATUS['deleted'])->orderBy('id','desc');
            })->where('author_id',Auth::user()->id)->skip($request->offset)->take($request->limit)->get();
            return response()->json($post);
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
    public function getPost(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0|max:50",
        ]);
        if($validator->passes()){
            $post=Post::with(['categories.category','author.details'=>function($query){
                $query->with('badges');
            }])->skip($request->offset)->take($request->limit)->where('date','<',time())->where('status',Constant::POST_STATUS['public'])->orderBy('id','desc')->get();
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
                $post=Post::with('author.details.badges','categories.category')->where('slug',$get_slug->slug_name)->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->first();
                $data= ['status'=>($post!=null? true :false ),'slug_type'=>$get_slug->slug_type,'data'=>$post];
                break;
            case $get_slug->slug_type=='category':
                
                $post=Category::with(['post'=>function($q){
                    $q->with('author.details.badges')->where('date','<',time())->where('status',Constant::POST_STATUS['public'])->take(20);
                }])->where('slug',$get_slug->slug_name)->get();
                $data= ['status'=>($post!=null? true :false ),'slug_type'=>$get_slug->slug_type,'data'=>$post];
            break;
            default:
                $data= ['status'=>false,'message'=>'data not found'];
                break;
        }
        return response()->json($data);
    }
    public function getSection(Request $request,$serial){
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0|max:50",
        ]);
        if($validator->passes()){
            // return 'xx';
            $post=HomeSection::with(['post'=>function($query) use ($request){
                    $query->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->take($request->limit)->skip($request->offset)->orderBy('date','desc');
                },'post.author.details.badges'])->whereHas('post')->where('serial',$serial)->first();
            return response()->json($post);
        }
        return response()->json(['status'=>false,'error'=>'something went wrong']);
    }
    public function getPinPost(Request $request){
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0|max:50",
        ]);
        if($validator->passes()){
            $post=Post::with(['categories.category','author.details'=>function($query){
                $query->with('badges');
            }])->where('post_type',Constant::POST_TYPE['pinned_post'])->where('status',Constant::POST_STATUS['public'])->skip($request->offset)->take($request->limit)->where('date','<',time())->orderBy('id','desc')->get();
            return response()->json($post);
            
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
    public function getPostPreview($id){
        
        $post=Post::with(['categories.category','author.details'=>function($query){
                $query->with('badges');
            }])->where('status','!=',Constant::POST_STATUS['deleted'])->where('id',$id)->first();
        
        return response()->json($post);
        
    }
}