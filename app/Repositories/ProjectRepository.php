<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\ProjectImage;

use InfyOm\Generator\Common\BaseRepository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Class ProjectRepository
 * @package App\Repositories
 * @version March 19, 2018, 1:26 pm UTC
 *
 * @method Project findWithoutFail($id, $columns = ['*'])
 * @method Project find($id, $columns = ['*'])
 * @method Project first($columns = ['*'])
*/
class ProjectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'mobile',
        'weixin',
        'money',
        'type',
        'province',
        'city',
        'district',
        'address',
        'detail',
        'status',
        'auth_status',
        'auth_result',
        'view',
        'collections',
        'user_id'
        // 'industry_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Project::class;
    }

    //该项目的发布者
    public function user(){
        return belongsTo('App\User','user_id','id');
    }

    public function getCachedProject($id){
        return Cache::remember('zcjy_project_one'.$id, Config::get('web.shrottimecache'), function() use ($id) {
            try {
                return $this->findWithoutFail($id);
            } catch (Exception $e) {
                return;
            }
        });
    }

    //获取初始的项目列表
    public function getProjects($skip = 0,$take = 18){
        return Cache::remember('zcjy_project_default_list_'.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($skip,$take) {
           try {
            return Project::where('status','通过')->orderBy('created_at','desc')->skip($skip)->take($take)->get();
            } catch (Exception $e) {
                return [];
            }
         });
     }


    /**
     * 类型1
     * type 1
     * 获取项目列表 通过金额  排序
     * 传入参数(int string string) [访问金额 排序类型 项目类型(项目,需求)]asc顺序 desc倒序
     * @return [array] [信息列表]
     */
    public function getProjectsByMoneySort($money=100000,$project='项目',$skip = 0,$take = 18,$page_times=1){
     return Cache::remember('zcjy_project_type_1_list'.$page_times.'_'.$money.$project.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($money,$project,$skip,$take) {
         try {
            return Project::where('type',$project)->where('auth_status','通过')->where('status','正常')->where('money','<=',$money)->orderBy('money','desc')->skip($skip)->paginate($take);
            } catch (Exception $e) {
                return [];
            }
        });
    }


    /**
     * 类型2
     * type 2
     * 获取项目列表 通过地域
     * 传入参数(int int string) [访问金额 省id 项目类型(项目,需求)]
     * @return [array] [信息列表]
     */
    public function getProjectsByDiyu($province_id=0,$project='项目',$skip = 0,$take = 18,$page_times=1){
       return Cache::remember('zcjy_project_type_2_list'.$page_times.'_'.$province_id.$project.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($province_id,$project,$skip,$take) {
            try{
                return Project::where('type',$project)->where('auth_status','通过')->where('status','正常')->where('province',$province_id)->skip($skip)->paginate($take);
            } catch (Exception $e) {
                return [];
            }
         });
    }

    /**
     * 类型3
     * type 3
     * 获取项目列表 通过行业类型
     * 传入参数(int int string) [访问金额 行业id 项目类型(项目,需求)]
     * @return [array] [信息列表]
     */
    public function getProjectsByHangye($hangye_id,$project='项目',$skip = 0,$take = 18,$page_times=1){
        return Cache::remember('zcjy_project_type_3_list'.$page_times.'_'.$hangye_id.$project.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($hangye_id,$project,$skip,$take) {
            try{
                $hangye=\App\Models\Industry::find($hangye_id);
                if(!empty($hangye)){
                    return $hangye->projects()->where('type',$project)->where('auth_status','通过')->where('status','正常')->skip($skip)->paginate($take);
                }else{
                    return [];
                }
            } catch (Exception $e) {
                return [];
            }
        });
    }


    /**
    * [获取图片 ]
    * @param  [object] $project [description]
    * @param  string $take    [description]
    * @return [type]          [description]
    */
   public function getImages($project,$take='all'){
        if($take=='all'){
            return $project->images()->get();
        }
        return $project->images()->take($take)->get();
   }

   /**
    * [清除企业图片]
    * @param  [type] $project_id [description]
    * @return [type]             [description]
    */
   public function clearImages($project_id){
        $id=ProjectImage::where('project_id',$project_id)->delete();
        return $id;
   }

   /**
    * [创建 更新图片]
    * @param  [type]  $images_arr [description]
    * @param  [type]  $project_id [description]
    * @param  boolean $update     [description]
    * @return [type]              [description]
    */
   public function syncImages($images_arr,$project_id,$update=false){

        #更新先重置
        if($update){
            $this->clearImages($project_id);
        }
        if(count($images_arr)){
            #只添加的话直接添加
            foreach ($images_arr as $k => $v) {
                if(!empty($v)){
                    ProjectImage::create([
                        'url'=>$v,
                        'project_id'=>$project_id
                    ]);
              }
            }
        }

   }


    
}
