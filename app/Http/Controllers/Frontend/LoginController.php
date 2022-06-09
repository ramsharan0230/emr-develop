<?php

namespace App\Http\Controllers\Frontend;

use App\AccessComp;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;


/**
 * Class LoginController
 * @package App\Http\Controllers\Frontend
 */
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
    protected $redirectTo = 'frontend/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin_frontend')->except('logout');
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        $data['group_name'] = AccessComp::select('name')->where('status', 'active')->get();
        return view('adminlogin::login',$data);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function login(Request $request)
    {

        $validator = $request->validate([
            'username' => 'required',
            'password' => 'required|min:6'
        ]);


        if (Auth::guard('admin_frontend')->attempt(array(
            'flduserid' => $request->input('username'),
            'fldpass'   => $request->input('password')
        ), true)) {

            return redirect()->route('frontend.dashboard');
        } else {
            return redirect()->back()->with('message', 'Username or password invalid..');
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }


    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'fldpass'         => 'required|string',
        ]);
    }


    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('admin_frontend')->logout();
        Session::flush();
        return redirect()->route('cogent.login.form');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin_frontend');
    }
}
