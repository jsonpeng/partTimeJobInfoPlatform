<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class BindMiddleware
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
        // if (getSettingValueByKeyCache('account_bind') == '是') {
        //     $user = auth('web')->user();
        //     if (empty($user->mobile)) {
        //         return redirect('/mobile');
        //     }
        // }
        return $next($request);
    }
}
