<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Social;
use Auth;
use Validator;
class SocialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return request()->all();
        return response()->json(Social::where('user_id',Auth::user()->id)->get());
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
            'type'=>'required|max:100|min:1',
            'value'=>'required|max:100|min:1',
            'status'=>'required|max:1|min:1',
        ]);
 
        if($validator->passes()){
            $user=new Social;
            $user->user_id=Auth::user()->id;
            $user->type=$request->type;
            $user->value= $request->value;
            $user->status= $request->status;
            $user->save();
            if($user){
                return response()->json(['status'=>true,'message'=>$request->type .' successfully added']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()],422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Social::where('id',$id)->where('user_id',Auth::user()->id));
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
