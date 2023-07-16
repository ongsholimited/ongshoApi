<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Otp;
use Mail;
class ForgotPasswordController extends Controller
{
    public function passwordChangeOtp(Request $request){
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'email'=>["required","email","max:100","min:1"],
        ]);
        if($validator->passes()){
            $fromEmail='noreply@ongsho.com';
            $code=Otp::generate('change_pass:'.$request->email);
            $message="<p style='background: #e5e5e5; padding: 10px; display: inline-block; margin: 4px 0px;'>".$code."</p>";
            Mail::send('email.pass_change_email',[
                'data'=>$message,
                'name'=>'Ongsho'
              ],function($message) use ($request,$fromEmail){
                $message->to($request->email);
                $message->subject("[Ongsho] Please reset your password");
              });
              return response()->json(['status'=>true,'message'=>"send email success"]);
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }

    public function changePassword()
    {
        
    }
}
