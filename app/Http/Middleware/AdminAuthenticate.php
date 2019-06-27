<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Route,URL;
use Illuminate\Support\Facades\Log;

class AdminAuthenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard('admin')->guest()) { 
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else { 
                return redirect()->guest('zcjy/login');
            } 
        }
         // $admin=Auth::guard('admin')->user();
         // dd($admin);
        /*
        $admin=Auth::guard('admin')->user();
        $uri=Route::currentRouteName();

        if(Route::currentRouteName()) {
            if (!$admin->can($uri)) {
                if ($request->getMethod() == 'GET') {
                    if(!varifyAllRouteByAdminObj($admin,$uri)) {
                        return redirect('/403');
                    }
                }
            }
        }
        */
        return $next($request);
    }
}
