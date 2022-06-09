<?php

namespace App\Http\Controllers;

use App\CogentUsers;
use App\User;
use Illuminate\Http\Request;

class UserDataTransferController extends Controller
{
    public function copyUsersData()
    {
//        CogentUsers::query()->truncate();
        $usersData = User::all();
        foreach ($usersData as $userDaton) {
            $userCreateData = [];
            $usernameremove = '';
            $nameWithoutDr = $userDaton->fldusername;
            if (strpos($userDaton->fldusername, 'Dr. ') !== false) {
                $usernameremove = 'Dr. ';
                $nameWithoutDr = str_replace('Dr. ', '', $userDaton->fldusername);
            }
            $name = explode(' ', $nameWithoutDr);
            if (count($name) == 3) {
                $userCreateData['firstname'] = $usernameremove . $name[0];
                $userCreateData['middlename'] = $name[1];
                $userCreateData['lastname'] = $name[2];
            } elseif (count($name) == 2) {
                $userCreateData['firstname'] = $usernameremove . $name[0];
                $userCreateData['lastname'] = $name[1];
            } else {
                $userCreateData['firstname'] = $usernameremove . $userDaton->fldusername;
            }

            $userCreateData['username'] = $userDaton->flduserid;
            $userCreateData['password'] = $userDaton->fldpass;
            $userCreateData['status'] = strtolower($userDaton->fldstatus);
            $userCreateData['fldroot'] = $userDaton->fldroot;
            $userCreateData['fldcategory'] = $userDaton->fldcategory;
            $userCreateData['fldcode'] = $userDaton->fldcode;
            $userCreateData['fldfromdate'] = $userDaton->fldfromdate;
            $userCreateData['fldtodate'] = $userDaton->fldtodate;
            $userCreateData['fldusercode'] = $userDaton->fldusercode;
            $userCreateData['fldfaculty'] = $userDaton->fldfaculty;
            $userCreateData['fldpayable'] = $userDaton->fldpayable;
            $userCreateData['fldreferral'] = $userDaton->fldreferral;
            $userCreateData['fldopconsult'] = $userDaton->fldopconsult;
            $userCreateData['fldipconsult'] = $userDaton->fldipconsult;
            $userCreateData['fldsigna'] = $userDaton->fldsigna;
            $userCreateData['fldreport'] = $userDaton->fldreport;
            $userCreateData['xyz'] = $userDaton->xyz;

            CogentUsers::insert($userCreateData);
        }
        dd("done migration");
    }
}
