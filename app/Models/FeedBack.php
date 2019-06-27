<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FeedBack
 * @package App\Models
 * @version July 6, 2018, 3:37 pm CST
 *
 * @property integer user_id
 * @property string email
 * @property string content
 * @property string status
 */
class FeedBack extends Model
{
    use SoftDeletes;

    public $table = 'feed_back';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'email',
        'content',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'email' => 'string',
        'content' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required',
        'content' => 'required'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    
}
