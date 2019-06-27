<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Caompany
 * @package App\Models
 * @version March 20, 2018, 2:03 am UTC
 *
 * @property string name
 * @property string mobile
 * @property string weixin
 * @property integer proince
 * @property integer city
 * @property integer district
 * @property string detail
 * @property longtext intro
 * @property integer view
 * @property integer collect
 * @property string lat
 * @property string lon
 */
class Caompany extends Model
{
    use SoftDeletes;

    public $table = 'caompanies';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'mobile',
        'weixin',
        'province',
        'city',
        'district',
        'detail',
        'intro',
        'view',
        'collect',
        'lat',
        'lon',
        'user_id',
        'status',
        'contact_man'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'mobile' => 'string',
        'weixin' => 'string',
        'province' => 'integer',
        'city' => 'integer',
        'district' => 'integer',
        'detail' => 'string',
        'view' => 'integer',
        'collect' => 'integer',
        'lat' => 'string',
        'lon' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'mobile' => 'required'
    ];

    //收藏该企业的用户
    public function users(){
         return $this->belongsToMany('App\User', 'caompany_user', 'caompany_id', 'user_id');
    }

    //企业发布人
    public function user(){
        return $this->belongsTo('App\User');
    }

    //发布人昵称
    public function getReleaseUserAttribute(){
        return empty($this->user()->first())?'':$this->user()->first()->nickname;
    }

    //发布人会员等级
    public function getReleaseUserLevelAttribute(){
        return empty($this->user()->first())?'':$this->user()->first()->userlevel()->first()->name;
    }


    //企业图片
      public function images(){
        return $this->hasMany('App\Models\CompanyImage','company_id','id');
    }

    //审核状态
    public function getStatusStateAttribute(){
        if($this->status===0){
            return '审核中';
        }elseif ($this->status===1) {
            return '通过';
        }elseif($this->status===2){
            return  '不通过';
        }

    }
    
}
