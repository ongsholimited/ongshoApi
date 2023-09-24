<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;
use App\Models\News\Slug;
trait SlugableTrait{

    public static function makeSlug($value,$slug_id=null)
    {
        $slug = Str::slug($value);
        
        $count = Slug::whereRaw("slug_name RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        if($slug_id!==null){
            $count = Slug::whereRaw("slug_name RLIKE '^{$slug}(-[0-9]+)?$'")->whereNotIn('id',[$slug_id])->count();
        }
        return $count ? $slug.'-'.($count+1) : $slug;
    }
    public static function slugCount($value,$post_id=null)
    {
        $slug = Str::slug($value);
        if($post_id==null){
            $count = Slug::whereRaw("slug_name RLIKE '^{$slug}(-[0-9]+)?$'")->count();
        }else{
            $count = Slug::whereRaw("slug_name RLIKE '^{$slug}(-[0-9]+)?$'")->whereNotIn('post_id',[$post_id])->count();
        }

        return $count;
    }

}