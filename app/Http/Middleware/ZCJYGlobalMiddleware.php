<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\Socialite\User as SocialiteUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ZCJYGlobalMiddleware
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
        if (Config::get('web.app_env') == 'local') {
            $user = new SocialiteUser([
                'id' => 'odh7zsgI75iT8FRh0fGlSojc9PWM',
                'name' => 'overtrue',
                'nickname' => 'overtrue',
                'avatar' => 'http://wx.qlogo.cn/mmopen/C2rEUskXQiblFYMUl9O0G05Q6pKibg7V1WpHX6CIQaic824apriabJw4r6EWxziaSt5BATrlbx1GVzwW2qjUCqtYpDvIJLjKgP1ug/0',
                'email' => null,
                'original' => [],
                'provider' => 'WeChat',
            ]);
            session(['wechat.oauth_user.snsapi_userinfo' => $user]);
            session(['wechat.oauth_user.default' => $user]);
            session(['wechat.oauth_user' => $user]);
        }

        return $next($request);
    }
}
