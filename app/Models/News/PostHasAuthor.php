<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHasAuthor extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=[
        'post_id',
        'author_id'
    ];
    public function details(){
        return $this->setConnection('mysql')->belongsTo('App\Models\User','author_id','id');
    }
    public function post()
    {
        return $this->hasOne(Post::class,'post_id','id');
    }
}