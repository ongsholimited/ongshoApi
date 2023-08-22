<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=['category_id','title','content','tags','author_id'];

    public function categories(){
        return $this->hasMany(PostHasCategory::class,'post_id','id');
    }
    // public function author(){
    //     return $this->setConnection('mysql')->belongsTo('App\Models\User','author_id','id');
    // }
    public function author(){
        return $this->hasMany(PostHasAuthor::class,'post_id','id');
    }
   
}