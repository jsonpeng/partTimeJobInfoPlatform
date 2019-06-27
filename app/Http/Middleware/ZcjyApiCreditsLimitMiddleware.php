<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

use App\User;

use EasyWeChat\Factory;


class ZcjyApiCreditsLimitMiddleware
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
        $varify = app('zcjy')->creditsLimitVarify($request->all());

        if($varify){
            return zcjy_callback_data($varify,1);
        }
    
        return $next($request);
    }
    
}
