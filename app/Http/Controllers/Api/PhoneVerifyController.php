<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\SendDataApi;
use Illuminate\Http\Request;
use App\Http\Traits\SendSmsTrait;
use App\Models\OtpSmsTemplate;
use Otp;
use Validator;
use App\Rules\OtpSmsValidate;
use App\Helpers\UniqueString;
use Illuminate\Support\Facades\Cache;
class PhoneVerifyController extends Controller
{
    use SendSmsTrait;
    public function sendOtpSms(Request $request){
        $explode=explode('@',$request->number);
        $short_name=$explode[0];
        $num=$explode[1];
        $sms=OtpSmsTemplate::where('short_name',$short_name)->first();
        info($sms);
        if(isset($sms->sms)){
            $code=Otp::generate($request->key.':'.$request->number);
            $final_sms=str_replace('@otp_code@',$code,$sms->sms);
            $send=$this->sendSms($request->number,$final_sms);
            if($send['status']){
               return SendDataApi::bind('sms send success'); 
            }
            return SendDataApi::bind($send['message'],403); 
        }
        return SendDataApi::bind('someting went wrong',403);
        
    }
    public function OtpVerifySms(Request $request){
        $validator=Validator::make($request->all(),[
            'otp'=>["required","max:100","min:1",new OtpSmsValidate($request->number,$request->key)],
            'number'=>["required","max:100","min:1"]
        ]);
        if($validator->passes()){
            $token=UniqueString::getToken(12);
            $key=$request->key.':'.$request->number.':'.$token;
            info('token:'.$token);
            $cache=Cache::store('database')->put($key,true,900);
            if($cache){
                return SendDataApi::bind(['status'=>true,'message'=>"Your Otp varified success",'otp_token'=>$token]);
            }
        }
        return response()->json(['error'=>$validator->getMessageBag()]);
    }
}
