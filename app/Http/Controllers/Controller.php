<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //清空缓存
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return ['status'=>true,'msg'=>''];
    }

    public function test(Request $request){
      return getDetailBylt(104.04701,30.548397);
     
    }

    //前端用户信息
    public function user(){

    	return auth('web')->user();

    }

  /**
    * 默认ajax操作通过Repository对象
    * @param  [object]   $repo_obj [Repository对象]
    * @param  [array]    $input    [input的提交vale]
    * @param  [string]   $action   [动作(store添加 update更新 delete删除)]
    * @param  [int]      $id       [需要操作的id]
    */
   public function defaultAjaxActionByRepo($repo_obj,$input,$action='store',$id=null,$admin=false){
    #过滤管理员操作及点赞踩
    if(!$admin && !array_key_exists('dianzan', $input) && !array_key_exists('cai', $input)){
            $input['user_id']=$this->user()->id;
    }
    
    $input =array_filter($input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH);
  
        #创建操作
        if($action=='store'){
            $create_success_obj=($repo_obj->model())::create($input);

            #如果涉及到图片添加
            
             #企业图片
             if(array_key_exists('company_images',$input)){
                 if(!empty($input['company_images'])){
                    $repo_obj->syncImages($input['company_images'],$create_success_obj->id);
                }
            }

            #项目图片
            if(array_key_exists('project_images',$input)){
                  if(!empty($input['project_images'])){
                    $repo_obj->syncImages($input['project_images'],$create_success_obj->id);
                }
            }

            return ['code'=>0,'message'=>'操作成功'];
        }

        $obj=$repo_obj->findWithoutFail($id);
        if(!empty($obj)){
            #更新操作
            if($action=='update'){
                $obj->update($input);

                #如果涉及到图片更新
                
                    #企业图片
                    if(array_key_exists('company_images',$input)){
                        if(!empty($input['company_images'])){
                            $repo_obj->syncImages($input['company_images'],$id,true);
                        }

                    }

                    #项目图片
                    if(array_key_exists('project_images',$input)){
                          if(!empty($input['project_images'])){
                                $repo_obj->syncImages($input['project_images'],$id,true);
                        }
                    }

            }

            #删除操作
            if($action=='delete'){
                $repo_obj->delete($id);
            }

            if($action=='show'){
                return ['code'=>0,'message'=>$obj];
            }

            #如果涉及到行业选择
            if(array_key_exists('industries',$input)){
              if(!empty($input['industries'])){
                  $obj->industries()->sync([$input['industries']]);
              }
            }

            return ['code'=>0,'message'=>'操作成功'];

        }else{

            return ['code'=>1,'message'=>'没有找到相关信息'];

        }
   }

   //获取错误信息列表
   public function getErrorList(){
      $list= preg_replace("/\n|\r\n/", "_",getSettingValueByKey('error_info_list'));
      $list_arr = explode('_',$list);
      return $list_arr;
   }

   /**
     * [初始化查询索引状态]
     * @param  [Repository / Model] $obj [description]
     * @return [type]                    [description]
     */
   public function defaultSearchState($obj){
         if(!empty($obj)){
            return !empty(optional($obj)->model())
                ?($obj->model())::orderBy('created_at','desc')
                :$obj::orderBy('created_at','desc');
         }else{
            return [];
         }
    }

    /**
     * 前端默认分页数量
     * @parameter []
     * @return [int] [每页显示数量]
     */
    public function defaultPage(){
        return empty(getSettingValueByKey('front_take')) ? 15 : getSettingValueByKey('front_take');
    }
   
}
