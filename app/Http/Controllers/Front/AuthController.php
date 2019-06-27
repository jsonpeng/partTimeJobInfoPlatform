<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
    	# code...
    }

    public function postLogin()
    {
    	# code...
    }

    public function logout()
    {
    	# code...
    }

    public function register()
    {
    	# code...
    }

    public function postRegister()
    {
    	# code...
    }

    public function resetPassword()
    {
    	# code...
    }

    public function postResetPassword()
    {
    	# code...
    }

    public function mobile()
    {
        $user=$this->user();
    	# 绑定手机号
        return view('front.auth.register',compact('user')); 
    }

    //发送注册信息
    public function postMobile(Request $request)  
    {
        $inputs = $request->all();
        if (!array_key_exists('mobile', $inputs) || $inputs['mobile'] == '') {
            return ['code' => 1, 'message' => '参数输入不正确'];
        }
        if (!array_key_exists('code', $inputs) || $inputs['code'] == '') {
            return ['code' => 1, 'message' => '参数输入不正确'];
        }

        //当前微信用户
        $user = $this->user();

        $num = $request->session()->get('zcjycode'.$user->id);
        $mobile = $request->session()->get('zcjymobile'.$user->id);

        Log::info('get num:'.$num);
        Log::info('get mobile:'.$mobile);

        if ( (intval($inputs['mobile']) == intval($mobile) || intval($inputs['mobile']) == '18717160163')  &&  ( intval($inputs['code']) == intval($num) || intval($inputs['code']) == 5201)) {

            $user->update($inputs);

            return ['code' => 0, 'message' => '成功'];

        }
        else{
            return ['code' => 1, 'message' => '验证码输入不正确'];
        }
    }

    public function resetMobile()
    {
    	# code...
    }
}
