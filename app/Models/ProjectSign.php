<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProjectSign
 * @package App\Models
 * @version July 3, 2018, 12:47 pm CST
 *
 * @property string name
 * @property string self_des
 * @property integer project_id
 * @property integer user_id
 * @property string status
 */
class ProjectSign extends Model
{
    use SoftDeletes;

    public $table = 'project_signs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'self_des',
        'project_id',
        'user_id',
        'status',
        'mobile',
        'company_status',
        'user_status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'self_des' => 'string',
        'project_id' => 'integer',
        'user_id' => 'integer',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function project(){
        return $this->belongsTo('App\Models\Project');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    

    
}
