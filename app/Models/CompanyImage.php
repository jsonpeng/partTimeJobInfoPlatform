<?php

namespace App\Models;

use Eloquent as Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CompanyImage
 * @package App\Models
 * @version July 25, 2017, 8:07 pm CST
 */
class CompanyImage extends Model
{
    //use SoftDeletes;

    public $table = 'company_image';
    

    //protected $dates = ['deleted_at'];


    public $fillable = [
        'url',
        'company_id'
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

    //图片关联的公司
    public function company(){
        return $this->belongsTo('App\Models\Caompany');
    }

    
}
