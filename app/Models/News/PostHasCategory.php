<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHasCategory extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class,'post_id','id');
    }
}