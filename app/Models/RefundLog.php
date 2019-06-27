<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RefundLog
 * @package App\Models
 * @version July 27, 2018, 6:28 pm CST
 *
 * @property float price
 * @property string reason
 * @property string content
 */
class RefundLog extends Model
{
    use SoftDeletes;

    public $table = 'refund_logs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'price',
        'reason',
        'content',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'reason' => 'string',
        'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
