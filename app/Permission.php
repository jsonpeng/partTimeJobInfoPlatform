<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

/**
 * Class Order
 * @package App\Models
 * @version April 28, 2017, 2:32 am UTC
 */
class Permission extends EntrustPermission
{


    public $table = 'permissions';

    public $fillable = [
        'name',
        'slug',
        'icon',
        'show_menu',
        'tid',
        'display_name',
        'description',
        'model'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'display_name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:permissions',
        'display_name' => 'required|unique:permissions'
    ];

    public function getIconHtmlAttribute()
    {
        if(strpos($this->name,'index') || strpos($this->name,'*') || strpos($this->name,'create') ) {
            return $this->icon ? '<i class="fa fa-' . $this->attributes['icon'] . '"></i>' : '';
        }else{
            return '--';
        }
    }

    public function getIsMenusAttribute(){
        if(strpos($this->name,'index') || strpos($this->name,'*') || strpos($this->name,'create') ) {
            return $this->show_menu == '1' ? '<span class="label label-danger">是</span>' : '<span class="label label-default">否</span>';
        }else{
            return '--';
        }
    }

    public function getGroupFuncAttribute(){
        if(autoMatchRoleGroupNameByTid($this->tid)==1){
            return $this->display_name;
        }else{
            return autoMatchRoleGroupNameByTid($this->tid,false);
        }
    }

    public function getLinkRelAttribute(){
        if($this->description=='页面') {
            if (strpos($this->name, '*')) {
                return substr($this->name, 0, strlen($this->name) - 1) . 'index';
            } else {
                return $this->name;
            }
        }

    }
}
