<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Rules\OtpEmailValidate;
use App\Helpers\UniqueString;
use Illuminate\Support\Facades\Cache;
class OtpController extends Controller
{
    public function checkOtp(Request $request,$type){
        $validator=Validator::make($request->all(),[
            'otp'=>["required","max:100","min:1",new OtpEmailValidate($request->email,$type)],
            'email'=>["required","max:100","min:1",'email']
        ]);
        if($validator->passes()){
            $token=UniqueString::getToken(12);
            if($type=='password_change'){
                $key='pass:'.$request->email.':'.$token;
            }
            if($type=='email'){
                $key='email:'.$request->email.':'.$token;
            }
            info('token:'.$token);
            $cache=Cache::store('database')->put($key,true,900);
            if($cache){
                return response()->json(['status'=>true,'message'=>"Your Otp varified success",'otp_token'=>$token]);
            }
    //    return $fromEmail->email;
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}
