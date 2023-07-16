<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $connection='institute';

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }
    public function chapter()
    {
        return $this->belongsTo(Chapter::class,'chapter_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
