<?php

namespace App\Repositories;

use App\Models\Caompany;
use App\Models\CompanyImage;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Class CaompanyRepository
 * @package App\Repositories
 * @version March 20, 2018, 2:03 am UTC
 *
 * @method Caompany findWithoutFail($id, $columns = ['*'])
 * @method Caompany find($id, $columns = ['*'])
 * @method Caompany first($columns = ['*'])
*/
class CaompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'mobile',
        'weixin',
        'province',
        'city',
        'district',
        'detail',
        'intro',
        'view',
        'collect',
        'lat',
        'lon',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Caompany::class;
    }

    /**
     * 获取企业列表带缓存
     */
    public function getCacheCompanies($skip = 0,$take = 18,$count=false){
          return Cache::remember('zcjy_companies_'.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use($skip,$take,$count){
                if(!$count){
                    return Caompany::orderBy('created_at', 'desc')->where('status',1)->with('images')->skip($skip)->take($take)->get();
                }else{
                    return Caompany::orderBy('created_at', 'desc')->where('status',1)->count();
                }
          });
    }

    /**
     * 获取企业列表
     */
    public function getCompanies($skip = 0,$take = 18){
       return Caompany::orderBy('created_at', 'desc')->where('status',1)->with('images')->skip($skip)->take($take)->get();
   }
    


    /**
     * 获取企业通过id
     */
    public function getCacheCompany($id){
        return Cache::remember('zcjy_company_'.$id, Config::get('web.shrottimecache'), function() use($id){

            return $this->findWithoutFail($id);

    });
   }

   
   /**
    * [获取图片 ]
    * @param  [type] $company [description]
    * @param  string $take    [description]
    * @return [type]          [description]
    */
   public function getImages($company,$take='all'){
        if($take=='all'){
            return $company->images()->get();
        }
        return $company->images()->take($take)->get();
   }

   /**
    * [清除企业图片]
    * @param  [type] $company_id [description]
    * @return [type]             [description]
    */
   public function clearImages($company_id){
        $id=CompanyImage::where('company_id',$company_id)->delete();
        return $id;
   }

   /**
    * [创建 更新图片]
    * @param  [type]  $images_arr [description]
    * @param  [type]  $company_id [description]
    * @param  boolean $update     [description]
    * @return [type]              [description]
    */
   public function syncImages($images_arr,$company_id,$update=false){

        #更新先重置
        if($update){
            $this->clearImages($company_id);
        }
        if(count($images_arr)){
            #只添加的话直接添加
            foreach ($images_arr as $k => $v) {
                 if(!empty($v)){
                        CompanyImage::create([
                            'url'=>$v,
                            'company_id'=>$company_id
                        ]);
                }

            }
      }
    }
}
