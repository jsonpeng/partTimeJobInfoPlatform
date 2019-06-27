<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WithDrawalLog
 * @package App\Models
 * @version July 12, 2018, 11:10 am CST
 *
 * @property integer user_id
 * @property float price
 * @property string status
 */
class WithDrawalLog extends Model
{
    use SoftDeletes;

    public $table = 'with_drawal_logs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'price',
        'status',
        'alipay_num'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'price' => 'float',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'price' => 'required'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    
}
