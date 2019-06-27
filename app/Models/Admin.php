<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
//use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Admin extends Model implements AuthenticatableContract{

    use Authenticatable;
    /** 
     * The attributes that are mass assignable. 
     * 
     * @var array 
     */ 
    protected $fillable = [ 
        'name', 'email', 'password', 'type','system_tag'
    ]; 
 
    /** 
     * The attributes that should be hidden for arrays. 
     * 
     * @var array 
     */ 
    protected $hidden = [ 
        'password', 'remember_token', 
    ];

    public function getShowNameAttribute()
    {
        return $this->nickname ? $this->nickname : '管理员编号:'.$this->id;
    }
}
