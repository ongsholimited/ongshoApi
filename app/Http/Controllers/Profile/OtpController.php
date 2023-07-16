<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Otp;
use Validator;
use Auth;
use App\Models\User;
use Mail;
use App\Rules\EmailValidate;
class OtpController extends Controller
{
    public function setOtp(Request $request){
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'mobile'=>'required|min:1|numeric',
        ]);
        if($validator->passes()){
            $code= Otp::generate('mobile:'.$request->mobile);
            $api_key="C20081826072b4bc932d35.83708572";
            $sender_id="8809601000185";
            $contacts=$request->mobile;
            $type="application/json";
            $msg="Your bekalpo.com Mobile Verify Code is: ".$code;
           return $fields='api_key='.$api_key.'&type='.$type.'&contacts='.$contacts.'&senderid='.$sender_id.'&msg='.$msg;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://esms.mimsms.com/smsapi");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
            // In real life you should use something like:
            // curl_setopt($ch, CURLOPT_POSTFIELDS, 
            //          http_build_query(array('postvar1' => 'value1')));
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close ($ch);
            // Further processing ...
            return $server_output;
            // if ($server_output == "OK") { 

            //  } else { 
                
            //  }
        }
        return response()->json(['message'=>$validator->getMessageBag()]);
    }

    public function emailOtp(Request $request){
        // return $request->all();
        $validator=Validator::make($request->all(),[
            'email'=>["required","email","max:100","min:1"],
        ]);
        if($validator->passes()){
            $fromEmail=User::where('id',Auth::guard()->user()->id)->first();
            
            $code=Otp::generate('email:'.'noman.eng73@gmail.com');
            $message="your Ongsho Email Varification code is : <span style='font-size:24px;font-weight:bold'>".$code."</span>";
            Mail::send('email.sendmail',[
                'data'=>$message,
                'name'=>Auth::guard()->user()->name
              ],function($message) use ($request,$fromEmail){
                $message->to($fromEmail->email);
                $message->subject("Ongsho Varify Email");
              });
              return response()->json(['message'=>"send email success"]);
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
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
                return response()->json(['message'=>"Your email varified success"]);
            }
    //    return $fromEmail->email;
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}
