<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\News\Post;
class NewsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function getPost()
    {
        $post=Post::with('category','author')->take(20)->get();
        return response()->json($post);
    }
}
