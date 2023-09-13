<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSetting extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=['key','value'];
}