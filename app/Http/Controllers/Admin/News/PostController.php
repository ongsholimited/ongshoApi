<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\News\Post;
use App\Helpers\Constant;
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
                            <span class="dropdown-item dropdown-header"><a href='.route('news.post.show',$get->id).'>Preview</a></span>
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
      ->addColumn('status',function($get){
        $arr=array_flip(Constant::POST_STATUS);
        return '<b style="background-color:pink">'.str_replace('_',' ',ucfirst($arr[$get->status])).'</b>';
       })
        ->rawColumns(['action','status'])->make(true);
      }
      return view('news.post.post_list');
    }
    public function reviewList()
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
                            <span class="dropdown-item dropdown-header"><a href='.route('news.post.show',$get->id).'>Preview</a></span>
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
      ->addColumn('status',function($get){
        $arr=array_flip(Constant::POST_STATUS);
        return '<b style="background-color:pink">'.str_replace('_',' ',ucfirst($arr[$get->status])).'</b>';
       })
        ->rawColumns(['action','status'])->make(true);
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
       return $request->all();
       if($request->status==Constant::POST_STATUS['public']){
        $isRequired='required';
        }else{
            $isRequired='nullable';
        }
        $data=$request->all();
        $data['category']=explode(',',$request->category);
        $data['author']=explode(',',$request->author);
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
        
        return response()->json(['status'=>true,'message'=>'Post Added Success']);
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
        $post=$post=Post::with(['categories.category','author.details'=>function($query){
            $query->with('badges');
        }])->where('id',$id)->first();
        return view('news.post_preview.post_preview',compact('post'));
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