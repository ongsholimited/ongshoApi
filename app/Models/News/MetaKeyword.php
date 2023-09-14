<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaKeyword extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=['title','slug','slogan','description','keyword','robots'];
}