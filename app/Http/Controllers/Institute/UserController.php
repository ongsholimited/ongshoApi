<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstUsers;
use App\Models\User;
use Validator;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function updateOrCreate(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'bio'=>'required|max:100|min:1',
            'education'=>'required|max:100|min:1',
        ]);

        if($validator->passes()){
            $user=InstUsers::where('ongsho_id',auth()->user()->id)->get();
            if($user->count()>0){
                $user=InstUsers::where('ongsho_id',auth()->user()->id)->first();
                $user->bio=$request->bio;
                $user->education=$request->education;
                $user->ongsho_id=auth()->user()->id;
                $user->save();
                if($user){
                    return response()->json(['message'=>'Your Sign Up Success']);
                }
            }else{
                $user=new InstUsers();
                $user->bio=$request->bio;
                $user->education=$request->education;
                $user->ongsho_id=auth()->user()->id;
                $user->save();
                if($user){
                    return response()->json(['message'=>'Your Sign Up Success']);
                }
            }
            
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

    public function getUser()
    {
        // return auth()->user();
        // return User::where('id',auth()->user()->id)->with('institute')->first();
        return response()->json(User::with('institute')->where('id',auth()->user()->id)->first());
    }
    public function getUserData(Request $request)
    {
        $donors= User::where('first_name','like','%'.$request->searchTerm.'%')->orWhere('last_name','like','%'.$request->searchTerm.'%')->orWhere('email','like','%'.$request->searchTerm.'%')->take(15)->get();
       foreach ($donors as $value){
            $set_data[]=['id'=>$value->id,'text'=>$value->first_name.' '.$value->last_name.'(#'.str_pad($value->id,7,"0",STR_PAD_LEFT ).')'];
        }
        return $set_data;
    }
}
