<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $connection='institute';
    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }

    public function content()
    {
        return  $this->hasMany(Content::class,'chapter_id','id');
    }
    
}
