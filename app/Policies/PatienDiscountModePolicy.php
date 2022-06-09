<?php

namespace App\Policies;

use App\CogentUsers;
use App\User;
use App\Utils\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatienDiscountModePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function before($user, $ability)
    {

        if ( Auth::guard('admin_frontend')->user()->user_is_superadmin->count() > 0 ) {
            return true;
        }
    }
    public function viewAny(User $user)
    {
        return Permission::checkPermission($user->id, 'patient-discount-category') ;

    }
    public function view()
    {

        return Permission::checkPermission($user->id, 'patient-discount-category') ;

    }
    Public function create(User $user)
    {
        return Permission::checkPermission($user->id, 'patient-discount-category') ;
    }

    Public function update(User $user)
    {
    }
    Public function delete(User $user)
    {
    }

}
