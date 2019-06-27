<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use Log;
use App\User;
use Carbon\Carbon;

class PartJobController extends Controller
{

    //根据省份名称模糊获取id
    public function getProvinceIdByName(Request $request){
        $varify = app('zcjy')->varifyInputParam($request->all(),['name']);
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        return zcjy_callback_data(app('zcjy')->cityRepo()->getProvinceIdByName($request->get('name')));
    }

    /**
     * 小程序获取第一级省份列表
     *
     * @SWG\Get(path="/api/provinces_list",
     *   tags={"小程序[兼职]接口"},
     *   summary="小程序获取第一级省份列表",
     *   description="小程序获取第一级省份列表,不需要需要token信息",
     *   operationId="getBasicProvince",
     *   produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="status_code=0请求成功,status_code=1参数错误,data返回banner图链接列表",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="服务器出错",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="token字段没带上或者token头已过期",
     *     )
     * )
     */
    public function getBasicProvince(Request $request){
         return zcjy_callback_data(app('zcjy')->cityRepo()->getBasicLevelCities());
    }

    /**
     * 小程序根据省份id获取对应的城市列表
     *
     * @SWG\Get(path="/api/cities_list",
     *   tags={"小程序[兼职]接口"},
     *   summary="小程序根据省份id获取对应的城市列表",
     *   description="小程序根据省份id获取对应的城市列表,不需要需要token信息",
     *   operationId="getCitiesList",
     *   produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="status_code=0请求成功,status_code=1参数错误,data返回banner图链接列表",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="服务器出错",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="token字段没带上或者token头已过期",
     *     )
     * )
     */
    public function getCitiesList(Request $request){
        $varify = app('zcjy')->varifyInputParam($request->all(),['pid']);
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        return zcjy_callback_data(app('zcjy')->cityRepo()->getChildCitiesById($request->get('pid')));
    }


    //获取所有兼职类型
    public function getAllJianZhiType(Request $request){
        return zcjy_callback_data(app('zcjy')->industryRepo()->getCacheIndustries());
    }

    //获取兼职列表带条件的
    public function getJianZhiList(Request $request){
        $projects = app('zcjy')->projectRepo()->model()::where('status','通过');

        $input = $request->all();

        //Log::info($input);

        $skip =0;
        $take = $this->defaultPage();

        $varify = app('zcjy')->varifyInputParam($input);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        #存在类型选择
        if(array_key_exists('type_id', $input) && $input['type_id'] != 0){
           $type = app('zcjy')->industryRepo()->findWithoutFail($input['type_id']);
           if(empty($type)){
                return zcjy_callback_data('该类型不存在',1);
           }
           $projects = $type->projects();
        }

        #存在城市选择
        if(array_key_exists('city', $input) && !empty($input['city'])){
            $projects =  $projects->where('city',$input['city']);
        }

        #存在区域选择
        if(array_key_exists('district', $input) && !empty($input['district'])){
            $projects =  $projects->where('district',$input['district']);
        }

        #存在时间类型选择
        if(array_key_exists('length_type',$input) && !empty($input['length_type'])){
            $projects = $projects->where('length_type',$input['length_type']);
        }

        #存在搜索
        if(array_key_exists('query', $input) && !empty($input['query'])){
          //Log::info('查询');
          $projects = $projects->where('name','like','%'.$input['query'].'%')
          ->orWhere('caompanie_name','like','%'.$input['query'].'%')
          ->orWhere('detail','like','%'.$input['query'].'%')
          ->orWhere('address','like','%'.$input['query'].'%')
          ->orWhere('length_type','like','%'.$input['query'].'%');
        }

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        if($skip>1){
          if(isset($input['id'])){
            if(!is_array($input['id'])){
              $input['id'] = explode(',',$input['id']);
            }
            $projects = $projects->whereNotIn('id',$input['id']);
          }
        }

        $projects = $projects
        ->orderBy('is_top','desc')
        ->orderBy('created_at','desc')
        ->skip($skip)
        ->take($take)
        ->get();
        
        foreach ($projects as $key => $value) {
            #带上类型
            $value['type'] = optional($value->industries()->first())->name;
            #带上发布者信息
            $value['publisher'] = $value->user()->first();
            #带上公司信息
            $value['company'] = $value['publisher']->caompany()->first();
            #带上时间
            $value['current_time'] = $value->created_at->diffForHumans();
            #带上结束时间
            $value['front_show'] = 1;
        }
 
        // foreach ($projects as $key => $val) {
        //     $end_time  = Carbon::parse($val['end_time'])->diffForHumans();
        //     if(stripos($end_time,'天前')!== false || stripos($end_time,'周前')!== false || stripos($end_time,'月前')!== false || stripos($end_time,'年前')!== false){
        //         // $value['front_show'] = 0;
        //         //unset($projects[$key]);
        //         array_splice($projects,$i);
        //     }
        // }
        $projects = $projects->filter(function($item,$key){
              $end_time  = Carbon::parse($item->end_time)->diffForHumans();
              return stripos($end_time,'天前') === false && stripos($end_time,'周前') === false && stripos($end_time,'月前') === false && stripos($end_time,'年前') === false ;
        });


        $projects = $projects->toArray();
        $arr = [];
        foreach ($projects as $key => $value) {
          $arr[] = $value;
        }

        return ['status_code'=>0,'data'=>$arr];
    }

    //获取兼职详情
    public function getJianZhiDetail(Request $request,$id){
        $jianzhi = app('zcjy')->projectRepo()->findWithoutFail($id);
        if(empty($jianzhi)){
            return zcjy_callback_data('没有找到该兼职',1);
        }
        $publisher = $jianzhi->ReleaseUserObj;
        $company = $publisher->caompany()->first();
        if(!empty($company)){
          //getAddressDetail
            if(empty($company->lat) || empty($company->lon)){
                $location = isset(getAddressDetail($company->detail)['location']) ? getAddressDetail($company->detail)['location'] : [];
                if(isset($location['lat']) && isset($location['lng'])){
                    $company->update(['lat'=>$location['lat'],'lon'=>$location['lng']]);
                }

            }
        }
        $view = empty($jianzhi->view) ? 0 : $jianzhi->view;

        $jianzhi->update(['view'=>$view+1]);
        $jianzhi['current_start_time'] = Carbon::parse($jianzhi->start_time)->format('Y-m-d');
        $jianzhi['current_end_time']  = Carbon::parse($jianzhi->end_time)->format('Y-m-d');
        $jianzhi['images'] = $jianzhi->images()->get();
        $jianzhi['location'] = isset(getAddressDetail($jianzhi->address)['location']) ? getAddressDetail($jianzhi->address)['location'] : [];
        
        $user = zcjy_api_user($request->all());
        #检查一下这个兼职 用户报名的状态录用了吗
        $sign_status = $this->defaultSearchState(app('zcjy')->projectSignRepo())
        ->where('project_id',$id)
        ->where('user_id',$user->id)
        ->where('status','已录用')
        ->count();
        $jianzhi['show_commit'] = empty($sign_status) ? false : true;
        return zcjy_callback_data(['jianzhi'=>$jianzhi,'user'=>$user,'company'=>$company,'publisher'=>$publisher]);
    }

    //用户发起兼职报名 需要用户登录
    public function publishProjectSign(Request $request){
        $input = $request->all();
       
        $varify = app('zcjy')->varifyInputParam($input,['project_id','name','self_des','mobile']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }
        
        $jianzhi = app('zcjy')->projectRepo()->findWithoutFail($input['project_id']);

        if(empty($jianzhi)){
            return zcjy_callback_data('没有找到该兼职',1);
        }

        $user = zcjy_api_user($input);

        if(!empty($user)){
            $input['user_id'] = $user->id;
        }

        if(!getSettingValueByKey('company_whether_sign')){
            #企业用户不可报名兼职
            if($user->type == '企业'){
                return zcjy_callback_data('企业用户不可报名兼职',1);
            }
        }

        #不能重复报名
        $sign_varify = app('zcjy')->projectSignRepo()->varifyUserWhetherSign($input['project_id'],$user->id);
        if($sign_varify){
            return zcjy_callback_data($sign_varify,1);
        }
        #已撤销没办法报名
        if($jianzhi->status == '已撤销'){
          return zcjy_callback_data('该兼职已被撤销',1);
        }

        #不能超过最大数量
        // $jianzhi_rec_num = $jianzhi->rec_num;
        // $sign_num = app('zcjy')->projectSignRepo()->nowSignNum($input['project_id']);
        // if($sign_num >= $jianzhi_rec_num){
        //   return zcjy_callback_data('该兼职报名人数已满,请选择其他兼职报名!',1);
        // }

        $sign = app('zcjy')->projectSignRepo()->create($input);

        return zcjy_callback_data('报名成功');
    }

    //用户个人中心查看自己的申请状态 需要用户登录
    public function userProjectSigns(Request $request){
        $input = $request->all();
        $user = zcjy_api_user($input);
        $varify = app('zcjy')->varifyInputParam($input,['status']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        if(array_key_exists('user_id',$input) && !empty($input['user_id'])){
            $user = User::find($input['user_id']);
        }

        $skip =0;
        $take = $this->defaultPage();

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }
        $projects = $this->defaultSearchState(app('zcjy')->projectSignRepo());
        if($input['status'] != '已报名'){
          $projects = $projects
          ->where('user_id',$user->id)
          ->where('status',$input['status'])
          ->where('user_status',0)
          ->skip($skip)
          ->take($take)
          ->get();
        }
        else{
          $projects = $projects
          ->where('user_id',$user->id)
          ->where('user_status',0)
          ->where(function ($query) use ($input){
               $query
              ->where('status',$input['status'])
              ->orWhere('status','已拒绝');
          })
          ->skip($skip)
          ->take($take)
          ->get();
        }
        #带上项目和企业信息
        foreach ($projects as $key => $val) {
              $val['project'] = $val->project()->first();
              if(!empty($val['project'])){
                 $user = $val['project']->user()->first();
              }
              if(!empty($user)){
                $val['company'] =  $user->caompany()->first();
              }  
        }

        return zcjy_callback_data($projects);
    }

    //申请为企业用户
    public function applyForCompanyUser(Request $request){
        $input = $request->all();
        $user = zcjy_api_user($input);
        $company = $user->caompany()->first();

        if(!empty($company)){
            return zcjy_callback_data('一个企业用户最多拥有一家企业!',1);
        }
        
        $varify = app('zcjy')->varifyInputParam($input,['name','mobile','company_images']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        if(!empty($user)){
            $input['user_id'] = $user->id;
        }

        if(array_key_exists('province',$input)){
            if(!is_numeric($input['province'])){
                $input['province'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['province']);
            }
        }

        if(array_key_exists('city',$input)){
            if(!is_numeric($input['city'])){
                $input['city'] = mb_substr($input['city'] , 0 , 2 , 'utf-8');
                $input['city'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['city']);
            }
        }

        if(array_key_exists('district',$input)){
            if(!is_numeric($input['district'])){
                $input['district'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['district']);
            }
        }

        if(array_key_exists('jindu',$input) && array_key_exists('weidu',$input) && !empty($input['jindu']) && !empty($input['weidu'])){
            $attr= getDetailBylt($input['jindu'],$input['weidu']);
            $input['lon'] = $input['jindu'];
            $input['lat'] = $input['weidu'];
            $input['province'] = app('zcjy')->cityRepo()->getProvinceIdByName($attr['province']);
            $attr['city'] = mb_substr($attr['city'] , 0 , 2 , 'utf-8');
            $input['city'] = app('zcjy')->cityRepo()->getProvinceIdByName($attr['city']);
            $input['district'] = app('zcjy')->cityRepo()->getProvinceIdByName($attr['district']);
        }

        $company = app('zcjy')->companyRepo()->create($input);
        
        if(!is_array($input['company_images'])){
            $input['company_images'] = explode(',', $input['company_images']);
        }
        #添加附加图片
        app('zcjy')->companyRepo()->syncImages($input['company_images'],$company->id);
     
        return zcjy_callback_data('申请成功');
    }



    //用户完善企业信息
    public function completeCompanyInfo(Request $request,$id){
        $input = $request->all();
        $company = app('zcjy')->companyRepo()->findWithoutFail($id);

        if(empty($company)){
            return zcjy_callback_data('该企业不存在!');
        }

        $varify = app('zcjy')->varifyInputParam($input,['name','mobile']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }
        $company->update($input);

        return zcjy_callback_data('更新企业信息成功');
    }

    //企业用户发布的招聘
    public function companyPublishProject(Request $request){
        $input = $request->all();
        $user = zcjy_api_user($request->all());
        $projects = $user->project();

        $skip = 0;
        $take =  $this->defaultPage();
        $status = '通过';
        if(array_key_exists('status',$input)){
            $status = $input['status'];
        }

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        if(array_key_exists('status',$input)){
            $projects = $projects->where('status',$status); 
        }

        $projects = $projects->orderBy('created_at','desc')->skip($skip)->take($take)->get();

        #带上单个招聘参加的用户
        foreach ($projects as $key => $value) {
            $value['project_sign'] = app('zcjy')->projectSignRepo()->model()::where('project_id',$value->id)->where('company_status',0)->get();
            if(count($value['project_sign'])){
                foreach ($value['project_sign'] as $key => $value2) {
                   $value2['user'] = $value2->user()->first();
                }
            }
            $value['images'] = $value->images()->get();
        }
        return zcjy_callback_data($projects);
    }

    //企业获取对应的兼职报名名单
    public function companyPublishProjectSign(Request $request,$project_id){
            $project = app('zcjy')->projectRepo()->findWithoutFail($project_id);

            if(empty($project)){
              return zcjy_callback_data('没有找到该兼职',1);
            }

           $input = $request->all();
           $varify = app('zcjy')->varifyInputParam($input,['status']);

           if($varify){
                return zcjy_callback_data($varify,1);
           }

           $project_signs = app('zcjy')->projectSignRepo()->model()::where('project_id',$project_id);

           $skip = 0;
           $take =  $this->defaultPage();

          if(array_key_exists('skip',$input)){
             $skip = $input['skip'];
           }

          if(array_key_exists('take',$input)){
             $take = $input['take'];
           }

             $project_signs = $project_signs
             ->where('status',$input['status'])
             ->where('company_status',0)
             ->orderBy('created_at','desc');

           if(!array_key_exists('skip', $input) && !array_key_exists('take',$input)){
              $project_signs = $project_signs->get();
           }
           else{
              $project_signs = $project_signs 
             ->skip($skip)
             ->take($take)
             ->get();
           }
    
           foreach ($project_signs as $key => $value) {
               $value['user'] = $value->user()->first();
               $value['project'] = $value->project()->first();
           }
           return zcjy_callback_data($project_signs);
    }


    //企业用户更新通过/不通过兼职用户
    public function companyUpdateProjectSign(Request $request,$id){
        $project_sign = app('zcjy')->projectSignRepo()->findWithoutFail($id);

        if(empty($project_sign)){
            return zcjy_callback_data('没有找到该兼职信息',1);
        }

        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['status']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        if($project_sign->status == '已录用' || $project_sign->status == '已拒绝'){
            return zcjy_callback_data('该兼职人员已被操作过,请勿重复操作',1);
        }

        if($project_sign->status == '已结算'){
            return zcjy_callback_data('已结算过该兼职,无法继续操作',1);
        }

        $project_sign->update($input);

        return zcjy_callback_data('处理更新成功!');
    }

    //企业用户发布招聘兼职信息
    public function companyActionPublishProject(Request $request){
        $input = $request->all();
        #必须要的验证参数
        $varify = app('zcjy')->varifyInputParam($input,[
        'name',
        'money',
        'type' ,
        'detail',
        'mobile',
        'address',
        'rec_num'
        ]);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        #带上用户信息
        $user = zcjy_api_user($input);
        $input['user_id'] = $user->id;

        if(array_key_exists('caompanie_id',$input)){
          $caompanie = app('zcjy')->companyRepo()->findWithoutFail($input['caompanie_id']);
          if(!empty($caompanie)){
            $input['caompanie_name'] = $caompanie->name;
          }
          else{
            return zcjy_callback_data('公司信息错误',1);
          }
        }

        if(array_key_exists('province',$input)){
            if(!is_numeric($input['province'])){
                $input['province'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['province']);
            }
        }

        if(array_key_exists('city',$input)){
            if(!is_numeric($input['city'])){
                $input['city'] = mb_substr($input['city'] , 0 , 2 , 'utf-8');
                $input['city'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['city']);
            }
        }

        if(array_key_exists('district',$input)){
            if(!is_numeric($input['district'])){
                $input['district'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['district']);
            }
        }
       #处理地址
       if(array_key_exists('address',$input)){
          $address = optional(getAddressDetail($input['address']));
          $input['province'] = empty($address['province']) ? 0 : $address['province'];
          if(!empty($input['province'])){
                $input['province'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['province']);
          }
          $input['city'] = empty($address['city']) ? 0 : $address['city'];
          if(!empty($input['city'])){
                $input['city'] = mb_substr($input['city'] , 0 , 2 , 'utf-8');
                $input['city'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['city']);
          }
          $input['district'] = empty($address['district']) ? 0 : $address['district'];
          if(!empty($input['district'])){
                $input['district'] = app('zcjy')->cityRepo()->getProvinceIdByName($input['district']);
          }
        }

        $project_type = 0;

        #处理兼职类型
        if(array_key_exists('industries', $input)){
            $project_type = explode(',',$input['industries']);
            $input['industries'] = '';
        }

        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );

        $project = app('zcjy')->projectRepo()->create($input);
        if(array_key_exists('project_images', $input)){
            $input['project_images'] = explode(',',$input['project_images']);
            #添加附加图片
            app('zcjy')->projectRepo()->syncImages($input['project_images'],$project->id);
        }
        #添加兼职类型
        if(!empty($project_type)){
            $project->industries()->sync($project_type);
        }
        return zcjy_callback_data('发布成功');
    }

    //用户投诉兼职企业
    public function userErrorPorject(Request $request,$project_id){
        $project = app('zcjy')->projectRepo()->findWithoutFail($project_id);

        if(empty($project)){
          return zcjy_callback_data('没有找到该兼职',1);
        }

        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['type','reason']);

        if($varify){
          return zcjy_callback_data($varify,1);
        }
        $user = zcjy_api_user($input);
        $input['project_id'] = $project_id;
        #用户发起
        $input['user_id'] = $user->id;
        $input['send_type'] = '发起';
        $input['other_user_id'] = $project->user_id;
        app('zcjy')->projectErrorRepo()->create($input);
        #企业收到
        $input['user_id'] = $project->user_id;
        $input['send_type'] = '收到';
        $input['other_user_id'] = $user->id;
        app('zcjy')->projectErrorRepo()->create($input);
        return zcjy_callback_data('投诉成功,请等待管理员核实');
    }


    //企业投诉个人
    public function companyErrorUser(Request $request,$user_id){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['type','reason','project_id']);

        if($varify){
          return zcjy_callback_data($varify,1);
        }

        $project = app('zcjy')->projectRepo()->findWithoutFail($input['project_id']);

        if(empty($project)){
          return zcjy_callback_data('没有找到该兼职',1);
        }

        $user = zcjy_api_user($input);
        #企业发起
        $input['user_id'] = $user->id;
        $input['send_type'] = '发起';
        $input['other_user_id'] = $user_id;
        app('zcjy')->projectErrorRepo()->create($input);
        #用户收到
        $input['user_id'] = $user_id;
        $input['send_type'] = '收到';
        $input['other_user_id'] = $user->id;
        app('zcjy')->projectErrorRepo()->create($input);
      
        return zcjy_callback_data('投诉成功');
    }

    //企业用户撤销兼职
    public function companyCancleProject(Request $request,$id){
        $input = $request->all();
        $user = zcjy_api_user($input);
        $project = app('zcjy')->projectRepo()->findWithoutFail($id);
        if(empty($project)){
            return zcjy_callback_data('没有找到该兼职',1);
        }
        if($project->user_id != $user->id){
            return zcjy_callback_data('自己只能撤销自己的兼职',1);
        }
        $project->update(['status'=>'已撤销']);
        return zcjy_callback_data('撤销兼职成功');

    }

    //企业用户删除自己的兼职记录
    public function companyDelProject(Request $request,$id){
        $input = $request->all();
        $user = zcjy_api_user($input);
        $project = app('zcjy')->projectRepo()->findWithoutFail($id);
        if(empty($project)){
            return zcjy_callback_data('没有找到该兼职',1);
        }
        if($project->user_id != $user->id){
            return zcjy_callback_data('自己只能删除自己的兼职',1);
        }
        $project->update(['company_status'=>1]);
        return zcjy_callback_data('撤销兼职成功');
    }


    //用户删除自己的报名记录
     public function userDelSelfSigns(Request $request,$id){
        $project_sign = app('zcjy')->projectSignRepo()->findWithoutFail($id);

        if(empty($project_sign)){
            return zcjy_callback_data('没有找到该兼职信息',1);
        }
        $project_sign->update(['user_status'=>1]);
        return zcjy_callback_data('删除报名记录成功');
     }

    //企业删除用户的报名记录
     public function compnayDelUserSigns(Request $request,$id){
        $project_sign = app('zcjy')->projectSignRepo()->findWithoutFail($id);

        if(empty($project_sign)){
            return zcjy_callback_data('没有找到该兼职信息',1);
        }
        $project_sign->update(['company_status'=>1]);
        return zcjy_callback_data('删除报名记录成功');
     }


      //用户确认收款
      public function userEnterPorjectPrice(Request $request,$project_sign_id){
        $project = app('zcjy')->projectSignRepo()->findWithoutFail($project_sign_id);

        if(empty($project)){
          return zcjy_callback_data('没有找到该报名记录',1);
        }

        if($project->status == '已报名'){
            return zcjy_callback_data('未录用无法确认收款',1);
        }

        if($project->status == '已结算'){
            return zcjy_callback_data('该兼职已结算过了',1);
        }

        if($project->status == '已拒绝'){
            return zcjy_callback_data('该报名已被拒绝,无法确认收款!',1);
        }

         $project->update(['status'=>'已结算']);
         return zcjy_callback_data('确认收款成功');
      }


}
