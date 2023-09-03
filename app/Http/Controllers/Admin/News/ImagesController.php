<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Storage;
use App\Models\news\Image;
use Str;
class ImagesController extends Controller
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
        // return $request->all();
        // return gettype($request->images);
        // foreach($request->images as $img){
        //     return $img->getClientOriginalExtension();
        // }
        $validator=Validator::make($request->all(),[
            "images"=>"required|array",
            "images.*"=>"required|max:2048|mimes:jpg,png,gif,jpeg",
        ]);
        if($validator->passes()){
             foreach($request->images as $img){
               $microtime=explode(' ',microtime(false));
               $ext= $img->getClientOriginalExtension();
               $f_name=str_replace($ext,'',Str::slug($img->getClientOriginalName(),'-')).'_'.$microtime[1]+str_replace('.','',$microtime[0]).'_'.date('d_m_Y');
               $image=new Image;
               $image->name=$f_name.'.'.$ext;
               $image->folder_id=$request->galary;
               $image->author_id=auth()->user()->id;
               $image->save();
               if($image){
                   Storage::putFileAs('public/media/images/news/',$img,$f_name.'.'.$ext);
               }
               return response()->json(['message'=>'Photo Uploaded Success']);
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

    public function getImages($folder=null){
        if($folder=='null'){
            $folder=null;
        }
        return Image::where("folder_id",$folder)->get();
    }
}