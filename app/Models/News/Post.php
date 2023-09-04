<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=['title','slug','content','focus_keyword','date','meta_description','feature_image','post_type','status','is_scheduled'];
    
    public function categories(){
        return $this->hasMany(PostHasCategory::class,'post_id','id');
    }
    // public function author(){
    //     return $this->setConnection('mysql')->belongsTo('App\Models\User','author_id','id');
    // }
    public function author(){
        return $this->hasMany(PostHasAuthor::class,'post_id','id');
    }
    public function getCreatedAtAttribute(){
        return $this->scheduled_at!==null? $this->scheduled_at : $this->date;
    }
}