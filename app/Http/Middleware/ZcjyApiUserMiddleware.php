<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\User;

use EasyWeChat\Factory;

class ZcjyApiUserMiddleware
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
        $varify = $this->zcjyApiUserVarify($request->all());
        if($varify){
            return zcjy_callback_data($varify,401);
        }
        return $next($request);
    }

    /**
     * [接口请求用户验证]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    private function zcjyApiUserVarify($input){
         $status = false;
         if(array_key_exists('token',$input) && !empty($input['token'])){
        
            $token = optional(explode('_', zcjy_base64_de($input['token'])));
            //Log::info($token);
            $user = User::find($token[0]);
            if(empty($user)){
                $status = 'token信息验证失败';
            }
            return $status;
            if($user->id == $token[0] && $user->nickname == $token[1] && strtotime($user->created_at) == $token[2] ){
                session(['zcjy_api_user_'.$token[0] => $user]);
            }
            else{
                $status = 'token信息验证失败';
            }

        }
        else{
            $status = 'token信息验证失败';
        }
        return $status;
    }
    
}
