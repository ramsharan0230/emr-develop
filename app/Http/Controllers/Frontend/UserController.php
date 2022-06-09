<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        /**
         * For password generation
         */

        $pwd           = $request->get('password');
        $generated_pwd = "";
        for ($i = 0; $i < strlen($pwd); $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii     = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }

        $user = User::where('flduserid', $request->get('username'))
            ->first();
        /**USER DOES NOT EXITS*/
        if (!$user) {
            return redirect()->route('frontend.login')->with('error_message', 'Invalid username or password.');
        }

        /**DELETED USER*/
        if ($user->fldstatus == Helpers::DELETED) {
            return redirect()->route('frontend.login')->with('error_message', 'Invalid username or password.');
        }

        /**INACTIVE USER*/
        if ($user->fldstatus == Helpers::INACTIVE) {
            return redirect()->route('frontend.login')->with('error_message', 'Invalid username or password.');
        }

        /**CHECK USER PASSWORD*/
        if (!($generated_pwd == $user->fldpass)) {
            return redirect()->route('frontend.login')->with('error_message', 'Invalid username or password.');
        }

        $remember_me = $request->has('remember-me') ? true : false;

        if ($user) {
//            Auth::guard('admin_frontend')->login($user, $remember_me);
            Auth::guard('admin_frontend')->login($user);
        }

        return redirect()->route('frontend.dashboard');
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('frontend.login');
    }
}
