<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Otp;
use Mail;
use Hash;
use Auth;
use App\Models\User;
use App\Rules\ChangePassEmailValidate;
use App\Rules\OtpTokenCheck;
use App\Rules\UserNotExistRule;
use App\Helpers\UniqueString;
class ForgotPasswordController extends Controller
{
    public function changePassword(Request $request,$token_type)
    {
        $validator=Validator::make($request->all(),[
            'token'=>["required","max:100","min:1",new OtpTokenCheck($request->email,$token_type)],
            'password'=>"required|confirmed"
        ]);
        if($validator->passes()){
            $user=User::where('email',$request->email)->first();
            $user->password=Hash::make($request->password);
            $user->save();
            if($user){
                return response()->json(['status'=>true,'message'=>"Your email varified success"]);
            }
    //    return $fromEmail->email;
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }

}
