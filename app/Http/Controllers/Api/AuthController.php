<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Hash;
use Auth;
use App\Rules\OtpTokenCheck;
use App\Rules\UserNotExistRule;
use Illuminate\Support\Carbon;
class AuthController extends Controller
{
    
    public function register(Request $request)
    {
       // return response()->json($request->all());
       $requestData=$request->all();
       $requestData['username']=$request->username;
       $requestData['first_name']=explode('@',$request->email)[0];
       $requestData['last_name']=explode('@',$request->email)[0];
       $requestData['email']=$request->email;
       $requestData['terms_agreed']=$request->terms_agreed;
       $requestData['gender']=$request->gender;
       $requestData['password']=$request->password;
       $requestData['password_confirmation']=$request->password_confirmation;
       $requestData['otp']=$request->otp;
       // $encode=$request->first_name;
       // $decode=base64_decode($request->first_name);
       // return response()->json($requestData);
       $validator=Validator::make($requestData,[
           'username'=>'nullable|max:100|min:1|unique:users,username',
           'first_name'=>'required|max:100|min:1',
           'last_name'=>'required|max:100|min:1',
           'email'=>['required','max:100','min:1','email','unique:users,email'],
           'terms_agreed'=>'required',
           'otp'=>['required',new OtpTokenCheck($requestData['email'],'email')],
           'gender'=>'nullable|regex:/^([0-9]+)$/',
           'password'=>"required|max:50|min:6|confirmed"
       ]);

       if($validator->passes()){
           $microtime=explode(' ',microtime(false));
           $user=new User;
           if($requestData['username']==null or $requestData['username']==''){
             $requestData['username']='user_'.$microtime[1]+str_replace('.','',$microtime[0]);
           }
           $user->username=$requestData['username'];
           $user->first_name=$requestData['first_name'];
           $user->last_name=$requestData['last_name'];
           $user->email=$requestData['email'];
           $user->gender=$requestData['gender'];
           $user->password=Hash::make($requestData['password']);
           $user->terms_agreed=1;
           $user->email_verified_at=Carbon::now()->toDateTimeString();
           $user->save();
           if($user){
               return response()->json(['status'=>true,'message'=>'Your Sign Up Success']);
           }
       }
       return response()->json(['error'=>$validator->getMessageBag()],422);
        // return response()->json(
        //   ['error'=>$validator->getMessageBag()]  
        // ,422);
    }
    public function login(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'email'=>['required','max:200',new UserNotExistRule],
            'password'=>'required|max:200',
        ]);

        if($validator->passes()){
            $credential=$request->only('email','password');
            if(Auth::guard('web')->attempt($credential)){
                $user=Auth::user();
                $data['user']=$user;
                $data['access_token']=$user->createToken('accessToken')->accessToken; 
                return response()->json(['message'=>'You have successfully logged in','data'=>$data,'status'=>true],200);
            }else{
               return response()->json(['status'=>false,'error'=>['password'=>["Email or password did't match"]]]); 
            }
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag(),'message'=>'Unauthorized'],200);
    }
    public function checkUsername($username){
        $count=User::where('username',$username)->count();
        if($count>0){
           return ['status'=>true,'message'=>"username already existed"]; 
        }
        return ['status'=>false,'message'=>"username available"];
    }

    public function logout()
    {
        $revoke = Auth::user()->token()->revoke();
        return response()->json(['status'=>true,'message'=>'logout successfully done'],200);
    }

    
}