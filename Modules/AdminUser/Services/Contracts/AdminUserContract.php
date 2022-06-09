<?php

namespace Modules\AdminUser\Services\Contracts ;

use Illuminate\Database\Eloquent\Model;

interface AdminUserContract
{
    public function storePermissionSetup($request) ;
    public function filterPermissionModule($request);
    public function modelData($id, $with ) ;
    public function updatePermissionSetup($request) ;
}
