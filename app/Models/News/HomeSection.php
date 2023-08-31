<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    public function hasCategory()
    {
        return $this->hasMany(PostHasCategory::class,'category_id','category_id')->select('id','category_id','post_id');
    }
    
    public function post()
    {
        return $this->hasManyThrough(Post::class,PostHasCategory::class,'category_id','id','category_id','post_id');
    }
}