<?php

namespace App\Http\Controllers;

use App\PatientCredential;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;

class PatientLogin extends Controller
{
    use Authenticatable;

    public function __construct()
    {
        $this->middleware('guest:patient_admin')->except('logout');
    }

    public function loginForm()
    {
        return view('patient.login');
    }

    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validator($request);
        $pwd = $request->get('password');
        $generated_pwd = "";
        for ($i = 0; $i < strlen($pwd); $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }

        $user = PatientCredential::where('fldusername', $request->get('username'))
            ->first();

        /**USER DOES NOT EXITS*/
        if (!$user) {
            return redirect()->route('patient.portal.login.show.form')->with('error_message', 'Invalid username ofasdfsdfr password.');
        }

        /**CHECK USER PASSWORD*/
        if (!($generated_pwd == $user->fldpassword)) {
            return redirect()->route('patient.portal.login.show.form')->with('error_message', 'Invalid username or password.');
        }

        if ($user) {
            \Auth::guard('patient_admin')->login($user);
            // setting the cookies for remember me
            if ($request->get('remember-me') == "yes") {
                //setting cookie for a year
                setcookie("patient_admin_rem_username", $request->get('username'), time() + 31556926, '/');
                try {
                    setcookie("patient_admin_rem_password", Crypt::encrypt($request->get('password')), time() + 31556926, '/');
                } catch (\Exception $e) {

                }
            } else {
                unset($_COOKIE['patient_admin_rem_username']);
                unset($_COOKIE['patient_admin_rem_password']);
                setcookie('patient_admin_rem_username', null, -1, '/');
                setcookie('patient_admin_rem_password', null, -1, '/');
            }
            return redirect()->route('patient.portal.dashboard');
        } else {
            return redirect()->route('patient.portal.login.show.form')->with('error_message', 'Invalid username ofasdfsdfr password.');
        }

    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        \Auth::guard('patient_admin')->logout();
        return redirect()
            ->route('patient.portal.login.show.form')
            ->with('success_message', 'Admin has been logged out!');
    }

    /**
     * Validate the form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'username' => 'required|exists:tblpatientcredential,fldusername|min:5|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'username.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules, $messages);
    }

    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error_message', 'Login failed, please try again!');
    }
}
