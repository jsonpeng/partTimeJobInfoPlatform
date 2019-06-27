<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProjectError
 * @package App\Models
 * @version March 26, 2018, 1:29 am UTC
 *
 * @property string reason
 * @property number company_id
 */
class ProjectError extends Model
{
    use SoftDeletes;

    public $table = 'project_errors';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'type',
        'reason',
        'project_id',
        'user_id',
        'status',
        'send_type',
        'other_user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'reason' => 'string',
        'project_id' =>'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function project(){
        return $this->hasOne('App\Models\Project','id','project_id');
    }



    
}
