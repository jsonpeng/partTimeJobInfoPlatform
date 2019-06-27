<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatMenu extends Model
{
    protected $hidden = ['created_at', 'deleted_at' , 'updated_at'];

    /**
     * 字段白名单.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'key',
        'sort',
                          ];

    /**
     * 用于表单验证时的字段名称提示.
     *
     * @var array
     */
    public static $aliases = [
        'parent_id' => '上级菜单',
        'name' => '菜单名称',
        'type' => '菜单类型',
        'key' => '菜单值',
        'sort' => '值',
                             ];

    public function subButtons()
    {
        return $this->hasMany('App\Models\WechatMenu', 'parent_id');
    }

    /**
     * 微信接口中的民称是sub_button，此方法完全是方便微信交互
     * @return [type] [description]
     */
    public function subButton()
    {
        return $this->hasMany('App\Models\WechatMenu', 'parent_id');
    }
}
