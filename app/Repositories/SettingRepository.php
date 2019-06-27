<?php

namespace App\Repositories;

use App\Models\Setting;
use InfyOm\Generator\Common\BaseRepository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'logo',
        'freight',
        'mianyou',
        'mianyou_list',
        'agreen',
        'qq',
        'weixin',
        'intro',
        'contact'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Setting::class;
    }

    public function valueOfKey($key){
        $setting = Setting::where('name', $key)->first();
        if (empty($setting)) {
            $setting = Setting::create(['name' => $key, 'value' => '', 'group' => '', 'des' => '']);
        }
        return $setting->value;
    }

    public function valueOfKeyCached($key){
        return Cache::remember('setting_value_of_key'.$key, Config::get('web.cachetime'), function() use($key){
            return $this->valueOfKey($key);
        });
    }

    public function settingByKey($key){
        $setting = Setting::where('name', $key)->first();
        if (empty($setting)) {
            $setting = Setting::create(['name' => $key, 'value' => '', 'group' => '', 'des' => '']);
        }
        return $setting;
    }

    public function settingByKeyCached($key){
        return Cache::remember('setting_value_of_key'.$key, Config::get('web.cachetime'), function() use($key){
            return $this->valueOfKey($key);
        });
    }
}
