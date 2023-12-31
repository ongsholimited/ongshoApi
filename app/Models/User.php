<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $connection='ongsho';
    protected $connection='mysql';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends=['contact'];
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */


    public function institute()
    {
        return $this->hasOne(InstUsers::class,'ongsho_id','id');
    }
    public function badges(){
        return $this->belongsTo(UserHasBadge::class,'id','user_id')->where('badge_key','news_verified');
    }
    // public function news_verify(){
    //     return $this->belongsTo(UserHasBadge::class,'user_id','id');
    // }
    public function contacts(){
        return $this->hasMany(Social::class,'user_id','id');
    }
    public function getContactAttribute(){
        $arr=[];
        foreach($this->contacts as $cn){
           $arr[$cn->type]=$cn->value;
        }
        return $arr;
    }
}