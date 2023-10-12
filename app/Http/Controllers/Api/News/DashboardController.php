<?php

namespace App\Http\Controllers\Api\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News\Post;
use App\Models\News\PostHasAuthor;
use App\Models\News\PostView;
use App\Helpers\Constant;
use Auth;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $total_post=PostHasAuthor::where('author_id',Auth::user()->id)->whereHas('post',function($q){
            $q->where('status','!=',Constant::POST_STATUS['deleted']);
        })->count();
        $public_post=PostHasAuthor::with('post')->whereHas('post',function($q){
            $q->where('status',Constant::POST_STATUS['public']);
        })->count();
        $total_view=DB::connection('ongsho_news')->select("select count(post_views.post_id) views from post_has_authors
        inner join post_views on post_views.post_id
        where post_has_authors.author_id=:author_id
        ",['author_id'=>auth()->user()->id]);

       return $data=['total_post'=>$total_post,'total_public'=>$public_post,'total_view'=>$total_view[0]->views];
    }
}
