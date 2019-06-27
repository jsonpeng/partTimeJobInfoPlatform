<?php

namespace App\Repositories;

use App\Models\Industry;
use InfyOm\Generator\Common\BaseRepository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Class IndustryRepository
 * @package App\Repositories
 * @version March 19, 2018, 9:37 am UTC
 *
 * @method Industry findWithoutFail($id, $columns = ['*'])
 * @method Industry find($id, $columns = ['*'])
 * @method Industry first($columns = ['*'])
*/
class IndustryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'sort'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Industry::class;
    }

    //获取所有的行业类型
    public function getAllIndustries(){
        return Industry::all();
    }

    //获取行业类型带缓存
    public function getCacheIndustries($skip = 0,$take = 1000){
        return Cache::remember('zcjy_industries_'.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($skip,$take) {

            return Industry::orderBy('sort','desc')->skip($skip)->take($take)->get();
            
            });
    }

}
