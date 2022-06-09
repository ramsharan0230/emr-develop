<?php

namespace Modules\AdminLogin\Http\Controllers;

use App\Advertisement;
use App\CogentUsers;
use App\GroupComputerAccess;
use App\GroupMac;
use App\HospitalDepartment;
use App\Http\Controllers\Frontend\Helpers;
use App\RequestMacAccess;
use App\UserGroup;
use App\Utils\Helpers as UtilsHelpers;
use App\Utils\Options;
use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Milon\Barcode\DNS2D;
use Session;
use Validator;

class AdminLoginController extends Controller
{
    use Authenticatable, Notifiable;

    //    protected $macAddr;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin_frontend')->except('logOut');
    }

    public function index()
    {
        //        $data['advertisement'] = Advertisement::get();
        return view('adminlogin::login');
    }

    public function submit(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
            /*'department' => 'sometimes'*/
        ]);

        /**
         * For password generation
         */

        $pwd = $request->get('password');

        $generated_pwd = "";
        for ($i = 0, $iMax = strlen($pwd); $i < $iMax; $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }

        $user = CogentUsers::where('username', $request->get('username'))
            ->first();

        $currentdate = date('Y-m-d');


        /**USER DOES NOT EXITS*/
        if (!$user) {
            UtilsHelpers::logStack(["User Not Found", "Error"]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }
        /**USER EXPIRED*/
        if ((!(isset($user->user_is_superadmin) && count($user->user_is_superadmin) < 0)) && $user->fldexpirydate < date('Y-m-d')) {
            UtilsHelpers::logStack([$user->fldfullname . " user Expired", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', 'User Expired, please contact admin.');
        }

        //        /**USER EXPIRED*/
        //        if (!isset($user->user_is_superadmin) && count($user->user_is_superadmin) < 0 && $user->fldexpirydate < date('Y-m-d')) {
        //            return redirect()->route('cogent.login.form')->with('error_message', 'User Expired, please contact admin.');
        //        }

        /**DELETED USER*/
        if ($user->status == Helpers::DELETED) {
            UtilsHelpers::logStack([$user->fldfullname . " user Already Deleted", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }

        /**INACTIVE USER*/
        if ($user->status == Helpers::INACTIVE) {
            UtilsHelpers::logStack([$user->fldfullname . " user is inactive", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }

        /**CHECK USER PASSWORD*/
        if (!($generated_pwd == $user->password)) {
            UtilsHelpers::logStack([$user->fldfullname . " password doesnot match", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }

        if (!isset($user->user_is_superadmin) && count($user->user_is_superadmin) <= 0) {
            /**IF USER IS EXPIRED*/
            if ($currentdate > $user->fldexpirydate) {
                UtilsHelpers::logStack([$user->fldfullname . " user login credential expired", "Access", $user->id]);
                return redirect()->route('cogent.login.form')->with('error_message', 'User login credential expired. Please contact system admin.');
            }
        }

        /**Check if 2fa is enabled and user has enabled 2fa*/

        if ($user->two_fa == 1) {
            if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            } else {
                if (Options::get('system_2fa') == 1) {
                    if ($user->secret_key_2fa == null || $user->enabled_2fa == 0) {
                        $hash = $this->createHash($user->username);
                        $user->update(['secret_key_2fa' => $hash]);
                        $twoFA = new TwoFactorAuthenticationController();

                        $otp = $twoFA->createOtp($hash, $user->username, 'cogent');

                        $urlForBarcode = $otp->getProvisioningUri();

                        $dataQr['qrCode'] = DNS2D::getBarcodeHTML($urlForBarcode, 'QRCODE', 4, 4);
                        $dataQr['username'] = $user->username;

                        return view('adminlogin::barcode-otp', $dataQr);
                    }

                    $dataQr['advertisement'] = Advertisement::get();
                    $dataQr['qrCode'] = false;
                    $dataQr['username'] = $user->username;
                    return view('adminlogin::barcode-otp', $dataQr);
                }
            }
        }

        if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
            $hospital_departments = HospitalDepartment::all();
            Session::put('user_hospital_departments', $hospital_departments);
            Session::put('selected_user_hospital_department', $hospital_departments->first());
            /*if user is super admin login*/
            $remember_me = $request->has('remember-me') ? true : false;


            if ($user) {
                Auth::guard('admin_frontend')->login($user);
                // setting the cookies for remember me
                if ($request->get('remember-me') == "yes") {
                    //setting cookie for a year
                    setcookie("admin_rem_username", $request->get('username'), time() + 31556926, '/');
                    try {
                        setcookie("admin_rem_password", Crypt::encrypt($request->get('password')), time() + 31556926, '/');
                    } catch (\Exception $e) {
                    }
                } else {
                    unset($_COOKIE['admin_rem_username']);
                    unset($_COOKIE['admin_rem_password']);
                    setcookie('admin_rem_username', null, -1, '/');
                    setcookie('admin_rem_password', null, -1, '/');
                }
                UtilsHelpers::logStack([$user->fldfullname . " logged in", "Access", $user->id]);
            }
            UtilsHelpers::syncAuthGuards();
            return redirect()->route('admin.dashboard');
        } else {
            $MacGroupData = GroupMac::whereHas('request', function ($query) use ($request) {
                $query->where('flduserid', $request->get('username'));
                //                $query->where('category', $request->get('department'));
            })->first();

            $userCheckGroup = CogentUsers::where('username', $request->get('username'))
                /*->whereHas('groups.computer_access', function ($query) use ($request) {
                    $query->where('name', $request->get('department'));
                })*/
                ->first();

            if (!$MacGroupData && !$userCheckGroup) {
                UtilsHelpers::logStack([$user->fldfullname . " user doesnt have access", "Access", $user->id]);
                return redirect()->route('cogent.login.form')->with('error_message', 'User doesnt have access.');
            }

            if ($userCheckGroup) {
                if (count($userCheckGroup->hospitalDepartment) > 0) {
                    Session::put('user_hospital_departments', $userCheckGroup->hospitalDepartment);
                    Session::put('selected_user_hospital_department', $userCheckGroup->hospitalDepartment->first());
                }
                //                Session::put('department', $request->get('department'));
                if ($user) {
                    Auth::guard('admin_frontend')->login($user);
                    // setting the cookies for remember me
                    if ($request->get('remember-me') == "yes") {
                        //setting cookie for a year
                        setcookie("admin_rem_username", $request->get('username'), time() + 31556926, '/');
                        try {
                            setcookie("admin_rem_password", Crypt::encrypt($request->get('password')), time() + 31556926, '/');
                        } catch (\Exception $e) {
                        }
                    } else {
                        unset($_COOKIE['admin_rem_username']);
                        unset($_COOKIE['admin_rem_password']);
                        setcookie('admin_rem_username', null, -1, '/');
                        setcookie('admin_rem_password', null, -1, '/');
                    }
                    UtilsHelpers::logStack([$user->fldfullname . " logged in", "Access", $user->id]);
                }
                UtilsHelpers::syncAuthGuards();
                return redirect()->route('admin.dashboard');
            }

            if ($MacGroupData) {

                /*Session::put('department', $request->get('department'));*/
                /*echo $MacGroupData->group_id;
                echo '<br>';*/
                if ($groupData = GroupComputerAccess::where('computer_access_id', $MacGroupData->group_id)->pluck('group_id')) {
                    /*var_dump($groupData);
                    echo '<br>';*/

                    if ($userPermission = UserGroup::whereIn('group_id', $groupData)->where('user_id', $user->id)->exists()) {
                        $remember_me = $request->has('remember-me') ? true : false;
                        if ($user) {
                            Auth::guard('admin_frontend')->login($user);
                            // setting the cookies for remember me
                            if ($request->get('remember-me') == "yes") {
                                //setting cookie for a year
                                setcookie("admin_rem_username", $request->get('username'), time() + 31556926, '/');
                                try {
                                    setcookie("admin_rem_password", Crypt::encrypt($request->get('password')), time() + 31556926, '/');
                                } catch (\Exception $e) {
                                }
                            } else {
                                unset($_COOKIE['admin_rem_username']);
                                unset($_COOKIE['admin_rem_password']);
                                setcookie('admin_rem_username', null, -1, '/');
                                setcookie('admin_rem_password', null, -1, '/');
                            }
                            UtilsHelpers::logStack([$user->fldfullname . " logged in", "Access", $user->id]);
                        }
                        UtilsHelpers::syncAuthGuards();
                        return redirect()->route('admin.dashboard');
                    } else {
                        UtilsHelpers::logStack([$user->fldfullname . " do not have permission on this computer", "Access", $user->id]);
                        return redirect()->route('cogent.login.form')->with('error_message', 'You do not have permission on this computer.');
                    }
                } else {
                    UtilsHelpers::logStack([$user->fldfullname . " do not have permission on this computer", "Access", $user->id]);
                    return redirect()->route('cogent.login.form')->with('error_message', 'You do not have permission on this computer.');
                }
            } else {
                UtilsHelpers::logStack([$user->fldfullname . " do not have permission on this computer", "Access", $user->id]);
                return redirect()->route('cogent.login.form')->with('error_message', 'You do not have permission on this computer.');
            }
        }
    }

    public function firstLogin2fa(Request $request)
    {
        $otp = $request->otp;
        $twoFA = new TwoFactorAuthenticationController();
        $user = CogentUsers::where('username', $request->get('username'))
            ->first();

        /**USER DOES NOT EXITS*/
        if (!$user) {
            UtilsHelpers::logStack(["User Not Found", "Error"]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }
        /**DELETED USER*/
        if ($user->status == Helpers::DELETED) {
            UtilsHelpers::logStack([$user->fldfullname . " user Already Deleted", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }

        /**INACTIVE USER*/
        if ($user->status == Helpers::INACTIVE) {
            UtilsHelpers::logStack([$user->fldfullname . " user is inactive", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', 'Invalid username or password.');
        }

        if ($twoFA->verify($user->secret_key_2fa, $otp)) {
            $MacGroupData = GroupMac::whereHas('request', function ($query) use ($request) {
                $query->where('flduserid', $request->get('username'));
                //                $query->where('category', $request->get('department'));
            })->first();

            $userCheckGroup = CogentUsers::where('username', $request->get('username'))
                /*->whereHas('groups.computer_access', function ($query) use ($request) {
                    $query->where('name', $request->get('department'));
                })*/
                ->first();

            if (!$MacGroupData && !$userCheckGroup) {
                UtilsHelpers::logStack([$user->fldfullname . " doesnt have access", "Access", $user->id]);
                return redirect()->route('cogent.login.form')->with('error_message', 'User doesnt have access.');
            }

            if ($userCheckGroup) {
                if (count($userCheckGroup->hospitalDepartment) > 0) {
                    Session::put('user_hospital_departments', $userCheckGroup->hospitalDepartment);
                    Session::put('selected_user_hospital_department', $userCheckGroup->hospitalDepartment->first());
                }
                //                Session::put('department', $request->get('department'));
                if ($user) {
                    $user->update(['enabled_2fa' => 1]);
                    Auth::guard('admin_frontend')->login($user);
                    UtilsHelpers::logStack([$user->fldfullname . " logged in", "Access", $user->id]);
                }
                UtilsHelpers::syncAuthGuards();
                return redirect()->route('admin.dashboard');
            }
        } else {
            UtilsHelpers::logStack([$user->fldfullname . " two factor authentication failed", "Access", $user->id]);
            return redirect()->route('cogent.login.form')->with('error_message', __('messages.error'));
        }
    }

    public function logOut(Request $request)
    {
        Auth::guard('admin_frontend')->logout();
        Session::flush();
        return redirect()->route('cogent.login.form');
    }

    public function requestAccess()
    {
        //        $data['macAddress'] = $this->macAddr->GetMacAddr(PHP_OS);
        //        $data['group_name'] = AccessComp::select('name')->where('status', 'active')->get();
        return view('adminlogin::request-access');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRequest(Request $request)
    {
        $rules = array(
            //            'firstname' => 'required',
            'macAddress' => 'required',
            'username' => 'required',
            'catagory' => 'required',
            //            'email' => 'required|email',
            //            'password' => 'required',
            //            're_password' => 'required|same:password',
        );

        $validator = Validator::make($request->all(), $rules);

        //extending the validation to further validation
        /*$validator->after(function ($validator) {

            //checking username
            if ($this->check_username()) {
                $validator->errors()->add('username', 'Username already taken.');
            }

        });*/

        if ($validator->fails()) {
            return redirect()->route('admin.request.access')->withErrors($validator)->withInput();
        }
        try {


            // 1 : Inserting in users table
            //            $MAC = $request->macAddress;

            /*if (RequestMacAccess::where('hostmac', $MAC)->exists()){
                Session::flash('error_message', 'This mac have already requested for access.');
                return redirect()->route('cogent.login.form');
            }*/
            /*if (!CogentUsers::where('username', $request->username)->exists()){
                Session::flash('error_message', 'Username doesnt exist.');
                return redirect()->route('cogent.login.form');
            }*/

            if (RequestMacAccess::where('flduserid', $request->username)->where('category', $request->catagory)->exists()) {
                Session::flash('error_message', 'User already applied.');
                return redirect()->route('cogent.login.form');
            }

            $user_data = [
                //                'hostmac' => $MAC,
                //            'firstname' => $request->get('firstname'),
                //            'middlename' => $request->get('middlename'),
                //            'lastname' => $request->get('lastname'),
                'flduserid' => $request->get('username'),
                'category' => $request->get('catagory'),
                //            'email' => $request->get('email'),
                //            'password' => $this->passwordGenerate($request->get('password')),
                'status' => 'inactive',
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time')
            ];

            $user_id = RequestMacAccess::insertGetId($user_data);

            Session::flash('success_message', 'User access request added successfully.');
            return redirect()->route('cogent.login.form');
        } catch (\GearmanException $e) {
            Session::flash('error_message', __('messages.error'));
            return redirect()->route('cogent.login.form');
        }
    }

    public function passwordGenerate($password)
    {
        $pwd = $password;

        $generated_pwd = "";
        for ($i = 0, $iMax = strlen($pwd); $i < $iMax; $i++) {
            $current_string = substr($pwd, $i, 1);
            $temp_ascii = ord($current_string);
            if (strlen($temp_ascii) == 1) {
                $temp_ascii = "00" . $temp_ascii;
            } elseif (strlen($temp_ascii) == 2) {
                $temp_ascii = "0" . $temp_ascii;
            }
            $generated_pwd .= $temp_ascii;
        }
        return $generated_pwd;
    }

    private function check_username()
    {
        //$count = CogentUsers::where('status','!=','deleted')->where('username',Input::get('username'))->count();
        $count = RequestMacAccess::where('flduserid', Input::get('username'))->count();
        return $count > 0 ? true : false;
    }

    private function check_email()
    {
        //$count = CogentUsers::where('status','!=','deleted')->where('email',Input::get('email'))->count();
        $count = RequestMacAccess::where('email', Input::get('email'))->count();
        return $count > 0 ? true : false;
    }

    private function createHash($username)
    {
        return $username . time() . rand(1000, 9999);
    }

    public function forgotPassword()
    {
        return view('adminlogin::forgot-password');
    }

    public function broker()
    {
        return Password::broker('admins');
    }

    public function submitForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('cogent.login.form')->with(['message' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function successReset()
    {
        $data['advertisement'] = Advertisement::get();
        return view('adminlogin::login', $data)->with('success_message', 'An email with reset password has been sent.');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->token;
        return view('auth.passwords.reset', compact('token'));
    }
}
