<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CreaditsLog
 * @package App\Models
 * @version July 9, 2018, 2:56 pm CST
 *
 * @property integer user_id
 * @property integer num
 * @property string type
 * @property string reason
 * @property string reason_des
 * @property integer project_error_id
 */
class CreaditsLog extends Model
{
    use SoftDeletes;

    public $table = 'creadits_logs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'num',
        'type',
        'reason',
        'reason_des',
        'project_error_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'num' => 'integer',
        'type' => 'string',
        'reason' => 'string',
        'reason_des' => 'string',
        'project_error_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
