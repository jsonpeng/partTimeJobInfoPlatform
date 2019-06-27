<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ErrandImage
 * @package App\Models
 * @version July 23, 2018, 9:48 am CST
 *
 * @property string url
 * @property integer errand_task_id
 */
class ErrandImage extends Model
{
    use SoftDeletes;

    public $table = 'errand_images';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'url',
        'errand_task_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'url' => 'string',
        'errand_task_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
