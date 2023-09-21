<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsApi extends Model
{
    use HasFactory;
    protected $fillable=['api_no','short_name','short_code','status'];
}
