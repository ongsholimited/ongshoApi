<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Post extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=['title','slug','content','focus_keyword','date','meta_description','feature_image','post_type','status','is_scheduled','is_public'];
    protected $appends=['category'];
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
   
    public function setCatAttribute(){
        $this->attributes['cat']=$this->categories->pluck('category_id')->toArray();
    }
    public function getCategoryAttribute(){
        return $this->categories->pluck('category_id');
    }


    // custom logic by ongsho dev
    // protected static function boot()
    // {
    //     parent::boot();
  
    //     static::created(function ($product) {
    //         $product->slug = $product->createSlug($product->title);
    //         $product->save();
    //     });
    // }
    // private function createSlug($title){
    //     if (static::whereSlug($slug = Str::slug($title))->exists()) {
    //        $max = static::whereTitle($title)->latest('id')->skip(1)->value('slug');
  
    //         if (is_numeric($max[-1])) {
    //             return preg_replace_callback('/(\d+)$/', function ($mathces) {
    //                 return $mathces[1] + 1;
    //             }, $max);
    //         }
  
    //         return "{$slug}-2";
    //     }
  
    //     return $slug;
    // }
    // public function generateAndSetSlug($title) {
    //     return $this->createSlug($title);
    // }
}