<?php

namespace Modules\AdminUser\Http\Controllers;

use App\AccessComp;
use App\Department;
use App\Group;
use App\GroupComputerAccess;
use App\PermissionGroup;
use App\PermissionModule;
use App\CogentUsers;
use App\HospitalDepartment;
use App\HospitalDepartmentUsers;
use App\SidebarMenu;
use App\UserDepartment;
use App\UserDetail;
use App\UserGroup;
use App\Utils\Helpers;
use App\Utils\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Validator;
use Session;
use Auth;
use File;
use Hash;
use Illuminate\Support\Facades\Input;
use App\Utils\Options;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\AdminUser\Http\Requests\PermissionSetupRequest;
use Modules\AdminUser\Services\Contracts\AdminUserContract;
use Illuminate\Support\Str;

/**
 * Class AdminUserController
 * @package Modules\AdminUser\Http\Controllers
 */
class AdminUserController extends Controller
{
    /**
     * initializing multiple using class and dipendency Injection
     * @param PermissionModule
     * @use of constructor
     */
    public function __construct(PermissionModule $permissionModule, SidebarMenu $sidebarMenu, AdminUserContract $adminUserContract, Group $group)
    {
        $this->permissionModule = $permissionModule;
        $this->sidebarMenu = $sidebarMenu;
        $this->adminUserContract = $adminUserContract;
        $this->group = $group;
    }
    // Profile Update
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Profile</li>';
        $data['title'] = "Profile - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['user_details'] = Auth::guard('admin_frontend')->user();
        return view('adminuser::profile', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profileStore(Request $request)
    {
        $rules = array(
            // 'firstname' => 'required',
            // 'username' => 'required',
            //            'email' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        //extending the validation to further validation
        $validator->after(function ($validator) use ($request) {

            //checking username
            if ($request->get('old_username') != $request->get('username')) {
                if ($this->check_username()) {
                    $validator->errors()->add('username', 'Username already taken.');
                }
            }


            //checking email
            if ($request->get('old_email') != $request->get('email')) {
                if ($this->check_email()) {
                    $validator->errors()->add('email', 'Email already taken.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.user.profile')->withErrors($validator)->withInput();
        }


        // 1 : Updating in users table
        $user_data = [
            // 'username' => $request->get('username'),
            // 'flduserid' => $request->get('username'),
            'email' => $request->get('email'),
            // 'firstname' => $request->get('firstname'),
            // 'middlename' => $request->get('middlename'),
            // 'lastname' => $request->get('lastname'),
            'nmc' => $request->get('nmc_number'),
            'updated_at' => config('constants.current_date_time')
        ];
        if ($request->hasFile('profile_image')) {
            /*profile image crop*/
            if ($request->x2 == NULL) {
                $request->x2 = 400;
            }
            if ($request->y2 == NULL) {
                $request->y2 = 400;
            }

            $width = $request->w;
            if ($width == 0) {
                $width = 400;
            }
            $height = $request->h;
            if ($height == 0) {
                $height = 400;
            }
            $file = $request->file('profile_image');
            $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();
            $path = 'emr/user/'.$filename;


            if (!file_exists(public_path('uploads/images/user/fullimage')))
                mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
            if (!file_exists(public_path('uploads/images/croppedimage')))
                mkdir(public_path('uploads/images/croppedimage'), 0777, true);

            $fullimagedestination = public_path() . '/uploads/images/fullimage';
            $file->move($fullimagedestination, $filename);

            $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
            $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
            $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
            $image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
            if(isset($image)){
                $user = CogentUsers::where('id',Auth::guard('admin_frontend')->user()->id)->first();
                if($user->profile_image_link){

                    $exists = Storage::disk('minio')->has($user->profile_image_link);
                    
                    if($exists){
                    
                    Storage::disk('minio')->delete($user->profile_image_link);
                    
                    }
            }
        }
            try{
                Storage::disk('minio')->put($path, file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
        
            }catch(\Exception $e){
                Helpers::logStack([$e->getMessage() . ' in user profile image', "Error"]);
            }
            @unlink(public_path('uploads/images/fullimage/' . $filename));
            @unlink(public_path('uploads/images/croppedimage/' . $filename));

            /*profile image crop*/
            $user_data['profile_image_link'] = $path;
            $user_data['profile_image'] = $image;
        }
        if ($request->hasFile('signature_image')) {
            /*signature image crop*/
            if ($request->x2s == NULL) {
                $request->x2s = 400;
            }
            if ($request->y2s == NULL) {
                $request->y2s = 400;
            }

            $width = $request->ws;
            if ($width == 0) {
                $width = 400;
            }
            $height = $request->hs;
            if ($height == 0) {
                $height = 400;
            }
            $file = $request->file('signature_image');
            $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();
            $signature_path = 'emr/user/signature/'.$filenamesignature;

            if (!file_exists(public_path('uploads/images/user/fullimage')))
                mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
            if (!file_exists(public_path('uploads/images/croppedimage')))
                mkdir(public_path('uploads/images/croppedimage'), 0777, true);

            $fullimagedestination = public_path() . '/uploads/images/fullimage';
            $file->move($fullimagedestination, $filenamesignature);

            $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
            $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
            $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
            $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
            if(isset($signature_image)){
                $user = CogentUsers::where('id',Auth::guard('admin_frontend')->user()->id)->first();
                if($user->signature_image_link){

                    $exists = Storage::disk('minio')->has($user->signature_image_link);
                    
                    if($exists){
                    
                    Storage::disk('minio')->delete($user->signature_image_link);
                    
                    }
            }
        }
            try{
                Storage::disk('minio')->put($signature_path, file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
        
            }catch(\Exception $e){
                Helpers::logStack([$e->getMessage() . ' in user signature image', "Error"]);
            }
            @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
            @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));

            /*signature image crop*/
            $user_data['signature_image_link'] = $signature_path;
            $user_data['signature_image'] = $signature_image;
        }

        CogentUsers::where('id', Auth::guard('admin_frontend')->user()->id)->update($user_data);

        // 2 : Updating in users details table
        $user_details_data = [
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'updated_at' => config('constants.current_date_time')
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $user_details_data['image'] = time() . '-' . rand(111111, 999999) . '.' . $image->getClientOriginalExtension();

            $path = public_path() . "/uploads/profile/";

            $image->move($path, $user_details_data['image']);
        }

        //checking if record exists or not
        if (UserDetail::where('user_id', Auth::guard('admin_frontend')->user()->id)->count() == 0) {

            $user_details_data['created_at'] = config('constants.current_date_time');
            $user_details_data['user_id'] = Auth::guard('admin_frontend')->user()->id;
            UserDetail::insert($user_details_data);
        } else {

            UserDetail::where('user_id', Auth::guard('admin_frontend')->user()->id)->update($user_details_data);
        }

        Session::flash('success_message', 'Profile updated successfully.');
        return redirect()->route('admin.user.profile');
    }

    // Password Reset

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordReset()
    {
        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Password Reset</li>';
        $data['title'] = "Password Reset - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        return view('adminuser::password_reset', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordResetStore(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);

            if ($this->passwordGenerate($request->get('current_password')) != Auth::guard('admin_frontend')->user()->password) {
                Session::flash('error_message', 'Invalid current password.');
                return redirect()->route('admin.user.password-reset');
            }
            //dd(Auth::guard('admin_frontend')->user()->id);
            // Resetting the password
            $change = CogentUsers::find(Auth::guard('admin_frontend')->user()->id)->update([
                'password' => $this->passwordGenerate($request->get('new_password')),
                'updated_at' => config('constants.current_date_time')
            ]);

            if ($change) {
                Session::flash('success_message', 'Password changed successfully.');
                return redirect()->route('admin.user.password-reset');
            }
        } catch (\GearmanException $e) {
            dd($e);
        }
    }

    // Users

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userLists()
    {
        //if (!Permission::checkPermissionFrontendAdmin('list-user'))
        // return redirect()->route('access-forbidden');

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Users Management</li>';
        $data['title'] = "User Management - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['header_nav'] = 'users';
        $data['users'] = CogentUsers::where('status', '!=', 'deleted')->with(['user_details', 'user_group.group_detail', 'user_group', 'user_is_superadmin'])->get();

        return view('adminuser::user', $data);
    }

    public function userview(Request $request)
    {
        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Users Management</li>';
        $data['title'] = "User Management - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['header_nav'] = 'users';
        $data["group"] = $request->group ?? "";
        $data["department"] = $request->department ?? "";
        $data["hospital_department"] = $request->hospital_department ?? "";
        $data["role"] = $request->role ?? "";
        $data['users'] = CogentUsers::where('status', '!=', 'deleted')
            ->when($request->group, function ($query) use ($request) {
                $query->whereHas('user_group', function ($query) use ($request) {
                    $query->where('group_id', $request->groups);
                });
            })
            ->when($request->department, function ($query) use ($request) {
                $query->whereHas('department', function ($query) use ($request) {
                    $query->where('department_id', $request->department);
                });
            })
            ->when($request->hospital_department, function ($query) use ($request) {
                $query->whereHas('hospitalDepartment', function ($query) use ($request) {
                    $query->where('hospital_department_id', $request->hospital_department);
                });
            })
            ->when($request->role == "faculty", function ($query) {
                $query->where('fldfaculty', 1);
            })
            ->when($request->role == "payable", function ($query) {
                $query->where('fldpayable', 1);
            })
            ->when($request->role == "referral", function ($query) {
                $query->where('fldreferral', 1);
            })
            ->when($request->role == "consultant", function ($query) {
                $query->where('fldopconsult', 1);
            })
            ->when($request->role == "ip_clinician", function ($query) {
                $query->where('fldipconsult', 1);
            })
            ->when($request->role == "signature", function ($query) {
                $query->where('fldsigna', 1);
            })
            ->when($request->role == "data_export", function ($query) {
                $query->where('fldreport', 1);
            })
            ->with('user_details', 'user_group.group_detail', 'user_group')->get();
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['departments'] = Department::all();
        return view('adminuser::user_view', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userAdd()
    {
        // if (!Permission::checkPermissionFrontendAdmin('add-user'))
        //     return redirect()->route('access-forbidden');

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();


        $data['department'] = Department::all();
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['user_category'] = CogentUsers::select('fldcategory')->where('fldcategory', '!=', null)->distinct()->get();
        // echo $data['user_category']; exit;
        return view('adminuser::user_add', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userAddNew()
    {
        // if (!Permission::checkPermissionFrontendAdmin('add-user'))
        //     return redirect()->route('access-forbidden');

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();


        $data['department'] = Department::all();
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['user_category'] = CogentUsers::select('fldcategory')->where('fldcategory', '!=', null)->distinct()->get();
        // echo $data['user_category']; exit;
        return view('adminuser::user_add_new', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userStore(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            're_password' => 'required|same:password',
            'groups' => 'required',
            'groups.*' => 'required',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            // 1 : Inserting in users table
            $profile_image = '';
            $signature_image = '';
            /*profile image crop*/
            if ($request->hasFile('profile_image')) {
                if ($request->x2 == NULL) {
                    $request->x2 = 400;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 400;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('profile_image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
               
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));
                $profile_image = $image;
            }

            /*profile image crop*/
            /*signature image crop*/
            if ($request->hasFile('signature_image')) {
                if ($request->x2s == NULL) {
                    $request->x2s = 400;
                }
                if ($request->y2s == NULL) {
                    $request->y2s = 400;
                }

                $width = $request->ws;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->hs;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('signature_image');
                $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filenamesignature);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
                $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
                @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
                @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));
            }

            /*signature image crop*/

            if ($request->designation_free != '') {
                $user_category = $request->designation_free;
            } else {
                $user_category = $request->designation;
            }
            $user_data = [
                'firstname' => $request->get('firstname'),
                'middlename' => $request->get('middlename'),
                'lastname' => $request->get('lastname'),
                'username' => $request->get('username'),
                'flduserid' => $request->get('username'),
                'fldnursing' => $request->get('nurse'),
                'email' => $request->get('email'),
                'password' => $this->passwordGenerate($request->get('password')),
                'status' => $request->get('status'),
                'nmc' => $request->get('nmc') ? $request->get('nmc') : NULL,
                'nhbc' => $request->get('nhpc') ? $request->get('nhpc') : NULL,
                'nnc' => $request->get('nnc') ? $request->get('nnc') : NULL,
                'npc' => $request->get('npc') ? $request->get('npc') : NULL,
                'fldexpirydate' => $request->expirydate,
                'signature_title' => $request->get('signature_title'),
                'profile_image' => isset($image) ? $image : '',
                'signature_image' => isset($signature_image) ? $signature_image : '',
                'fldcategory' => ucfirst($user_category),
                'fldfaculty' => $request->get('faculty') ? 1 : 0,
                'fldpayable' => $request->get('payable') ? 1 : 0,
                'fldreferral' => $request->get('referral') ? 1 : 0,
                'fldopconsult' => $request->get('consultant') ? 1 : 0,
                'fldipconsult' => $request->get('ip_clinician') ? 1 : 0,
                'fldsigna' => $request->get('signature') ? 1 : 0,
                'fldreport' => $request->get('data_export') ? 1 : 0,
                'two_fa' => $request->get('two_fa'),
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            $user_id = CogentUsers::insertGetId($user_data);
            Helpers::logStack(["User created", "Event"], ['current_data' => $user_data]);
            if (!$user_id) {
                DB::rollBack();
                Session::flash('error_message', 'Something went wrong. Please try again.');
                return redirect()->route('admin.user.add');
            }

            // 2 : Updating in users details table
            $user_details_data = [
                'user_id' => $user_id,
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            UserDetail::insert($user_details_data);
            Helpers::logStack(["User detail created", "Event"], ['current_data' => $user_details_data]);
            // 3 : Users Group Table
            $groups = $request->get('groups');
            if (count($groups) > 0) {
                $final_grps = [];
                foreach ($groups as $grps) {
                    $temp['user_id'] = $user_id;
                    $temp['group_id'] = $grps;
                    $temp['created_at'] = config('constants.current_date_time');
                    $temp['updated_at'] = config('constants.current_date_time');
                    $temp['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                    $final_grps[] = $temp;
                }
                if (count($final_grps) > 0) UserGroup::insert($final_grps);
                Helpers::logStack(["User group created", "Event"], ['current_data' => $final_grps]);
            }

            // 4 : Users department table
            $department = $request->get('department');
            if (is_array($department)) {
                $final_dept = [];
                foreach ($department as $dept) {
                    $temp_dept['user_id'] = $user_id;
                    $temp_dept['department_id'] = $dept;
                    $final_dept[] = $temp_dept;
                }
                if (count($final_dept) > 0) UserDepartment::insert($final_dept);
                Helpers::logStack(["User department created", "Event"], ['current_data' => $final_dept]);
            }

            // 5 : Users hospital department users table
            $hospitalDepartment = $request->get('hospital_department');
            if (isset($hospitalDepartment) && count($hospitalDepartment) > 0) {
                $final_hosp_dept = [];
                foreach ($hospitalDepartment as $hdept) {
                    $temp_hosp_dept['hospital_department_id'] = $hdept;
                    $temp_hosp_dept['user_id'] = $user_id;
                    $final_hosp_dept[] = $temp_hosp_dept;
                }
                if (count($final_hosp_dept) > 0) HospitalDepartmentUsers::insert($final_hosp_dept);
                Helpers::logStack(["User hospital department created", "Event"], ['current_data' => $final_hosp_dept]);
            }

            $success_mesg = "User created successfully. Please save the user credentials" . "<br>";
            $success_mesg .= "<strong>Username : " . $request->get('username') . "</strong><br>";
            $success_mesg .= "<strong>Password : " . $request->get('password') . "</strong>";

            DB::commit();
            Session::flash('success_message_special', $success_mesg);
            return redirect()->route('admin.user.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', $e->getMessage());
            Helpers::logStack([$e->getMessage() . ' in admin user create', "Error"]);
            return redirect()->route('admin.user.add');
        }
    }

    public function userStoreNew(Request $request)
    {
        $request->validate([
            'designation'           => 'required',
            'name'                  => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'username'              => 'required|unique:users,username',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required',
            // 'signature_title'       => 'required',
            // 'groups'                => 'required',
            // 'department'            => 'required',
            // 'hospital_department'   => 'required',
            // 'role'                  => 'required',
            // 'identification_type'   => 'required',
            // 'identification'        => 'required',
            'expirydate'            => 'required|date_format:Y-m-d|after:today',
            'nurse'                 => 'required',
            'status'                => 'required',
            'two_fa'                => 'required',
            'profile_image'         => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=400',
            'signature_image'       => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=200'
        ]);
        DB::beginTransaction();
        try {
            // 1 : Inserting in users table
            $profile_image = '';
            $signature_image = '';
            /*profile image crop*/
            if ($request->hasFile('profile_image')) {
                if ($request->x2 == NULL) {
                    $request->x2 = 400;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 400;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('profile_image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $profile_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));
            }

            /*profile image crop*/
            /*signature image crop*/
            if ($request->hasFile('signature_image')) {
                if ($request->x2s == NULL) {
                    $request->x2s = 400;
                }
                if ($request->y2s == NULL) {
                    $request->y2s = 400;
                }

                $width = $request->ws;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->hs;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('signature_image');
                $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filenamesignature);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
                $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
                @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
                @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));
            }
            /*signature image crop*/

            if ($request->designation_free != '') {
                $user_category = $request->designation_free;
            } else {
                $user_category = $request->designation;
            }
            $fullName = $request->name;
            $nameArray = explode(' ', $fullName);
            $firstName = array_shift($nameArray);
            $lastName = array_pop($nameArray);
            $middleName = implode(' ', $nameArray);
            $password = $this->generatePassword();
            $user_data = [
                'fldcategory' => ucfirst($user_category),
                'firstname' => $firstName,
                'middlename' => $middleName != "" ? $middleName : Null,
                'lastname' => $lastName,
                'username' => $request->username,
                'flduserid' => $request->username,
                'email' => $request->email,
                'password' => $this->passwordGenerate($password),
                'signature_title' => $request->get('signature_title'),
                'nmc' => $request->identification_type == 'nmc' ? $request->identification : NULL,
                'nhbc' => $request->identification_type == 'nhbc' ? $request->identification : NULL,
                'nnc' => $request->identification_type == 'nnc' ? $request->identification : NULL,
                'npc' => $request->identification_type == 'npc' ? $request->identification : NULL,
                'fldfaculty' => in_array('faculty', $request->role ?? []) ? 1 : 0,
                'fldpayable' => in_array('payable', $request->role ?? []) ? 1 : 0,
                'fldreferral' => in_array('referral', $request->role ?? []) ? 1 : 0,
                'fldopconsult' => in_array('consultant', $request->role ?? []) ? 1 : 0,
                'fldipconsult' => in_array('ip_clinician', $request->role ?? []) ? 1 : 0,
                'fldsigna' => in_array('signature', $request->role ?? []) ? 1 : 0,
                'fldreport' => in_array('data_export', $request->role ?? []) ? 1 : 0,
                'fldexpirydate' => $request->expirydate,
                'fldnursing' => $request->nurse,
                'status' => $request->status,
                'two_fa' => $request->two_fa,
                'profile_image' => isset($profile_image) ? $profile_image : '',
                'signature_image' => isset($signature_image) ? $signature_image : '',
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $user_id = CogentUsers::insertGetId($user_data);
            Helpers::logStack(["User created", "Event"], ['current_data' => $user_data]);
            if (!$user_id) {
                DB::rollBack();
                Session::flash('error_message', 'Something went wrong. Please try again.');
                return redirect()->route('admin.user.add.new');
            }

            // 2 : Updating in users details table
            $user_details_data = [
                'user_id' => $user_id,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            UserDetail::insert($user_details_data);
            Helpers::logStack(["User detail created", "Event"], ['current_data' => $user_details_data]);
            // 3 : Users Group Table
            $groups = $request->get('groups') ?? [];
            if (count($groups) > 0) {
                $final_grps = [];
                foreach ($groups as $grps) {
                    $temp['user_id'] = $user_id;
                    $temp['group_id'] = $grps;
                    $temp['created_at'] = config('constants.current_date_time');
                    $temp['updated_at'] = config('constants.current_date_time');
                    $temp['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                    $final_grps[] = $temp;
                }
                if (count($final_grps) > 0) UserGroup::insert($final_grps);
                Helpers::logStack(["User group created", "Event"], ['current_data' => $final_grps]);
            }

            // 4 : Users department table
            $department = $request->get('department') ?? [];
            if (count($department) > 0) {
                $final_dept = [];
                foreach ($department as $dept) {
                    $temp_dept['user_id'] = $user_id;
                    $temp_dept['department_id'] = $dept;
                    $final_dept[] = $temp_dept;
                }
                if (count($final_dept) > 0) UserDepartment::insert($final_dept);
                Helpers::logStack(["User department created", "Event"], ['current_data' => $final_dept]);
            }

            // 5 : Users hospital department users table
            $hospitalDepartment = $request->get('hospital_department') ?? [];
            if (count($hospitalDepartment) > 0) {
                $final_hosp_dept = [];
                foreach ($hospitalDepartment as $hdept) {
                    $temp_hosp_dept['hospital_department_id'] = $hdept;
                    $temp_hosp_dept['user_id'] = $user_id;
                    $final_hosp_dept[] = $temp_hosp_dept;
                }
                if (count($final_hosp_dept) > 0) HospitalDepartmentUsers::insert($final_hosp_dept);
                Helpers::logStack(["User hospital department created", "Event"], ['current_data' => $final_hosp_dept]);
            }

            $success_mesg = "User created successfully. Please save the user credentials.";
            $success_mesg .= "<strong>Username : " . $request->get('username') . "</strong>";
            $success_mesg .= "<strong>Password : " . $password . "</strong>";

            DB::commit();
            Session::flash('success_message_special', $success_mesg);
            return redirect()->route('admin.user.userview');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            Session::flash('error_message', $e->getMessage());
            Helpers::logStack([$e->getMessage() . ' in admin user create', "Error"]);
            return redirect()->route('admin.user.add.new')->withInput();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordResetUser(Request $request)
    {
        $rules = array(
            '_user_id' => 'required',
            'st_user_password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Session::flash('error_message', 'Invalid Customer Details. Please try again.');
            return redirect()->route('admin.user.list');
        }

        $customer_data = [
            'password' => $this->passwordGenerate($request->get('st_user_password')),
            'updated_at' => config('constants.current_date_time')
        ];

        CogentUsers::where('id', $request->get('_user_id'))->update($customer_data);
        Session::flash('success_message', 'User password updated successfully.');
        return redirect()->route('admin.user.list');
    }

    /**
     * @return bool
     */
    private function check_username()
    {
        //$count = CogentUsers::where('status','!=','deleted')->where('username',Input::get('username'))->count();
        $count = CogentUsers::where('username', Input::get('username'))->count();
        return $count > 0 ? true : false;
    }

    /**
     * @return bool
     */
    private function check_email()
    {
        //$count = CogentUsers::where('status','!=','deleted')->where('email',Input::get('email'))->count();
        $count = CogentUsers::where('email', Input::get('email'))->count();
        return $count > 0 ? true : false;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function userEdit($id)
    {
        // if (!Permission::checkPermissionFrontendAdmin('update-user'))
        //     return redirect()->route('access-forbidden');


        $data = array();
        $data['user_details'] = CogentUsers::where('id', $id)->where('status', '!=', 'deleted')->with('department')->first();

        if (!$data['user_details']) {
            Session::flash('error_message', 'Something went wrong. User records not found.');
            return redirect()->route('admin.user.list');
        }

        $data['user_type'] = 'others';
        // checking if this user is super admin
        $group_count = UserGroup::where('user_id', $id)->where('group_id', config('constants.role_super_admin'))->count();
        if ($group_count > 0) {
            $data['user_type'] = 'superadmin';
        }

        // checking if this user is super admin
        //$group_count = UserGroup::where('user_id',$id)->where('group_id',config('constants.role_super_admin'))->count();
        //if( $group_count > 0 ){
        //  Session::flash('error_message', 'Sorry ! You cannot edit this user.');
        // return redirect()->route('admin.user.list');
        //}

        //preparing user groups
        $data['current_user_grps'] = [];
        if (isset($data['user_details']->user_group) && count($data['user_details']->user_group) > 0) {
            foreach ($data['user_details']->user_group as $ugrp)
                $data['current_user_grps'][] = $ugrp->group_id;
        }

        $data['current_user_dept'] = [];
        if (isset($data['user_details']->department) && count($data['user_details']->department) > 0) {
            foreach ($data['user_details']->department as $dept) {
                $data['current_user_dept'][] = $dept->fldid;
            }
        }

        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['department'] = Department::all();

        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->get();

        $data['user_hospital_dept'] = [];
        if (isset($data['user_details']->hospitalDepartment) && count($data['user_details']->hospitalDepartment) > 0) {
            foreach ($data['user_details']->hospitalDepartment as $hospital_department) {
                $data['user_hospital_dept'][] = $hospital_department->id;
            }
        }

        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))->where('status', 'active')->get();
        $data['user_category'] = CogentUsers::select('fldcategory')->where('fldcategory', '!=', null)->distinct()->get();
        return view('adminuser::user_edit', $data);
    }

    public function userEditNew($id)
    {
        $data = array();
        $data['user'] = CogentUsers::where('id', $id)->where('status', '!=', 'deleted')->with('department', 'hospitalDepartment', 'user_group', 'user_details')->first();
        if (!$data['user']) {
            Session::flash('error_message', 'Something went wrong. User records not found.');
            return redirect()->route('admin.user.userview');
        }
        // if ($data['user']->user_is_superadmin) {
        //     Session::flash('error_message', 'Cannot edit this user.');
        //     return redirect()->route('admin.user.userview');
        // }
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.list') . '">Users</a></li><li>Create New User</li>';
        $data['title'] = "Create User - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['side_nav'] = 'users';
        $data['side_sub_nav'] = 'users';
        $data['groups'] = Group::where('id', '!=', config('constants.role_super_admin'))
            //->where('id','!=',config('constants.role_default_user'))
            ->where('status', 'active')
            ->get();


        $data['department'] = Department::all();
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
        $data['user_category'] = CogentUsers::select('fldcategory')->where('fldcategory', '!=', null)->distinct()->get();

        return view('adminuser::user_edit_new', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userUpdate(Request $request)
    {
        $user = CogentUsers::where('id', $request->get('_id'))->first();
        $group_count = UserGroup::where('user_id', $request->get('_id'))->where('group_id', config('constants.role_super_admin'))->count();
        $usertype = '';
        if ($group_count > 0) {
            $usertype = 'superadmin';
        }
        if ($usertype == 'superadmin') {
            $rules = array(
                'firstname' => 'required',
                // 'username' => 'required|unique:users,username,' . $request->get('_id'),
                'email' => 'required|email|unique:users,email,' . $request->get('_id'),
            );
        } else {
            $rules = array(
                'firstname' => 'required',
                // 'username' => 'required|unique:users,username,' . $request->get('_id'),
                'email' => 'required|email|unique:users,email,' . $request->get('_id'),
                'groups' => 'required',
                'groups.*' => 'required',
            );
        }


        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // 1 : Updating users table

            if ($request->designation_free != '') {
                $user_category = $request->designation_free;
            } else {
                $user_category = $request->designation;
            }

            $user_data = [
                // 'username' => $request->get('username'),
                'email' => $request->get('email'),
                'firstname' => $request->get('firstname'),
                'middlename' => $request->get('middlename'),
                'lastname' => $request->get('lastname'),
                'status' => $request->get('status'),
                'fldnursing' => $request->get('nurse'),
                'fldcategory' => ucfirst($user_category),
                'nmc' => $request->get('nmc'),
                'nhbc' => $request->get('nhbc'),
                'npc' => $request->get('npc'),
                'fldexpirydate' => $request->expirydate,
                'signature_title' => $request->get('signature_title'),
                'fldfaculty' => $request->get('faculty') ? 1 : 0,
                'fldpayable' => $request->get('payable') ? 1 : 0,
                'fldreferral' => $request->get('referral') ? 1 : 0,
                'fldopconsult' => $request->get('consultant') ? 1 : 0,
                'fldipconsult' => $request->get('ip_clinician') ? 1 : 0,
                'fldsigna' => $request->get('signature') ? 1 : 0,
                'two_fa' => $request->get('two_fa') === "active" ? 1 : 0,
                'fldreport' => $request->get('data_export') ? 1 : 0,
                'updated_at' => config('constants.current_date_time')
            ];

            if ($request->hasFile('profile_image')) {
                /*profile image crop*/
                if ($request->x2 == NULL) {
                    $request->x2 = 400;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 400;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('profile_image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                 
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));


                /*profile image crop*/
                $user_data['profile_image'] = $image;
            }
            if ($request->hasFile('signature_image')) {
                /*signature image crop*/
                if ($request->x2s == NULL) {
                    $request->x2s = 400;
                }
                if ($request->y2s == NULL) {
                    $request->y2s = 400;
                }

                $width = $request->ws;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->hs;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('signature_image');
                $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filenamesignature);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
                $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
                @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
                @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));

                /*signature image crop*/
                $user_data['signature_image'] = $signature_image;
            }
            $user = CogentUsers::where('id', $request->get('_id'))->first();
            $user->update($user_data);
            Helpers::logStack(["User updated", "Event"], ['current_data' => $user_data, 'previous_data' => $user]);
            // 2 : Updating in users details table
            $user_details_data = [
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'updated_at' => config('constants.current_date_time')
            ];

            $userDetail = UserDetail::where('user_id', $request->get('_id'))->first();
            $userDetail->update($user_details_data);
            Helpers::logStack(["User details updated", "Event"], ['current_data' => $user_details_data, 'previous_data' => $userDetail]);
            // 3 : Updating User Group Table
            if ($request->get('user_type') != 'superadmin') {
                $groups = $request->get('groups');
                if ($groups && count($groups) > 0) {
                    $final_grps = [];
                    foreach ($groups as $grps) {
                        $temp['user_id'] = $request->get('_id');
                        $temp['group_id'] = $grps;
                        $temp['created_at'] = config('constants.current_date_time');
                        $temp['updated_at'] = config('constants.current_date_time');
                        $temp['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                        $final_grps[] = $temp;
                    }

                    if ($final_grps && count($final_grps) > 0) {
                        //dropping previous data
                        $previous_final_grps = UserGroup::where(['user_id' => $request->get('_id')])->get();
                        UserGroup::where(['user_id' => $request->get('_id')])->delete();
                        UserGroup::insert($final_grps);
                        Helpers::logStack(["User group updated", "Event"], ['current_data' => $final_grps, 'previous_data' => $previous_final_grps]);
                    }
                }
            }

            $department = $request->get('department');
            if ($department && count($department) > 0) {
                $final_dept = [];
                foreach ($department as $dept) {
                    $temp_dept['user_id'] = $request->get('_id');
                    $temp_dept['department_id'] = $dept;
                    $final_dept[] = $temp_dept;
                }

                if ($final_dept && count($final_dept) > 0) {
                    //dropping previous data
                    $previous_final_dept = UserDepartment::where('user_id', $request->get('_id'))->first();
                    UserDepartment::where('user_id', $request->get('_id'))->delete();
                    UserDepartment::insert($final_dept);
                    Helpers::logStack(["User department updated", "Event"], ['current_data' => $final_dept, 'previous_data' => $previous_final_dept]);
                }
            }

            // 5 : Updating Hospital Department Users Table
            $hospitalDepartment = $request->get('hospital_department');

            //dropping previous data

            if ($hospitalDepartment && count($hospitalDepartment) > 0) {
                $final_hosp_dept = [];
                foreach ($hospitalDepartment as $hdept) {
                    $temp_hosp_dept['hospital_department_id'] = $hdept;
                    $temp_hosp_dept['user_id'] = $request->get('_id');
                    $final_hosp_dept[] = $temp_hosp_dept;
                }
                if ($final_hosp_dept && count($final_hosp_dept) > 0) {
                    $previous_final_hosp_dept = HospitalDepartmentUsers::where('user_id', $request->get('_id'))->get();
                    HospitalDepartmentUsers::where('user_id', $request->get('_id'))->delete();
                    HospitalDepartmentUsers::insert($final_hosp_dept);
                    Helpers::logStack(["Hospital department updated", "Event"], ['current_data' => $final_hosp_dept, 'previous_data' => $previous_final_hosp_dept]);
                }
            }

            DB::commit();
            Session::flash('success_message', "User record updated successfully.");
            return redirect()->route('admin.user.list');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            Session::flash('error_message', $e->getMessage());
            Helpers::logStack([$e->getMessage() . ' in admin user update', "Error"]);
            return redirect()->back();
        }
    }

    public function userUpdateNew(Request $request, $id)
    {
        $request->validate([
            'designation'           => 'required',
            'name'                  => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'username'              => 'required|unique:users,username,' . $id,
            'email'                 => 'required|email|unique:users,email,' . $id,
            'phone'                 => 'required',
            // 'signature_title'       => 'required',
            // 'groups'                => 'required',
            // 'department'            => 'required',
            // 'hospital_department'   => 'required',
            // 'role'                  => 'required',
            // 'identification_type'   => 'required',
            // 'identification'        => 'required',
            'expirydate'            => 'required|date_format:Y-m-d|after:today',
            'nurse'                 => 'required',
            'status'                => 'required',
            'two_fa'                => 'required',
            'profile_image'         => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=400',
            'signature_image'       => 'mimes:jpeg,jpg,png|dimensions:min_width=400,min_height=200'
        ]);
        DB::beginTransaction();
        try {
            $user = CogentUsers::where('id', $id)->first();
            if (!$user) {
                Session::flash('error_message', "User not found.");
                Helpers::logStack(['User not found in admin user update', "Error"]);
                return redirect()->route('admin.user.edit.new', $id);
            }
            // 1 : Inserting in users table
            $profile_image = '';
            $signature_image = '';
            /*profile image crop*/
            if ($request->hasFile('profile_image')) {
                if ($request->x2 == NULL) {
                    $request->x2 = 400;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 400;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('profile_image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $profile_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));
            }

            /*profile image crop*/
            /*signature image crop*/
            if ($request->hasFile('signature_image')) {
                if ($request->x2s == NULL) {
                    $request->x2s = 400;
                }
                if ($request->y2s == NULL) {
                    $request->y2s = 400;
                }

                $width = $request->ws;
                if ($width == 0) {
                    $width = 400;
                }
                $height = $request->hs;
                if ($height == 0) {
                    $height = 400;
                }
                $file = $request->file('signature_image');
                $filenamesignature = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/user/fullimage')))
                    mkdir(public_path('uploads/images/user/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filenamesignature);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filenamesignature));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filenamesignature), 70);
                $signature_image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filenamesignature)));
                @unlink(public_path('uploads/images/fullimage/' . $filenamesignature));
                @unlink(public_path('uploads/images/croppedimage/' . $filenamesignature));
            }
            /*signature image crop*/

            if ($request->designation_free != '') {
                $user_category = $request->designation_free;
            } else {
                $user_category = $request->designation;
            }
            $fullName = $request->name;
            $nameArray = explode(' ', $fullName);
            $firstName = array_shift($nameArray);
            $lastName = array_pop($nameArray);
            $middleName = implode(' ', $nameArray);
            $user_data = [
                'fldcategory' => ucfirst($user_category),
                'firstname' => $firstName,
                'middlename' => $middleName != "" ? $middleName : Null,
                'lastname' => $lastName,
                'username' => $request->username,
                'flduserid' => $request->username,
                'email' => $request->email,
                'signature_title' => $request->get('signature_title'),
                'nmc' => $request->identification_type == 'nmc' ? $request->identification : NULL,
                'nhbc' => $request->identification_type == 'nhbc' ? $request->identification : NULL,
                'nnc' => $request->identification_type == 'nnc' ? $request->identification : NULL,
                'npc' => $request->identification_type == 'npc' ? $request->identification : NULL,
                'fldfaculty' => in_array('faculty', $request->role ?? []) ? 1 : 0,
                'fldpayable' => in_array('payable', $request->role ?? []) ? 1 : 0,
                'fldreferral' => in_array('referral', $request->role ?? []) ? 1 : 0,
                'fldopconsult' => in_array('consultant', $request->role ?? []) ? 1 : 0,
                'fldipconsult' => in_array('ip_clinician', $request->role ?? []) ? 1 : 0,
                'fldsigna' => in_array('signature', $request->role ?? []) ? 1 : 0,
                'fldreport' => in_array('data_export', $request->role ?? []) ? 1 : 0,
                'fldexpirydate' => $request->expirydate,
                'fldnursing' => $request->nurse,
                'status' => $request->status,
                'two_fa' => $request->two_fa,
                'profile_image' => isset($profile_image) ? $profile_image : '',
                'signature_image' => isset($signature_image) ? $signature_image : '',
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $user->update($user_data);
            Helpers::logStack(["User updated", "Event"], ['current_data' => $user_data, 'previous_data' => $user]);

            // 2 : Updating in users details table
            $user_details_data = [
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $userdetails = UserDetail::where('user_id', $user->id)->first();
            UserDetail::where('user_id', $user->id)->update($user_details_data);
            Helpers::logStack(["User detail updated", "Event"], ['current_data' => $user_details_data, 'previous_data' => $userdetails]);
            // 3 : Users Group Table
            $groups = $request->get('groups');
            if (count($groups) > 0) {
                $final_grps = [];
                foreach ($groups as $grps) {
                    $temp['user_id'] = $user->id;
                    $temp['group_id'] = $grps;
                    $temp['created_at'] = config('constants.current_date_time');
                    $temp['updated_at'] = config('constants.current_date_time');
                    $temp['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                    $final_grps[] = $temp;
                }
                $usergroups = UserGroup::where('user_id', $user->id)->get();
                UserGroup::where('user_id', $user->id)->delete();
                if (count($final_grps) > 0) UserGroup::insert($final_grps);
                Helpers::logStack(["User group updated", "Event"], ['current_data' => $final_grps, 'previous_data' => $usergroups]);
            }

            // 4 : Users department table
            $department = $request->get('department');
            if (is_array($department)) {
                $final_dept = [];
                foreach ($department as $dept) {
                    $temp_dept['user_id'] = $user->id;
                    $temp_dept['department_id'] = $dept;
                    $final_dept[] = $temp_dept;
                }
                $userdepartments = UserDepartment::where('user_id', $user->id)->get();
                UserDepartment::where('user_id', $user->id)->delete();
                if (count($final_dept) > 0) UserDepartment::insert($final_dept);
                Helpers::logStack(["User department updated", "Event"], ['current_data' => $final_dept, 'previous_data' => $userdepartments]);
            }

            // 5 : Users hospital department users table
            $hospitalDepartment = $request->get('hospital_department');
            if (isset($hospitalDepartment) && count($hospitalDepartment) > 0) {
                $final_hosp_dept = [];
                foreach ($hospitalDepartment as $hdept) {
                    $temp_hosp_dept['hospital_department_id'] = $hdept;
                    $temp_hosp_dept['user_id'] = $user->id;
                    $final_hosp_dept[] = $temp_hosp_dept;
                }
                $hospitalsdepartments = HospitalDepartmentUsers::where('user_id', $user->id)->get();
                HospitalDepartmentUsers::where('user_id', $user->id)->delete();
                if (count($final_hosp_dept) > 0) HospitalDepartmentUsers::insert($final_hosp_dept);
                Helpers::logStack(["User hospital department updated", "Event"], ['current_data' => $final_hosp_dept, 'previous_data' => $hospitalsdepartments]);
            }

            DB::commit();
            $success_mesg = "User updated successfully." . "<br>";
            Session::flash('success_message_special', $success_mesg);
            return redirect()->route('admin.user.userview');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', $e->getMessage());
            Helpers::logStack([$e->getMessage() . ' in admin user create', "Error"]);
            return redirect()->route('admin.user.edit.new', $id);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userDestroy($id)
    {
        // if (!Permission::checkPermissionFrontendAdmin('delete-user'))
        //     return redirect()->route('access-forbidden');
        $user_details = CogentUsers::where('id', $id)->where('status', '!=', 'deleted')->first();

        if (!$user_details) {
            Helpers::logStack(["User detail not found in admin user delete", "Error"]);
            Session::flash('error_message', 'Something went wrong. User records not found.');
            return redirect()->route('admin.user.list');
        }

        // checking if this user is super admin
        $group_count = UserGroup::where('user_id', $id)->where('group_id', config('constants.role_super_admin'))->count();
        if ($group_count > 0) {
            Helpers::logStack(["User cannot delete(assign to user group) in admin user delete", "Error"]);
            Session::flash('error_message', 'Sorry ! You cannot delete this user.');
            return redirect()->route('admin.user.list');
        }

        CogentUsers::where('id', $id)->update(['status' => 'deleted', 'updated_at' => config('constants.current_date_time')]);
        Session::flash('success_message', 'User deleted successfully.');
        Helpers::logStack(["User deleted", "Event"], ['previous_data' => $user_details]);
        return redirect()->route('admin.user.list');
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function userReport($userId)
    {
        $data['user_data'] = CogentUsers::where('id', $userId)->with(['user_group', 'groups.permission'])->first();
        return $pdfString = view('adminuser::pdf.user_data', $data)/*->setPaper('a4')->stream('user_data.pdf')*/;
    }

    // User Groups

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groups()
    {
        // if (!Permission::checkPermissionFrontendAdmin('list-groups'))
        //     return redirect()->route('access-forbidden');

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Groups Management</li>';
        $data['title'] = "User Groups - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['header_nav'] = 'groups';
        $data['groups'] = Group::get();
        return view('adminuser::group', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function groupStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:group',
            'status' => 'required',
        ]);

        $group_data = [
            'name' => $request->get('name'),
            'status' => $request->get('status'),
            'created_at' => config('constants.current_date_time'),
            'updated_at' => config('constants.current_date_time'),
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        ];

        Group::create($group_data);

        Session::flash('success_message', 'Group created successfully.');
        return redirect()->route('admin.user.groups');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groupsEdit($id)
    {
        // if (!Permission::checkPermissionFrontendAdmin('update-groups'))
        //     return redirect()->route('access-forbidden');

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.groups') . '">Groups</a></li><li>Update User Groups</li>';
        $data['title'] = "User Groups Update - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['group_details'] = Group::where('id', $id)->with('group_computer_access')->first();
        $data['computer_access'] = AccessComp::where('status', 'active')->get();
        //        dd($data);
        return view('adminuser::group_edit', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function groupsUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        $group_data = [
            'name' => $request->get('name'),
            'status' => $request->get('status'),
            'updated_at' => config('constants.current_date_time')
        ];

        GroupComputerAccess::where([['group_id', $request->get('_id')]])->delete();
        if ($request->get('computer_access') && count($request->get('computer_access'))) {
            $compAccessData['group_id'] = $request->get('_id');
            foreach ($request->get('computer_access') as $access) {
                $compAccessData['computer_access_id'] = $access;
                $compAccessData['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                GroupComputerAccess::create($compAccessData);
            }
        }

        Group::where([['id', $request->get('_id')]])->update($group_data);

        Session::flash('success_message', 'Group updated successfully.');
        return redirect()->route('admin.user.groups');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function groupDestroy($id)
    {
        // if (!Permission::checkPermissionFrontendAdmin('delete-groups'))
        //     return redirect()->route('access-forbidden');

        if (in_array($id, [config('constants.role_super_admin'), config('constants.role_default_user')])) {
            Session::flash('error_message', 'Sorry ! You cannot delete this user group.');
            return redirect()->route('admin.user.groups');
        }

        $group_details = Group::where('id', $id)->first();
        if (!$group_details) {
            Session::flash('error_message', 'Something went wrong. Please try again.');
            return redirect()->route('admin.user.groups');
        }

        $group_users = $group_details->group_users ?? null;
        if ($group_users != null && $group_users->count() > 0) {
            foreach ($group_users as $gu) {

                UserGroup::where('group_id', $id)->where('user_id', $gu->user_id)->delete();

                $user_has_default_role = UserGroup::where('group_id', config('constants.role_default_user'))->where('user_id', $gu->user_id)->count();

                if ($user_has_default_role == 0) {
                    UserGroup::insert([
                        'user_id' => $gu->user_id,
                        'group_id' => config('constants.role_default_user')
                    ]);
                }
            }
        }

        Group::where('id', $id)->delete();
        PermissionGroup::where('group_id', $id)->delete();
        Session::flash('success_message', 'User Group deleted successfully.');
        return redirect()->route('admin.user.groups');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function groupPermission($id)
    {
        // if (!Permission::checkPermissionFrontendAdmin('list-groups'))
        //     return redirect()->route('access-forbidden');

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.user.groups') . '">Groups</a></li><li>Update Groups Permissions</li>';
        $data['title'] = "User Groups Permissions - " . isset(Options::get('siteconfig')['system_name']) ?? "";
        $data['group_details'] = Group::where('id', $id)->where('status', 'active')->where('id', '!=', config('constants.role_super_admin'))->first();

        if (!$data['group_details']) {
            Session::flash('error_message', 'Group does not exists / Groups Permission cannot be modified.');
            return redirect()->route('admin.user.groups');
        }

        $data['modules'] = PermissionModule::where('status', '1')->with('permission_references')->orderBy('order_by', 'asc')->get();
        $data['group_permissions'] = PermissionGroup::where('group_id', $id)->pluck('permission_reference_id')->toArray();

        return view('adminuser::group_permission', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function groupPermissionStore(Request $request)
    {
        $rules = array(
            "_group_id" => "required",
        );
        $request->validate($rules);

        PermissionGroup::where('group_id', $request->get('_group_id'))->delete();
        if ($request->has('permission_reference_id')) {

            $permission_ids = $request->get('permission_reference_id');
            if (count($permission_ids) > 0) {
                $per_records = [];
                foreach ($permission_ids as $per_id) {
                    $per_records[] = [
                        'group_id' => $request->get('_group_id'),
                        'permission_reference_id' => $per_id,
                        'created_at' => config('constants.current_date_time'),
                        'updated_at' => config('constants.current_date_time'),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }
                if (count($per_records) > 0) {
                    PermissionGroup::insert($per_records);
                }
            }
        }

        Session::flash('success_message', 'Groups Permissions updated successfully.');
        return redirect()->route('admin.user.groups.permission', [$request->get('_group_id')]);
    }

    /**
     * @param $password
     * @return string
     */
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

    public function reset2fa($id)
    {
        try {
            $user = CogentUsers::where('id', $id)->first();
            $user->update(['secret_key_2fa' => null, 'enabled_2fa' => 0]);
            return redirect()->back()->with('success_message', '2 Factor reset successful.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function permissionsetup()
    {
        $data['permissionModule'] = $this->permissionModule->get();
        $data['groups'] = $this->group->get();

        $data['sidebarmenu'] = $this->sidebarMenu->select('mainmenu')->distinct()->get();
        return view('adminuser::permission_setup', $data);
    }

    public function ajaxGetSubMenuItem(Request $request)
    {
        try {
            $data['permissionModule'] = null;
            $data['submenus']  = null;
            if ($request->has('group_id') && !is_null($request->group_id)) {
                $data['permissionModule'] = $this->group->with('permission.permissionModule')->findOrFail($request->group_id);
            }

            $htmlView = null;
            // dd($request->all());
            if (!is_null($request->all())  && collect($request->all())->isNotEmpty()) {
                $data['submenus']  = $this->sidebarMenu->select('submenu', 'id')
                    ->whereIn('mainmenu', $request->menu_names)
                    ->get();
                $htmlView = view('adminuser::ajax-views.ajax-submenu-list', $data)->render();
                $sideBarBlock = view('adminuser::ajax-views.submenulist', $data)->render();

            }

            // dd($data);

        } catch (Exception $e) {
            // dd($e->getMessage()) ;
            return response(['errors' => $e->getMessage(), 'status' => '403'], 200);
        }
        return response(['subMenuView' => $htmlView, 'submenublock' => $sideBarBlock, 'status' => 200], 200);
    }
    public function permissionSetupFilter(Request $request)
    {
        try {
            // dd($request->all());
            // $data['permisionFilter'] = $this->adminUserContract->filterPermissionModule($request);

            $data['permisionFilter'] = $this->group
                ->when(!is_null($request->module_name), function ($query) use ($request) {
                    $query->where('id',  $request->module_name);
                })
                ->when($request->has('status') && !is_null($request->status) , function ($query) use ($request) {
                    $stuatus = ($request->status == 'active')  ? 'active' : 'inactive';
                    $query->where('status', $stuatus);
                })->get();

            // dd($data);


            $view = view('adminuser::ajax-views.ajax-permission-modules-list', $data)->render();
            return response(['permisionModules' => $view, 'status' => 200], 200);
        } catch (Exception $e) {
            return response(['errors' => $e->getMessage(), 'status' => '403'], 200);
        }
    }

    /**
     * @param Response
     * @return View
     */
    // public function storePermissionSetup(PermissionSetupRequest $request)
    public function storePermissionSetup(PermissionSetupRequest $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            //    $permissionRefrence = $this->adminUserContract->storePermissionSetup($request);
            $goupModel = $this->group->create([
                'name' => $request->permission_name,
                'status' => ($request->status == 'active') ? 'active' : 'inactive',
            ]);

            foreach ($request->roles as $key => $role) {
                $permissionModule = $this->permissionModule->where('name', $key)->first();
                // dd($permissionModule);
                if(!is_null($permissionModule)){

                    foreach ($role as $permission) {
                        $moduleRefrenceData = [
                            'code' => Str::slug($key, '-') . '-' . $permission,
                            'short_desc' =>  $key,
                            'description' => $request->flddescription,
                            'permission_modules_id' => $permissionModule->id
                        ];
                        $permissionRefrence = $permissionModule->permission_references()->create($moduleRefrenceData);
                        // dump($permissionRefrence);
                        $permissionRefrenceIds[] = $permissionRefrence->id;
                    }
                }
                // dd($permissionRefrenceIds);
                // $permissionRefrence->permission_groups()->create( $permissionRefrence->id,[
                //     'group_id' => $goupModel->id,
                // ]);
                // dd($permissionRefrenceIds);
                $goupModel->permission()->sync($permissionRefrenceIds);
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->withErrors('errors', $e->getMessage());
        }
        DB::commit();
        // Session::flash('success_message', 'User Group deleted successfully.');
        return redirect()->route('admin.user.groups.permissionsetup')->with('success_message', 'successfully added a permission');
    }

    public function permissionadd()
    {
        return view('adminuser::permission_add');
    }

    /**
     * edit permmision setup view
     */
    public function editPermissionSetup($id)
    {
        try {
            // $permissionModule = $this->adminUserContract->modelData($id,['permission_references.permissionRefrenceSideBarMenu',]);
            $permissionModule = $this->group->with('permission.permissionModule')->findOrFail($id);
            $data['sidebarmenu'] = $this->sidebarMenu
                ->select('mainmenu')
                ->distinct()
                // ->take(10)
                ->get();
            // $mainsideBar = $permissionModule->permission_references->map(function($item){
            //     return $item->permissionRefrenceSideBarMenu->mainmenu ;
            // })->unique();
            $mainsideBar = $permissionModule->permission->map(function ($item) {
                // dd($item->permissionRefrenceSideBarMenu);
                return (!is_null($item->permissionRefrenceSideBarMenu)) ? $item->permissionRefrenceSideBarMenu->mainmenu : false;
            })->unique();
            $data['subsidebarmenu'] = $this->sidebarMenu
                ->whereIn('mainmenu', $mainsideBar)
                ->get();
            $data['permissionModule'] = $permissionModule;

            // $data['permissionModuleView'] = $this->permissionModule->get();
            $data['permissionModuleView'] = $this->group->get();
            // dd($data);

            // $data['sidebarmenu'] = $this->sidebarMenu->select('mainmenu')->distinct()->get();
            return view('adminuser::permission_edit', $data);
        } catch (Exception $e) {
            dd($e);
            return back()->with(['errors' => $e->getMessage(), 'status' => '403'], 200);
        }
    }
    /**
     * update permission setup
     * @param Request
     * @return Boolean true|false
     */
    public function updatePermissionSetup(PermissionSetupRequest $request)
    {
        try {
            // dd($request->all());

            DB::beginTransaction();
            $groupModel = $this->group->findOrFail($request->permission_module_id);
            $groupModel->update([
                'name' => $request->permission_name,
                'status' => ($request->status == 'active') ? 'active' : 'inactive',
            ]);

            //updating
            $moduleRefrenceData = null;
            foreach ($request->roles as $key => $role) {
                $permissionModule = $this->permissionModule->where('name', $key)->first();
                // dd($permissionModule, $key, $role);
                if(!is_null($permissionModule)){
                    $permissionModule->permission_references()->delete();
                    foreach ($role as $permission) {
                        $moduleRefrenceData = [
                            'code' => Str::slug($key, '-') . '-' . $permission,
                            'short_desc' =>  $key,
                            'description' => $request->flddescription,
                            'permission_modules_id' => $permissionModule->id
                        ];
                        $permissionRefrence = $permissionModule->permission_references()->create($moduleRefrenceData);
                        $permissionRefrenceIds[] = $permissionRefrence->id;
                    }
                }

            }
            $groupModel->permission()->sync($permissionRefrenceIds);

            // $updateStatus = $permissionModule->permission_references()
            //                 ->insert($moduleRefrenceData);
            // $updateAction = $this->adminUserContract->updatePermissionSetup($request,['permission_references', ]);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(['errors' => $e->getMessage(), 'status' => '403'], 200);
        }
        DB::commit();
        return redirect()->route('admin.user.groups.permissionsetup.submenus')->with('success_message', 'successfully update a permission');
    }

    public function updateGroupActiveStatus(Request $request)
    {

        try{
            $group = $this->group->findOrFail($request->group_id);
            $stuatus = ($group->status == 'active' ) ? 'inactive' : 'active';
            $group->update(['status' => $stuatus,]);

        }catch(Exception $e){
            // dd($e);
        }
        return response(['status' => ucfirst($stuatus), 'message' => 'Successfully update a group status'],200);
    }

    public function groupPermissionList(Request $request)
    {

          // $permissionModule = $this->adminUserContract->modelData($id,['permission_references.permissionRefrenceSideBarMenu',]);
          $permissionModule = $this->group->with('permission.permissionModule')->findOrFail($request->group_id);
          $data['sidebarmenu'] = $this->sidebarMenu
              ->select('mainmenu')
              ->distinct()
              // ->take(10)
              ->get();
          $mainsideBar = $permissionModule->permission->map(function ($item) {

              return (!is_null($item->permissionRefrenceSideBarMenu)) ? $item->permissionRefrenceSideBarMenu->mainmenu : false;
          })->unique();
          $data['subsidebarmenu'] = $this->sidebarMenu
              ->whereIn('mainmenu', $mainsideBar)
              ->get();
          $data['permissionModule'] = $permissionModule;
          $html = view('adminuser::ajax-views.permissionpreview',$data)->render();
          return response(['htmlview' => $html], 200);
    }

    protected function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
