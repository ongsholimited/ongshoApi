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
use App\Models\News\PostView;
use App\Rules\PostStatusRule;
use App\Http\Traits\SendDataApi;
use DB;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $post;
    public $i;
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
            'slug'=>"required|max:250|min:1|unique:ongsho_news.slugs,slug_name",
            'status'=>['required','max:250','min:1',new PostStatusRule],
            'post_type'=>"required|max:250|min:1",
            'date'=>"required|max:30",
            'is_scheduled'=>"required|numeric|min:0|max:1",
        ]);
        if($validator->passes()){
            DB::transaction(function() use($request){
                $existed_slug=Slug::where('slug_name','like',$request->slug.'%')->count();
                
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
            return SendDataApi::bind(['message'=>'Post Added Success','post_id'=>$this->post->id,'slug'=>$this->post->slug]);
            }
            return SendDataApi::bind($validator->getMessageBag());
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
            'slug'=>"required|max:250|min:1|unique:ongsho_news.slugs,slug_name,".$id.",post_id",
            'status'=>['required','max:250','min:1',new PostStatusRule],
            'post_type'=>"required|max:250|min:1",
            'date'=>"required|max:30",
            'is_scheduled'=>"required|numeric|min:0|max:1",
        ]);
        if($validator->passes()){

            DB::transaction(function() use($request,$id){
                $existed_slug=Slug::where('slug_name','like',$request->slug.'%')->whereNotIn('post_id',[$id])->count();
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
                    for($i=0;$i<count($request->category);$i++){
                       $cat_exist= PostHasCategory::where('post_id',$id)->where('category_id',$request->category[$i])->count();
                       if($cat_exist<1){
                            PostHasCategory::create([
                                'post_id'=>$id,
                                'category_id'=>$request->category[$i],
                            ]);
                        }
                    }
                    PostHasCategory::where('post_id',$id)->whereNotIn('category_id',$request->category)->delete();
                }
                $auth_exist= PostHasAuthor::where('post_id',$id)->where('author_id',Auth::user()->id)->count();
                if($auth_exist<1){
                    PostHasAuthor::create([
                        'post_id'=> $id,
                        'author_id'=> Auth::user()->id,
                    ]);
                }
                    
            });
                return SendDataApi::bind(['message'=>'Post Updated Success'],200);
            }
            return SendDataApi::bind($validator->getMessageBag());
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
        return SendDataApi::bind('failed to destroy',400);
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
            if($post!=null){
                return SendDataApi::bind($post);
            }
            // where('category_id',$category->id)->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
            return SendDataApi::bind('data not found',404);
        }
        return SendDataApi::bind($validator->getMessageBag(),403);
    }
    public function getPostByUser(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0",
        ]);
        if($validator->passes()){
            $counter=DB::connection('ongsho_news')->select("
                select count(post_has_authors.id) count from post_has_authors
                inner join posts on posts.id=post_has_authors.post_id 
                where post_has_authors.author_id=:user_id and posts.status!=:status
            ",['status'=>Constant::POST_STATUS['deleted'],'user_id'=>Auth::user()->id]);
            $post=PostHasAuthor::with('post.categories.category')->whereHas('post',function($q){
                $q->where('status','!=',Constant::POST_STATUS['deleted'])->orderBy('id','desc');
            })->where('author_id',Auth::user()->id)->skip($request->offset)->take($request->limit)->get();
            if($post->count()>0){
                return SendDataApi::bind(['data'=>$post,'count'=>$counter[0]->count]);
            }
            return SendDataApi::bind($post,404);
        }
        return SendDataApi::bind($validator->getMessageBag(),403);
    }
    public function getPost(Request $request)
    {
        
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0",
        ]);
        if($validator->passes()){
            $post=Post::with(['categories.category','author.details'=>function($query){
                $query->with('badges');
            }])->skip($request->offset)->take($request->limit)->where('date','<',time())->where('status',Constant::POST_STATUS['public'])->orderBy('id','desc')->get();
            if($post->count()>0){
                return SendDataApi::bind($post,200);
            }
            return SendDataApi::bind('data not found',200);
        }
        return SendDataApi::bind($validator->getMessageBag(),403);
    }
    public function getPostBySlug($slug=null)
    {
        // return 'xx';
        $get_slug=Slug::where('slug_name',$slug)->first();
        switch ($get_slug) {
            case null:
              $data= SendDataApi::bind('data not found',404);

                break;
            case $get_slug->slug_type=='post':
                $post=Post::with('author.details.badges','categories.category')->where('slug',$get_slug->slug_name)->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->first();
                // PostView::create([
                //     'post_id'=>$post->id,
                //     'ip'=>Request::getClientIp(true),
                // ]);
                if($post!=null){
                    $data=SendDataApi::bind(['slug_type'=>$get_slug->slug_type,'data'=>$post]);
                }else{
                    $data= SendDataApi::bind('data not found',404);
                }
                break;
            case $get_slug->slug_type=='category':
                
                $post=Category::with(['post'=>function($q){
                    $q->with('author.details.badges')->where('date','<',time())->where('status',Constant::POST_STATUS['public'])->take(20);
                }])->where('slug',$get_slug->slug_name)->first();
                if($post!=null){
                    $data= SendDataApi::bind(['slug_type'=>$get_slug->slug_type,'data'=>$post]);
                }else{
                    $data=SendDataApi::bind('data not found',404) ;
                }
            break;
            default:
                $data= SendDataApi::bind('data not found',404);
                break;
        }
        return $data;
    }
    public function getSection(Request $request){
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0",
        ]);
        if($validator->passes()){
            // return 'xx';
            $sections=HomeSection::orderBy('serial','asc')->get();
            $posts=[];
            foreach($sections as $section){
                $post=Category::with(['post'=>function($q)use($section){
                    $q->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->limit($section->limit)->orderBy('date','desc');
                }])->whereHas('post',function($q){
                    $q->where('status',Constant::POST_STATUS['public'])->where('date','<',time());
                })->where('id',$section->category_id)->first();
                
                $posts['section'.$section->serial]=(isset($post->post) ? $post->post : []);
                
                
            }
            return SendDataApi::bind($posts);



            
            
            $post=HomeSection::with(['post'=>function($q)use($request){
                $q->where('status',Constant::POST_STATUS['public'])->where('date','<',time())->limit($request->limit)->offset($request->offset)->orderBy('date','desc');
            },'post.author.details.badges'])->whereHas('post',function($q){
                   $q->where('status',Constant::POST_STATUS['public'])->where('date','<',time());
                })->orderBy('serial','asc')->get();
            if($post->count()>0){
                return SendDataApi::bind($post);
            }
            return SendDataApi::bind('data not found',404);

        }
        return SendDataApi::bind($validator->getMessageBag(),403);
    }
    public function getPinPost(Request $request){
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0",
        ]);
        if($validator->passes()){
            $post=Post::with(['categories.category','author.details'=>function($query){
                $query->with('badges');
            }])->where('post_type',Constant::POST_TYPE['pinned_post'])->where('status',Constant::POST_STATUS['public'])->skip($request->offset)->take($request->limit)->where('date','<',time())->orderBy('id','desc')->get();
            if($post!=null){
                return SendDataApi::bind($post);
            }
            return SendDataApi::bind('data not found',404);
        }
        return SendDataApi::bind($validator->getMessageBag());
    }
    public function getPostPreview($post_slug){
        $post=Post::with(['categories.category','author.details'=>function($query){
                $query->with('badges');
            }])->whereHas('author',function($q){
                $q->where('author_id',Auth::user()->id);
            })->where('status','!=',Constant::POST_STATUS['deleted'])->where('slug',$post_slug)->first();
        if($post!=null){
           return  SendDataApi::bind($post);
        }
        return  SendDataApi::bind('data not found',404);
    }
    public function getPostPreviewEdit($id){
        $post=Post::with(['author.details'=>function($query){
                $query->with('badges');
            }])->whereHas('author',function($q){
                $q->where('author_id',Auth::user()->id);
            })->where('status','!=',Constant::POST_STATUS['deleted'])->where('id',$id)->first();
        if($post!=null){
           return  SendDataApi::bind($post);
        }
        return  SendDataApi::bind('data not found',404);
    }
}