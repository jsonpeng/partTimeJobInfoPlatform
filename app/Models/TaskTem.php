<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TaskTem
 * @package App\Models
 * @version July 2, 2018, 11:02 am CST
 *
 * @property string name
 * @property string content
 * @property string tag
 */
class TaskTem extends Model
{
    use SoftDeletes;

    public $table = 'task_tems';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'content',
        'tag'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'content' => 'string',
        'tag' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'content' => 'required'
    ];

    
}
