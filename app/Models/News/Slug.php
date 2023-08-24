<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    use HasFactory;
    protected $connection='ongsho_news';
    protected $fillable=[
                    'slug_name',
                    'slug_type',
                    'post_id'
                ];
}