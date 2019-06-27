<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Log;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nickname',
        'mobile',
        'credits',
        'openid',
        'unionid',
        'head_image',
        'user_money',
        'last_login',
        'last_ip',
        'province',
        'city',
        'district',
        'type',
        'school'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];


    
    //发布的项目
    public function project(){
        return $this->hasMany('App\Models\Project');
    }

    //申请的兼职
    public function projects(){
        return $this->belongsToMany('App\Models\Project','project_signs','user_id','project_id');
    }

    //发布的企业
    public function caompany(){
        return $this->hasMany('App\Models\Caompany');
    }

    //收藏的企业
    public function caompanys(){
        return $this->belongsToMany('App\Models\Caompany','caompany_user','user_id','caompany_id');
    }

    //分享二维码
    public function getErweimaAttribute(){

        $path='qrcodes/'.$this->id.'.png';

        if(!file_exists(public_path($path))){
            $url='http://'.$_SERVER['HTTP_HOST'].'?share_id='.$this->id;
            Log::info($url);
            \QrCode::format('png')->size(300)->generate($url,public_path($path));
        }

        return  '/'.$path;
    }

  
     

}
