<?php

namespace App\Http\Traits;
use App\Models\SmsApi;
trait SendSmsTrait{


    public function sendSms($number='BD@1731186740',$sms='হেলো নোমান'){
        $numArr=explode('@',$number);
        $shortName=$numArr[0];
        $single_number=$numArr[1];
        $cn_code=SmsApi::where('short_name',$shortName)->first();
        info($cn_code);
        if($cn_code!=null){
            $func="api".$cn_code->api_no;
          return  $this->$func($cn_code->short_code.$single_number,$sms);
        }
    }
    public function api1($number,$sms){
            $msg=$sms;
            $url = "https://smsapi.shiramsystem.com/user_api/";
            $data = [
                "email" => "mohidulinfo@gmail.com",
                "password" => "0159ed33f2bbaf7b20aab889e5c7aabd",
                "method" => "send_sms",
                "mobile" => [$number],
                "mask" => "Ongsho",
                "message"=>$msg,
            ];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $server_output = curl_exec($ch);
            curl_close($ch);
            $decode=json_decode(strval($server_output));
            info($server_output);
            // return $decode;
            return ['status'=>$decode->status,'message'=>$decode->message];
    }
}