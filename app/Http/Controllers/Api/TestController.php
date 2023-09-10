<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
class TestController extends Controller
{
    public function test(Request $request)
    {
      
        $clientIp = $request->getClientIp();

        return "Client's IP address: " . $clientIp;
        
        return "Client's Public IP Address: " . $ip;
        $public_ip = file_get_contents('https://ipinfo.io/ip');
        return  "Your public IP address is: $public_ip";
  
       
        return HomeSection::with('post')->get();
    }
}