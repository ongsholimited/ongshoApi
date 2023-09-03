<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\News\Image;
use Storage;
use Str;
use Auth;
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
        return response()->json($image);
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
        // return response()->json(['message'=>'Photo Uploaded Success']);
        // return $request->file('images')->getSize();
        $validator=Validator::make($request->all(),[
            "images"=>"required|max:2048|mimes:jpg,png,gif,jpeg",
            "folder"=>"nullable|max:20",
            // "size"=>"required|max:20",
            "alt"=>"nullable|max:20",
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
               $image->folder_id=$request->folder;
               $image->author_id=auth()->user()->id;
               $image->save();
               if($image){
                   Storage::putFileAs('public/media/images/news/',$img,$f_name.'.'.$ext);
                   return response()->json(['message'=>'Photo Uploaded Success','image'=>$f_name.'.'.$ext]);
                }
        }
        return response()->json(['error'=>$validator->getMessagebag()]);
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
            "alt"=>"nullable|max:20",
        ]);
        if($validator->passes()){
               $image=Image::find($id);
               $image->alt=$request->alt;
               $image->save();
               if($image){
                  
                   return response()->json(['message'=>'Photo Updated Success']);
                }
        }
        return response()->json(['error'=>$validator->getMessagebag()]);
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
    public function getImageByFolderId($folder_id){
        $image=Image::where('folder_id',$folder_id)->get();
        return response()->json($image);
    }
    public function getImage(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'limit'=>"required|numeric|min:1|max:50",
            'offset'=>"required|numeric|min:0|max:50",
        ]);
        if($validator->passes()){
            $image=Image::where('author_id',Auth::user()->id)->skip($request->offset)->take($request->limit)->orderBy('id','desc')->get();
            return response()->json($image);
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
}