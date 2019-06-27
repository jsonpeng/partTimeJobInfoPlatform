<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ErrandTask
 * @package App\Models
 * @version July 5, 2018, 11:19 am CST
 *
 * @property string name
 * @property integer user_id
 * @property integer tem_id
 * @property string remark
 * @property float give_price
 * @property string price_type
 * @property float item_cost
 * @property integer wait_buyer_enter
 * @property integer remain_time
 * @property integer wish_time_hour
 * @property integer wish_time_minute
 * @property string mobile
 * @property string status
 * @property string tem_word1
 * @property string tem_word2
 * @property string province
 * @property string city
 * @property string district
 * @property string address
 * @property float lat
 * @property float lon
 * @property string school_name
 */
class ErrandTask extends Model
{
    use SoftDeletes;

    public $table = 'errand_tasks';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'user_id',
        'tem_id',
        'remark',
        'give_price',
        'price_type',
        'item_cost',
        'wait_buyer_enter',
        'remain_time',
        'wish_time_hour',
        'wish_time_minute',
        'mobile',
        'status',
        'tem_word1',
        'tem_word2',
        'province',
        'city',
        'district',
        'address',
        'lat',
        'lon',
        'school_name',
        'errand_status',
        'errand_id',
        'pay_status',
        'remain_time_hour',
        'remain_time_min',
        'current_remain_time',
        'current_wish_time',
        'pay_price',
        'platform_price',
        'errander_get_price',
        'out_trade_no'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'user_id' => 'integer',
        'tem_id' => 'integer',
        'remark' => 'string',
        'give_price' => 'float',
        'price_type' => 'string',
        'item_cost' => 'float',
        'wait_buyer_enter' => 'integer',
        'remain_time' => 'integer',
        'wish_time_hour' => 'integer',
        'wish_time_minute' => 'integer',
        'mobile' => 'string',
        'status' => 'string',
        'tem_word1' => 'string',
        'tem_word2' => 'string',
        'province' => 'string',
        'city' => 'string',
        'district' => 'string',
        'address' => 'string',
        'lat' => 'float',
        'lon' => 'float',
        'school_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'give_price' => 'required',
        'mobile' => 'required',
        'tem_word1' => 'required',
        'tem_word2' => 'required',
        'address' => 'required',
        'lat' => 'required',
        'lon' => 'required',
        'school_name' => 'required'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function images(){
        return $this->hasMany('App\Models\ErrandImage','errand_task_id','id');
    }
    
}
