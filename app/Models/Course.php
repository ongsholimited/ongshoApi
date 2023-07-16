<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $connection='institute';
    protected $fillable = ['ongsho_id','category_id','title','description','thumbnail'];
    public function user()
    {
        return $this->setConnection('mysql')->belongsTo(User::class,'user_id','id');
    }
    public function category()
    {
        return $this->belongsTo(CourseCategory::class,'category_id','id');
    }

    public function chapter(){
        return $this->hasMany(Chapter::class,'course_id','id')->with('content');
    }
}
