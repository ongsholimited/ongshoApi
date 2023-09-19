<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
use App\Models\News\Post;
use App\Http\Traits\SlugableTrait;

class TestController extends Controller
{
    use SlugableTrait;
    public function test(Request $request)
    {
       
        return SlugableTrait::makeSlug('nam-officiis-fuga-magni-omnis-et-voluptatum-veritatis');
        return Post::whereTitle('qwertyuiop')->latest('id')->skip(1)->value('slug');
    //    
        $x='App\Models\User'::find(1);
        return $x;
    //   XXXX
        $clientIp = $request->ips();
    //    return  $_SERVER['REMOTE_ADDR'];
        // return dd($request->headers->all());
        return $clientIp;
        
        $public_ip = file_get_contents('https://ipinfo.io/ip');
        return  "Your public IP address is: $public_ip";
  
       
        return HomeSection::with('post')->get();
    }
}