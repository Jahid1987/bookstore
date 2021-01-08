<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\user;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
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
    protected $redirectTo = '/products';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
        /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->role->name == 'admin'){
            $this->redirectTo = '/admin/dashboard';
        }


        // switch ($user->role->name) {
        //     case 'admin':
        //         $this->redirectTo = 'admin/dashboard';
        //         break;
        //     case 'editor':
        //         $this->redirectTo = '/editor';
        //         break;
            
        //     default:
        //         $this->redirctTo = '/home';
        //         break;
        // }
    }
}
