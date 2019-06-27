<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Industry
 * @package App\Models
 * @version March 19, 2018, 9:37 am UTC
 *
 * @property string name
 * @property integer sort
 */
class Industry extends Model
{
    use SoftDeletes;

    public $table = 'industries';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'sort'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'sort' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    //行业下的项目
    public function projects(){
        return $this->belongsToMany('App\Models\Project', 'industry_project','industry_id','project_id');
    }
}
