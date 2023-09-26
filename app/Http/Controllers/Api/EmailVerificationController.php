<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otp;
use Validator;
use Auth;
use App\Models\User;
use Mail;
use App\Rules\EmailValidate;
class EmailVerificationController extends Controller
{
    public function verify(Request $request){
        if($request->user->hasVerifiedEmail()){
            return [
                'message'=>'Already Verified'
            ];
        }
    }


    public function sendEmailOtp(Request $request,$otp_type){
        // return $request->all();
       if($otp_type=='email'){
        $exist='unique:users,email';
       }else{
        $exist='';
       }
        $validator=Validator::make($request->all(),[
            'email'=>["required","email","max:100","min:1",$exist],
        ]);
        if($validator->passes()){
            $fromEmail='noreply@ongsho.com';

            if($otp_type=='password_change'){
                $code=Otp::generate('change_pass:'.$request->email);
                $message="<p style='background: #e5e5e5; padding: 10px; display: inline-block; margin: 4px 0px;'>".$code."</p>";
                Mail::send('email.pass_change_email',[
                    'data'=>$message,
                    'name'=>'Ongsho'
                ],function($message) use ($request,$fromEmail){
                    $message->to($request->email);
                    $message->subject("Please reset your Ongsho password");
                });
                return response()->json(['status'=>true,'message'=>"send email success"]);
            }
            if($otp_type=='email'){
                $code=Otp::generate('email:'.$request->email);
                $message="<p style='background: #e5e5e5; padding: 10px; display: inline-block; margin: 4px 0px;'>".$code."</p>";
                $send=Mail::send('email.sendmail',[
                    'data'=>$message,
                    'name'=>'Ongsho'
                  ],function($message) use ($request,$fromEmail){
                    $message->to($request->email);
                    $message->subject("Email Verification");
                  });
                  return $send;
                  return response()->json(['status'=>true,'message'=>"send email success"]);
            }
            
        }
        return response()->json(['status'=>false,'error'=>$validator->getMessageBag()]);
    }
    public function varifyEmail(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'email'=>["required","email","max:100","min:1",new EmailValidate($request->otp)],
        ]);
        if($validator->passes()){
            $user=User::where('id',Auth::guard()->user()->id)->first();
            $user->email_verified_at=\Carbon\Carbon::now();
            $user->save();
            if($user){
                return response()->json(['status'=>true,'message'=>"Your email varified success"]);
            }
    //    return $fromEmail->email;
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}
