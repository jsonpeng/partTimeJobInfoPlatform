<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Repositories\SettingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Setting;
use Config;

use App\User;
use App\Models\Admin;

class SettingController extends AppBaseController
{
    /** @var  SettingRepository */
    private $settingRepository;

    public function __construct(SettingRepository $settingRepo)
    {
        $this->settingRepository = $settingRepo;
    }

    /**
     * 打开网站设置页面
     * @return [type] [description]
     */
    public function setting()
    {
        return view('admin.common.settings.index');
    }
    
    private function varifySettingVal($input,$attr=[]){
         $status = false;
         if(count($attr)){
            foreach ($attr as $key => $val) {
                if(array_key_exists($val,$input) && empty($input[$val])){
                    $status = '参数不完整';
                }
            }
         }
        return $status;
    }

    /**
     * 更新设置信息
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        try {
            $inputs = $request->all();
      
            $varify = $this->varifySettingVal($inputs,['user_basic_credits','user_min_credits','error_del_credits','without_add_credits','platform_scale','min_withdrawal_price']);
            if($varify){
                return zcjy_callback_data($varify,1,'web');
            }
            if(array_key_exists('min_withdrawal_price',$inputs) && $inputs['min_withdrawal_price'] < 1){
                return zcjy_callback_data('提现企业付款最低金额不得低于1元',1,'web');  
            }
            foreach ($inputs as $key => $value) {
                $setting = $this->settingRepository->settingByKey($key);
                if(strpos($key,'email')!==false && $key!='order_notify_email'){
                   // modifyEnv([autoVarifyMailName($key)=>$value]);
                }
                $setting->update(['value' => $value]);
            }
             return zcjy_callback_data('更新成功',0,'web');
        } catch (Exception $e) {
            return zcjy_callback_data('未知错误',1,'web');
        }
        
    }
    
    public function edit_pwd(){
        $user=Admin::find(1);
        return view('admin.edit_pwd.index')
                ->with('user',$user);
    }

    public function edit_pwd_api($id,Request $request){
        $user=Admin::find($id);
        $pwd=$request->get('passwords');
        $pwd_re=$request->get('newpassword');
        if($pwd==$pwd_re){
            $user->update(['password'=>\Hash::make($pwd)]);
            Flash::success('密码修改成功');

            return redirect('/zcjy');
        }else{
            Flash::error('两次密码输入不一致');
            return redirect(route('settings.edit_pwd'));
        }
    }


}
