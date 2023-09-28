<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=['title','description','keyword','content','status','author_id'];

}
