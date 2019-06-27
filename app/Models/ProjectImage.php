<?php

namespace App\Models;

use Eloquent as Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ProjectImage
 * @package App\Models
 * @version July 25, 2017, 8:07 pm CST
 */
class ProjectImage extends Model
{
   // use SoftDeletes;

    public $table = 'project_image';
    

    //protected $dates = ['deleted_at'];


    public $fillable = [
        'url',
        'project_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    //图片关联的项目
    public function project(){
        return $this->belongsTo('App\Models\Project');
    }

    
}
