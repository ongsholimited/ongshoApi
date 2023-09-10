<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
class TestController extends Controller
{
    public function test(Request $request)
    {
      
        $ipaddress = '';
	if ($_SERVER['HTTP_CLIENT_IP'])
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if($_SERVER['HTTP_X_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if($_SERVER['HTTP_X_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if($_SERVER['HTTP_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if($_SERVER['HTTP_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if($_SERVER['REMOTE_ADDR'])
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
        
        return "Client's Public IP Address: " . $ip;
        $public_ip = file_get_contents('https://ipinfo.io/ip');
        return  "Your public IP address is: $public_ip";
  
       
        return HomeSection::with('post')->get();
    }
}