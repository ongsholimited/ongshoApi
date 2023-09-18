<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\HomeSection;
use App\Models\News\Post;
class TestController extends Controller
{
    public function test(Request $request)
    {
       
        // $post=new Post;
        return Post::whereTitle('qwertyuiop')->latest('id')->skip(1)->value('slug');
    //    return $post->generateAndSetSlug('qwertyuiop');
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