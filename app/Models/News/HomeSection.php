<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->hasManyThrough(Post::class,PostHasCategory::class,'post_id','id','category_id','category_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
}