<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
use App\Http\Traits\SendDataApi;
class ProfileController extends Controller
{

    public function __construct(){
        $this->middleware('auth:api');
    }
    public function profileUpdate(Request $request)
    {
        // return response()->json($request->all());
       $requestData=$request->all();
       $requestData['username']=$request->username;
       $requestData['first_name']=$request->first_name;
       $requestData['last_name']=$request->last_name;
       $requestData['gender']=$request->gender;
       $requestData['image']=$request->image;
       $requestData['birth_date']=$request->birth_date;
       $requestData['phone']=$request->phone;

       $validator=Validator::make($requestData,[
           'username'=>'nullable|max:100|min:1|unique:users,username,'.$request->user()->id,
           'first_name'=>'required|max:100|min:1',
           'last_name'=>'required|max:100|min:1',
           'birth_date'=>'nullable|max:100|min:1',
           'gender'=>'nullable|regex:/^([0-9]+)$/',
           'phone'=>'nullable|regex:/^([0-9]+)$/',
           'image'=>'nullable|mimes:jpeg,png,jpg,gif|max:2048',
       ]);

       if($validator->passes()){
           $user=User::find($request->user()->id);
           $user->username=$requestData['username'];
           $user->first_name=$requestData['first_name'];
           $user->last_name=$requestData['last_name'];
           $user->gender=$requestData['gender'];
           $user->birth_date=($requestData['birth_date']!=null ? strtotime($requestData['birth_date']) : null);
           if($request->hasFile('image')) {
                if($user->photo!=null){
                    unlink(storage_path('app/public/media/images/users/'.$user->photo));
                }
                $ext = $request->image->getClientOriginalExtension();
                $name =auth()->user()->id  .'_'. time() . '.' . $ext;
                $request->image->storeAs('public/media/images/users', $name);
                $user->photo = $name;
            }
           $user->save();
           if($user){
               return response()->json(['message'=>'Profile Updated Success','user'=>Auth::user()]);
           }
       }
       return response()->json(['error'=>$validator->getMessageBag()],422);
        // return response()->json(
        //   ['error'=>$validator->getMessageBag()]  
        // ,422);
    }
    public function getProfile()
    {
        $user=User::without('contacts')->where('id',Auth::user()->id)->first();
        if($user){
            return SendDataApi::bind($user);
        }
        return SendDataApi::bind('data not found',404);
    }
}
