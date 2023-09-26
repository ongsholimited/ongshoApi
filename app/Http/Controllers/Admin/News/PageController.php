<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Post;
use App\Helpers\Constant;
use App\Rules\PostStatusRule;
use App\Models\News\PostHasAuthor;
use App\Models\News\PostHasCategory;
use App\Models\News\Slug;
use App\Http\Traits\SlugableTrait;
use DB;
use Str;
use Auth;
class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
         //    return $request->all();
       if($request->status==Constant::POST_STATUS['public']){
        $isRequired='required';
        }else{
            $isRequired='nullable';
        }
        $data=$request->all();
        $data['category']=array_filter(explode(',',$request->category));
        $data['category_delete']=array_filter(explode(',',$request->category_delete));
        $data['author']=array_filter(explode(',',$request->author));
        $data['author_delete']=array_filter(explode(',',$request->author_delete));
        $slug=Slug::where('post_id',$id)->first();
        // return $data;
        $validator=Validator::make($data,[
        'feature_image'=>$isRequired."|max:250|min:1",
        'category'=>$isRequired."|array",
        'category.*'=>$isRequired."|regex:/^([0-9]+)$/",
        'title'=>"required|max:250|min:1",
        'meta_description'=>$isRequired."|max:250|min:1",
        'content'=>$isRequired."|max:60000|min:1",
        'focus_keyword'=>"nullable|max:500|min:1",
        'slug'=>"required|max:250|min:1|unique:ongsho_news.slugs,slug_name,".$slug->id,
        'status'=>['required','numeric','max:7','min:0'],
        'post_type'=>"required|max:250|min:1",
        'date'=>"required|max:30",
        'is_scheduled'=>"required|numeric|min:0|max:1",
    ]);

    // return $data;
    if($validator->passes()){
        DB::transaction(function() use($request,$data,$id){
            
            // $existed_slug=Slug::where('slug_name','like',$request->slug.'%')->whereNotIn('post_id',[$id])->count();
            $post_stats=Post::where('id',$id)->first()->status;
            $post=Post::create([
                'title'=>$request->title,
                'meta_description'=>$request->meta_description,
                'content'=>$request->content,
                'focus_keyword'=>$request->focus_keyword,
                'slug'=>SlugableTrait::makeSlug($request->slug,$slug->id),
                'date'=>(isset($request->date) ? strtotime($request->date) : strtotime(date('d-m-Y h:i:s'))  ),
                'status'=>$request->status,
                'is_public'=>(Constant::POST_STATUS['public']==$post_stats or Constant::POST_STATUS['public']==$request->status) ? 1 :0,
                'feature_image'=>isset($request->feature_image)  ? $request->feature_image : 'no-image.jpg' ,
                'post_type'=>$request->post_type,
                'is_scheduled'=>$request->is_scheduled,
            ]);
            // $this->post=$post;
            Slug::where('post_id')->update([
                'slug_name'=> SlugableTrait::makeSlug($request->slug),
                'slug_type'=> 'post',
                'post_id'=> $post->id,
            ]);
        });
        
        return response()->json(['status'=>true,'message'=>'Post Added Success','post'=>$this->post]);
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
