<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/zcjy';

    private $redirectAfterLogout = '/zcjy';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        $this->middleware('guest:admin', ['except' => 'logout']); 
    }


    protected function guard() 
    { 
        return auth()->guard('admin'); 
    } 

      /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {

        if (property_exists($this, 'redirectPath')) {
            Log::info($this->redirectPath);
            return $this->redirectPath;
        }
        Log::info(property_exists($this, 'redirectTo') ? $this->redirectTo : '/zcjy');
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/zcjy';
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/zcjy');
    }



}
