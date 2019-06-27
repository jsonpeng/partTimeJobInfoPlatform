<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Models
 * @version March 23, 2018, 1:22 am UTC
 *
 * @property float price
 * @property string pay_platform
 * @property string order_pay
 * @property timestamp paytime
 * @property string pay_no
 * @property string out_trade_no
 * @property string remark
 * @property string type
 * @property integer user_id
 * @property integer user_level_id
 */
class Order extends Model
{
    use SoftDeletes;

    public $table = 'orders';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'price',
        'pay_platform',
        'order_pay',
        'paytime',
        'pay_no',
        'out_trade_no',
        'remark',
        'type',
        'user_id',
        'user_level_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'pay_platform' => 'string',
        'order_pay' => 'string',
        'pay_no' => 'string',
        'out_trade_no' => 'string',
        'remark' => 'string',
        'type' => 'string',
        'user_id' => 'integer',
        'user_level_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    //下单用户
    public function user(){
          return $this->belongsTo('App\User','user_id','id');
    }
    
    //下单用户等级
    public function userLevel(){
          return $this->belongsTo('App\Models\UserLevel','user_level_id','id');
    }

    
}
