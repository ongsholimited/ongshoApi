<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\News\Image;
use Storage;
use Str;
use Auth;
use App\Rules\AuthorValidation;
use App\Http\Traits\SendDataApi;
class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        return $this->middleware('auth:api');
    }
    public function index()
    {
        $image=Image::where('author_id',Auth::user()->id)->get();
        if($image->count()>0){
            return SendDataApi::bind($image);
        }
        return SendDataApi::bind('data not found',404);
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
       
        // return $request->file('images')->getSize();
        $validator=Validator::make($request->all(),[
            "images"=>"required|max:2048|mimes:jpg,png,gif,jpeg,webp|dimensions:min_width=960,min_height=540",
            "folder"=>"nullable|max:20",
            // "size"=>"required|max:20",
            "alt"=>"nullable|max:150",
            "title"=>"nullable|max:150",
            "caption"=>"nullable|max:150",
        ],[
            'images.max'=>'file size limit exceeded max size 2MB',
            'images.dimensions'=>'the image dimension required minimum 960x540px'
        ]);
        if($validator->passes()){
               $microtime=explode(' ',microtime(false));
               $img=$request->images;
               $ext= $img->getClientOriginalExtension();
               $f_name=str_replace($ext,'',Str::slug($img->getClientOriginalName(),'-')).'_'.$microtime[1]+str_replace('.','',$microtime[0]);
               $image=new Image;
               $image->name=$f_name.'.'.$ext;
               $image->size=$request->file('images')->getSize();
               $image->alt=$request->alt;
               $image->title=$request->title;
               $image->caption=$request->caption;
               $image->folder_id=$request->folder;
               $image->author_id=auth()->user()->id;
               $image->save();
               if($image){
                   $up=  Storage::putFileAs('public/media/images/news/',$img,$f_name.'.'.$ext);
                   info($up);
                   return SendDataApi::bind(['message'=>'Photo Uploaded Success','image'=>$f_name.'.'.$ext]);
                }
        }
        return SendDataApi::bind($validator->getMessagebag(),403);
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
        return SendDataApi::bind(Image::find($id));
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
            "alt"=>["nullable","max:140",new AuthorValidation($id)],
            "title"=>"nullable|max:140",
            "caption"=>"nullable|max:140",
        ]);
        if($validator->passes()){
               $image=Image::find($id);
               $image->alt=$request->alt;
               $image->title=$request->title;
               $image->caption=$request->caption;
               $image->save();
               if($image){
                   return SendDataApi::bind(['message'=>'Photo Updated Success']);
                }
        }
        return SendDataApi::bind($validator->getMessagebag());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image=Image::where('id',$id)->where('author_id',Auth::user()->id)->first();
        if($image!=null){
            unlink(storage_path('app/public/media/images/news/'.$image->name));
            $image_del=$image->delete();
        }
        if(isset($image_del)){
            return SendDataApi::bind(['message'=>'Image Deleted Success']);
        }
        return SendDataApi::bind('bad request',400);

    }  
    public function getImageByFolderId($folder_id){
        $image=Image::where('folder_id',$folder_id)->get();
        return SendDataApi::bind($image);
    }
    public function getImage(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0",
        ]);
        if($validator->passes()){
            $counter=Image::where('author_id',Auth::user()->id)->count();
            $image=Image::where('author_id',Auth::user()->id)->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
            if($image->count()>0){
                return SendDataApi::bind(['data'=>$image,'count'=>$counter],200);
            }
            return SendDataApi::bind('data not found',404);
        }
        return SendDataApi::bind($validator->getMessageBag(),403);
    }
}