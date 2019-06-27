<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

/**
 * Class Order
 * @package App\Models
 * @version April 28, 2017, 2:32 am UTC
 */
class Role extends EntrustRole
{

    public $table = 'roles';

    public $fillable = [
        'name',
        //'display_name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        //'display_name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:roles'
        //'display_name' => 'required|unique:roles'
    ];
    
    
}
