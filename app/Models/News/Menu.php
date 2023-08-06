<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
