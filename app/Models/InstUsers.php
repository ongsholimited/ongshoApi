<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstUsers extends Model
{
    use HasFactory;
    protected $fillable=['photo','ongsho_id','bio','education'];
}
