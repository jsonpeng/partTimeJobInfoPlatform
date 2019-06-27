<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\User;

use EasyWeChat\Factory;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Log::info('weixin_user');
        /*
        if (!Auth::guard('web')->check()) {
            //Log::info('auth:');
            if (Config::get('web.app_env') == 'local') {
                $user= User::where('openid', 'odh7zsgI75iT8FRh0fGlSojc9PWM')->first();
                // dd($user);
                auth('web')->login($user);
            }else{
                //当前微信用户
                $weixin_user = session('wechat.oauth_user.default')->getOriginal();
                Log::info('weixin_user');
                Log::info($weixin_user);
              
                //防止access_token过期
                if (array_key_exists('errcode', $weixin_user) && $weixin_user['errcode'] == 40001) {
                    $app = Factory::officialAccount(Config::get('wechat.official_account.default'));

                    // 强制重新从微信服务器获取 token.
                    $token = $app->access_token->getToken(true); 
                    //修改 $app 的 Access Token

                    $app['access_token']->setToken($token['access_token'], 7000);
                    //return redirect('/usercenter');
                    $weixin_user = session('wechat.oauth_user.default')->getOriginal(); 

                    return $response = $app->oauth->scopes(['snsapi_userinfo'])
                          ->redirect();
                    //return redirect('/');
                }

                $user = null;
                if (array_key_exists('unionid', $weixin_user)) {
                    $user = User::where('unionid', $weixin_user['unionid'])->first();
                } else {
                    $user = User::where('openid', $weixin_user['openid'])->first();
                }
                if (empty($user)) {
                    $user = app('user')->CreateUserFromWechatOauth($weixin_user);
                    auth('web')->login($user);
                    return redirect('https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU1OTQ5NDE5OA==&scene=124#wechat_redirect');
                } else {

                    auth('web')->login($user);
                }
                //$user=app('user')->varifyUserMemberGuoQi($user);
                //保存用户登录信息
                $user->last_ip = $request->ip();
                $user->last_login = \Carbon\Carbon::now();
                $user->save();
            }
        }else{
            $weixin_user=null;
            if (Config::get('web.app_env') == 'online') {
            $weixin_user = session('wechat.oauth_user.default')->getOriginal();
            Log::info('weixin_user');
            Log::info($weixin_user);
            }
            app('user')->varifyUserMemberGuoQi(auth('web')->user(), $weixin_user);
        }

        return $next($request);
    }
    /*
}
