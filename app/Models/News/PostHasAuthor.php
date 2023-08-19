<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHasAuthor extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    public function user(){
        return $this->setConnection('mysql')->belongsTo('App\Models\User','author_id','id');
    }
    
}
