<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable = ['name', 'slug','parent_id','author_id','status'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        // return $this->belongsTo(Category::class, 'parent_id');
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function post(){
        return $this->hasManyThrough(Post::class,PostHasCategory::class,'category_id','id','id','post_id');
    }
    
}