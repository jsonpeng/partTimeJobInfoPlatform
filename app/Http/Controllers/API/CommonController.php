<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Config;
use Log;
use EasyWeChat\Factory;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CommonController extends Controller
{
    //用户登录
    public function loginMiniprogram(Request $request){
           $input = $request->all();
           $varify = app('zcjy')->varifyInputParam($input,['code','userInfo','jindu','weidu']);
           if($varify){
                return zcjy_callback_data($varify,1);
           }
           $app = Factory::miniProgram(Config::get('wechat.mini_program.default'));
           $result = $app->auth->session($input['code']);

           #更新用户信息
           $user = $this->updateUserInfo($input['userInfo'],$result);

           if(array_key_exists('encryptedData',$input) && array_key_exists('iv',$input)){
             #处理手机号
             //Log::info(weixinDecodeTel($result['session_key'],$input['encryptedData'],$input['iv']));
             $phone_obj =optional(json_decode(weixinDecodeTel($result['session_key'],$input['encryptedData'],$input['iv']),true));
             //Log::info($phone_obj['phoneNumber']);
             if(!empty($phone_obj['phoneNumber'])){
                $user->update(['mobile'=>$phone_obj['phoneNumber']]);
             }
           }

           #给予token
           $token = zcjy_base64_en($user->id.'_'.strtotime($user->created_at).'_'.$user->openid);
           #session存储
           session(['zcjy_api_user_'.$user->id => $user]);
           #处理省市区
           $address = getAddressLocation($input['jindu'],$input['weidu']);

           #第一级省份id
           $province_id = app('zcjy')->cityRepo()->getProvinceIdByName($address->province);

           if(empty($province_id)){
              $province_id = app('zcjy')->cityRepo()->getProvinceIdByName(mb_substr($address->address, 0 , 2 , 'utf-8'));
           }

           #该省份下的城市
           $cities = app('zcjy')->cityRepo()->getChildCitiesById($province_id);
           $city_id = null;
           #加上选中状态
           foreach ($cities as $key => $value) {
              $cities[$key]['selected']=false;
              if(strpos($address->city, $cities[$key]['name']) !== false){
                $cities[$key]['selected']=true;
                $city_id = $cities[$key]['id'];
              }
           }
           #该城市下的地区
           $districts = app('zcjy')->cityRepo()->getChildCitiesById($city_id);
           foreach ($districts as $key => $value) {
                 $districts[$key]['selected']=false;
                 if(strpos($address->district, $districts[$key]['name']) !== false){
                    $districts[$key]['selected']=true;
                }
           }
           app('zcjy')->clearRepUsers();
           return zcjy_callback_data(['token' => $token,'address'=>$address->address,'cities'=>$cities,'districts'=>$districts,'school'=>$address->school]);
    }

    //根据id获取用户token
    public function loginId(Request $request,$id){
        $user = User::find($id);
        $token = null;
        if(!empty($user)){
           #给予token
           $token = zcjy_base64_en($user->id.'_'.strtotime($user->created_at).'_'.$user->openid);
        }
        return zcjy_callback_data($token);
    }

    private function updateUserInfo($userInfo,$result)
    {
        if(empty($result)){
          return;
        }
        $userInfo = json_decode($userInfo, true);
        $user = User::where('openid',$result['openid'])->first();
        if(empty($user)){
          $userInfo['openid'] = $result['openid'];
          $userInfo['credits'] = empty(getSettingValueByKey('user_basic_credits')) ? 0 : getSettingValueByKey('user_basic_credits');
          if(empty(User::where('openid',$result['openid'])->first())){
              $user = User::create($userInfo);
           }
        }else{
            $user->update($userInfo);
        }
        return $user;
    }

    public function changeMobile(Request $request){
           $input = $request->all();
           $varify = app('zcjy')->varifyInputParam($input,['mobile']);
           if($varify){
                return zcjy_callback_data($varify,1);
           }
           $user = zcjy_api_user($input);
           $user->update($input);
           return zcjy_callback_data('绑定手机号成功!');
    }

    //用户详细信息
    public function userInfo(Request $request){
        $user = zcjy_api_user($request->all());
        return zcjy_callback_data(['user'=>$user,'company'=>$user->caompany()->first()]);
    }

    //图片上传
    public function uploadImage(Request $request){
        $file =  Input::file('file');
        return app('zcjy')->uploadImages($file,'api',zcjy_api_user($request->all()));
    }

    //发起意见反馈
    public function publishFeedBack(Request $request){
        $input = $request->all();
        $user = zcjy_api_user($input);
        $varify = app('zcjy')->varifyInputParam($input,app('zcjy')->feedBackRepo()->model()::$rules,'key');
        if($varify){
                return zcjy_callback_data($varify,1);
        }
        $input['user_id'] = $user->id;
        app('zcjy')->feedBackRepo()->create($input);
        return zcjy_callback_data('提交意见成功');
    }

    //个人的信用积分记录
    public function userCreditsLog(Request $request){
        $input = $request->all();

        $type = '全部';

        $skip = 0;
        $take = $this->defaultPage();
        $time_type = 'month';

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        if(array_key_exists('type',$input)){
            $type = $input['type'];
        }

        if(array_key_exists('time_type',$input)){
            $time_type = $input['time_type'];
        }

        if($time_type == 'week'){
               $start_time = Carbon::today()->startOfWeek();
               $end_time = Carbon::today()->endOfWeek();
              
        }
        elseif ($time_type == 'month') {
               $start_time = Carbon::today()->startOfMonth();
               $end_time = Carbon::today()->endOfMonth();
        }
        elseif ($time_type == 'custom'){
              if(array_key_exists('time_start',$input) && !empty($input['time_start'])){
                  $start_time = $input['time_start'];
              }
              if(array_key_exists('time_end',$input) && !empty($input['time_end'])){
                  $end_time = $input['time_end'];
              }
        } 

        $user = zcjy_api_user($input);

        $logs = $this->defaultSearchState(app('zcjy')->creaditsLogRepo())
        ->where('user_id',$user->id);

        if($type != '全部'){
          $logs = $logs->where('type',$type);
        }

        #存在两个起止时间
        if(!empty($start_time) && !empty($end_time)){
            $logs =  $logs->whereBetween('created_at',[$start_time,$end_time]);
        }

        #只有开始时间
        if(!empty($start_time) && empty($end_time)){
             $logs =  $logs->where('created_at','>=',$start_time);
        }

        #只有结束时间
        if(!empty($end_time) && empty($start_time)){
            $logs =  $logs->where('created_at','<=',$start_time);
        }

        $logs = $logs->skip($skip)->take($take)->get();

        return zcjy_callback_data($logs);
    }

    //个人发起的/收到的投诉
    public function publishAndReceiveError(Request $request,$platform_type=null){
        if(empty($platform_type)){
            return zcjy_callback_data('参数错误,没有选择对应平台!');
        }

        $input = $request->all();

        $skip = 0;
        $take = $this->defaultPage();
        $send_type = '发起';

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        if(array_key_exists('send_type',$input)){
            $send_type = $input['send_type'];
        }

        #兼职
        if($platform_type == 'part_job'){
            $obj = app('zcjy')->projectErrorRepo();

        }#校购
        elseif ($platform_type == 'errand') {
            $obj = app('zcjy')->errandErrorRepo();
        }

        $user = zcjy_api_user($input);

        $errors =  $this->defaultSearchState($obj)
            ->where('user_id',$user->id)
            ->where('send_type',$send_type)
            ->skip($skip)
            ->take($take)
            ->get();
    
        $errors = $obj->attachInfo($errors,$user);
      
         
        return zcjy_callback_data($errors);
         
    }


}
