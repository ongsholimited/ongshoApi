<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpSmsTemplate extends Model
{
    use HasFactory;
    protected $fillable=['sms','short_name','author_id'];
}
