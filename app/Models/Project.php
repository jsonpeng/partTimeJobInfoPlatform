<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project
 * @package App\Models
 * @version March 19, 2018, 1:26 pm UTC
 *
 * @property string name
 * @property string mobile
 * @property string weixin
 * @property float money
 * @property string type
 * @property integer province
 * @property integer city
 * @property integer district
 * @property string address
 * @property string detail
 * @property string auth_status
 * @property string auth_result
 * @property integer view
 * @property integer collections
 * @property integer industry_id
 */
class Project extends Model
{
    use SoftDeletes;

    public $table = 'projects';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'mobile',
        'weixin',
        'money',
        'time_type',
        'type',
        'length_type',
        'sex_need',
        'province',
        'city',
        'district',
        'address',
        'rec_num',
        'detail',
        'status',
        'pay_status',
        'view',
        'collections',
        'user_id',
        'start_time',
        'end_time',
        'morning_start_time',
        'morning_end_time',
        'afternoon_start_time',
        'afternoon_end_time',
        'caompanie_id',
        'caompanie_name',
        'time_set',
        'is_top',
        'company_status'
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
        'money' => 'float',
        'type' => 'string',
        'province' => 'integer',
        'city' => 'integer',
        'district' => 'integer',
        'address' => 'string',
        'detail' => 'string',
        'status' => 'string',
        'auth_status' => 'string',
        'auth_result' => 'string',
        'view' => 'integer',
        'collections' => 'integer',
        // 'industry_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'money'=> 'required',
        'type' => 'required',
        'detail'=> 'required',
        'mobile'=> 'required',
        'address' => 'required',
        'rec_num' => 'required'
    ];

    //项目所属的行业
    public function industries(){
        return $this->belongsToMany('App\Models\Industry', 'industry_project', 'project_id', 'industry_id');
    }

    //收藏该项目的用户
    public function users(){
         return $this->belongsToMany('App\User', 'project_user', 'project_id', 'user_id');
    }

    //会员编号
  public function getUserNumberAttribute(){
        return empty($this->user()->first())?'1000':'1000'.$this->user()->first()->id;
    }

    //项目的发布人
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

    //发布人对象
    public function getReleaseUserObjAttribute(){
        return empty($this->user()->first())?'':$this->user()->first();
    }

       //发布人对象
    public function getReleaseUserCompanyAttribute(){
        return empty($this->user()->first())?'':$this->user()->first()->caompany()->first();
    }

    //行业显示带跳转
    public function getindustriesShowAttribute(){
        $industries=$this->industries()->get();
        $str='';
        foreach ($industries as $key => $v) {
            $str .="<a href=/zcjy/industries/".$v->id."/edit>".$v->name."</a>&nbsp;&nbsp;";
        }
        return $str;
    }

    //行业显示仅文字
   public function getindustriesTextAttribute(){
        $industries=$this->industries()->get();
        $str='';
        foreach ($industries as $key => $v) {
            $str .=$v->name."&nbsp;&nbsp;";
        }
        return $str;
    }

    //企业图片
    public function images(){
        return $this->hasMany('App\Models\ProjectImage','project_id','id');
    }
}
