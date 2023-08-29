<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasBadge extends Model
{
    use HasFactory;
    protected $fillable=['user_id','badge_key','author_id'];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}