<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Hash;
use Storage;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function store(Request $request)
    {
        // return response()->json($request->all());
        $requestData=$request->all();
        $requestData['username']=$request->username;
        $requestData['first_name']=$request->first_name;
        $requestData['last_name']=$request->last_name;
        $requestData['email']=$request->email;
        $requestData['password']=$request->password;
        $requestData['password_confirmation']=$request->password_confirmation;
        // $encode=$request->first_name;
        // $decode=base64_decode($request->first_name);
        // return response()->json($requestData);
        $validator=Validator::make($request->all(),[
            'username'=>'required|max:100|min:1|unique:users,username',
            'first_name'=>'required|max:100|min:1',
            'last_name'=>'required|max:100|min:1',
            'email'=>'required|max:100|min:1|unique:users,email',
            'terms_agreed'=>'required',
            'gender'=>'required|regex:/^([0-9]+)$/',
            'password'=>"required|max:50|min:6|confirmed"
        ]);

        if($validator->passes()){
            $user=new User;
            $user->username=$request->username;
            $user->first_name=$request->first_name;
            $user->last_name=$request->last_name;
            $user->email=$request->email;
            $user->gender=$request->gender;
            $user->password=Hash::make($request->password);
            $user->terms_agreed=1;
            $user->save();
            if($user){
                return response()->json(['message'=>'Your Sign Up Success']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

    public function changeName(Request $request)
    {
        // return response()->json($request->all());
        
        $requestData=$request->all();
        $requestData['first_name']=base64_decode($request->first_name);
        $requestData['last_name']=base64_decode($request->last_name);
        // $encode=$request->first_name;
        // $decode=base64_decode($request->first_name);
        // return response()->json($requestData);
        $validator=Validator::make($requestData,[
            'first_name'=>'required|max:100|min:1',
            'last_name'=>'required|max:100|min:1',
        ]);

        if($validator->passes()){
            $user=User::find(auth()->user()->id);
            $user->first_name=base64_decode($request->first_name);
            $user->last_name=base64_decode($request->last_name);
            $user->save();
            if($user){
                return response()->json(['message'=>'Your Name Changed Success']);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
    public function changePhoto(Request $request)
    {
        // return response()->json($request->all());
        
        // $requestData=$request->all();
        // Storage::disk('google')->put('noman.jpg', 'Hello World');
        // $ext=$request->image->getClientOriginalExtension();
        // $name=Auth::user()->id.'_'.str_replace(" ","_",$user->first_name).'_'.$user->id.'_'.time().'.'.$ext;
       
        Storage::disk('google')->put('lara-test/noman.jpg',$request->image);
        return "done";
        $validator=Validator::make($request->all(),[
            'image'=>'required|image|mimes:jpeg,png,jpg,svg|max:2000',
        ]);

        if($validator->passes()){
            $user=User::find(auth()->user()->id);
            if($user->photo!=null){
                unlink(storage_path('app/public/images/user/'.$user->photo));
            }
            if ($request->hasFile('image')){
                $ext=$request->image->getClientOriginalExtension();
                $name=Auth::user()->id.'_'.str_replace(" ","_",$user->first_name).'_'.$user->id.'_'.time().'.'.$ext;
                $request->image->storeAs('public/images/user/',$name);
                $user->photo=$name;
            }
            $user->save();
            if($user){
                return response()->json(['message'=>'Your Photo Changed Success','photo'=>$user->photo]);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
    public function changeBirthday(Request $request)
    {
        // return response()->json($request->all());
        $validator=Validator::make($request->all(),[
            'birthday'=>'required|max:12',
        ]);

        if($validator->passes()){
            $user=User::find(auth()->user()->id);
            $user->birth_date=strtotime($request->birthday);
            $user->save();
            if($user){
                return response()->json(['message'=>'Your Birthday Changed Success','birth_date'=>date('d-m-Y',$user->birth_date)]);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
    public function guard()
    {
        return Auth::guard();
    }
}
