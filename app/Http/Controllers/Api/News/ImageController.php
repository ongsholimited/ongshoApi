<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\News\Image;
use Storage;
class ImageController extends Controller
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
        $validator=Validator::make($request->all(),[
            "images"=>"required|max:2048|mimes:jpg,png,gif,jpeg",
            "folder"=>"required|max:20",
        ]);
        if($validator->passes()){
               $img=$request->images;
               $ext= $img->getClientOriginalExtension();
               $f_name=time().'_'.date('d_m_Y');
               $image=new Image;
               $image->name=$f_name.'.'.$ext;
               $image->folder_id=$request->folder;
               $image->author_id=auth()->user()->id;
               $image->save();
               if($image){
                   Storage::putFileAs('public/post_images',$img,$f_name.'.'.$ext);
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
    public function getImageByFolderId($folder_id){
        $image=Image::where('folder_id',$folder_id)->get();
        return response()->json($image);
    }
}