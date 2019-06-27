<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class School
 * @package App\Models
 * @version July 5, 2018, 1:42 pm CST
 *
 * @property string name
 * @property string province
 * @property string city
 * @property string district
 * @property string address
 * @property float lon
 * @property float lat
 */
class School extends Model
{
    use SoftDeletes;

    public $table = 'schools';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'province',
        'city',
        'district',
        'address',
        'lon',
        'lat'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'province' => 'string',
        'city' => 'string',
        'district' => 'string',
        'address' => 'string',
        'lon' => 'float',
        'lat' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'province' => 'required',
        'city' => 'required',
        'district' => 'required',
        'address' => 'required',
        'lon' => 'required',
        'lat' => 'required'
    ];

    
}
