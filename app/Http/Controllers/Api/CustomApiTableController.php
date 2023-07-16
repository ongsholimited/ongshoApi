<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomApiTable;
use Google\Service\CloudSourceRepositories\Repo;
use Validator;

class CustomApiTableController extends Controller
{
    public function createApi(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'url'=>'required|max:100|min:1|unique:custom_api_tables,url',
            'json'=>'required|max:100|min:1',
        ]);
 
        if($validator->passes()){
            $user=new CustomApiTable;
            $user->url=$request->url;
            $user->json= json_encode( $request->json );
            $user->save();
            if($user){
                return response()->json(['status'=>true,'message'=>'your json inserted']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()],422);
    }
    public function getApi($url){
       $json= CustomApiTable::where('url',$url)->first()->json;
       return response()->json(json_decode($json));
    }
    public function getUrlList()
    {
        return response()->json(CustomApiTable::get());
    }
    public function updateApi(Request $request,$url)
    {
        $validator=Validator::make($request->all(),[
            // 'url'=>'required|max:100|min:1|unique:custom_api_tables,url',
            'json'=>'required|max:100|min:1',
        ]);
 
        if($validator->passes()){
            $user=CustomApiTable::where('url',$url)->first();
            // $user->url=$request->url;
            $user->json=$request->json;
            $user->save();
            if($user){
                return response()->json(['status'=>true,'message'=>'your json data updated']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()],422);
    }
}
