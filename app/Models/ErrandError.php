<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ErrandError
 * @package App\Models
 * @version July 10, 2018, 2:49 pm CST
 *
 * @property string type
 * @property string reason
 * @property integer errand_id
 * @property integer user_id
 * @property integer task_id
 * @property string status
 * @property string send_type
 */
class ErrandError extends Model
{
    use SoftDeletes;

    public $table = 'errand_errors';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'type',
        'reason',
        'errand_id',
        'user_id',
        'task_id',
        'status',
        'send_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
        'reason' => 'string',
        'errand_id' => 'integer',
        'user_id' => 'integer',
        'task_id' => 'integer',
        'status' => 'string',
        'send_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function errander(){
        return $this->belongsTo('App\User','errand_id','id');
    }

    public function publisher(){
        return $this->belongsTo('App\User','user_id','id');
    }

    public function task(){
        return $this->belongsTo('App\Models\ErrandTask','task_id','id');
    }

    
}
