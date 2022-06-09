<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'admin/user'], function () {

    // User Groups
    Route::get('groups', array(
        'as' => 'admin.user.groups',
        'uses' => 'AdminUserController@groups'
    ));

    Route::get('userview', array(
        'as' => 'admin.user.userview',
        'uses' => 'AdminUserController@userview'
    ));

    Route::post('groups/store', array(
        'as' => 'admin.user.groups.store',
        'uses' => 'AdminUserController@groupStore'
    ));

    Route::get('groups/edit/{id}', array(
        'as' => 'admin.user.groups.edit',
        'uses' => 'AdminUserController@groupsEdit'
    ));

    Route::post('groups/update', array(
        'as' => 'admin.user.groups.update',
        'uses' => 'AdminUserController@groupsUpdate'
    ));

    Route::get('groups/destroy/{id}', array(
        'as' => 'admin.user.groups.destroy',
        'uses' => 'AdminUserController@groupDestroy'
    ));

    Route::get('groups/permission/{id}', array(
        'as' => 'admin.user.groups.permission',
        'uses' => 'AdminUserController@groupPermission'
    ));

    Route::post('groups/permission-store', array(
        'as' => 'admin.user.groups.permission-store',
        'uses' => 'AdminUserController@groupPermissionStore'
    ));

    //new permission setup
    Route::get('groups/permissionsetup', array(
        'as' => 'admin.user.groups.permissionsetup',
        'uses' => 'AdminUserController@permissionsetup'
    ));

    //new permission setup
    Route::post('groups/permissionsetup/filter', array(
        'as' => 'admin.user.groups.permissionsetup.filter',
        'uses' => 'AdminUserController@permissionSetupFilter'
    ));

    Route::post('groups/permissionsetup', [
        'as' => 'admin.user.groups.permissionsetup.submenus',
        'uses' => 'AdminUserController@ajaxGetSubMenuItem'
    ]);

    //store a permission for submenus
    Route::post('groups/permissionsetup/store', [
        'as' => 'admin.user.groups.permissionsetup.submenus.store',
        'uses' => 'AdminUserController@storePermissionSetup'
    ]);

    Route::get('groups/permissionadd', array(
        'as' => 'admin.user.groups.permissionadd',
        'uses' => 'AdminUserController@permissionadd'
    ));

    //edit view a permission for submenus
    Route::get('groups/permissionsetup/edit/{id}', [
        'as' => 'admin.user.groups.permissionsetup.submenus.edit',
        'uses' => 'AdminUserController@editPermissionSetup'
    ]);

    //update permission setup for submenus
    Route::patch('groups/permissionsetup/update', [
        'as' => 'admin.user.groups.permissionsetup.submenus.update',
        'uses' => 'AdminUserController@updatePermissionSetup'
    ]);

    //view permission list of specific modal
    Route::post('groups/permissionsetup/permission/list', [
        'as' => 'admin.user.group.permission.listview',
        'uses' => 'AdminUserController@groupPermissionList'
    ]);

    Route::post('groups/permissionsetup/permission/updatestatus', [
        'as' => 'admin.user.group.permission.updatestatus',
        'uses' => 'AdminUserController@updateGroupActiveStatus'
    ]);

  

    // Users
    Route::get('list', array(
        'as' => 'admin.user.list',
        'uses' => 'AdminUserController@userLists'
    ));

    Route::get('add', array(
        'as' => 'admin.user.add',
        'uses' => 'AdminUserController@userAdd'
    ));

    Route::get('add-new', array(
        'as' => 'admin.user.add.new',
        'uses' => 'AdminUserController@userAddNew'
    ));

    Route::post('store', array(
        'as' => 'admin.user.store',
        'uses' => 'AdminUserController@userStore'
    ));

    Route::post('store-new', array(
        'as' => 'admin.user.store.new',
        'uses' => 'AdminUserController@userStoreNew'
    ));

    Route::get('edit/{id}', array(
        'as' => 'admin.user.edit',
        'uses' => 'AdminUserController@userEdit'
    ));

    Route::get('edit-new/{id}', array(
        'as' => 'admin.user.edit.new',
        'uses' => 'AdminUserController@userEditNew'
    ));

    Route::get('report/{id}', array(
        'as' => 'admin.user.report',
        'uses' => 'AdminUserController@userReport'
    ));

    Route::post('update', array(
        'as' => 'admin.user.update',
        'uses' => 'AdminUserController@userUpdate'
    ));

    Route::post('update-new/{id}', array(
        'as' => 'admin.user.update.new',
        'uses' => 'AdminUserController@userUpdateNew'
    ));

    Route::get('destroy/{id}', array(
        'as' => 'admin.user.destroy',
        'uses' => 'AdminUserController@userDestroy'
    ));

    Route::post('password-reset-user', array(
        'as' => 'admin.user.password-reset-user',
        'uses' => 'AdminUserController@passwordResetUser'
    ));

    /*
     * comp/mac access
     */
    Route::group(['prefix' => 'comp-access'], function () {
        Route::get('/', array(
            'as' => 'admin.user.comp.access',
            'uses' => 'AccessComputerController@compAccess'
        ));

        Route::get('/add', array(
            'as' => 'admin.user.comp.access.add',
            'uses' => 'AccessComputerController@compAccessAdd'
        ));

        Route::post('/store', array(
            'as' => 'admin.user.comp.access.store',
            'uses' => 'AccessComputerController@compAccessStore'
        ));

        Route::get('/edit/{id}', array(
            'as' => 'admin.user.comp.access.edit',
            'uses' => 'AccessComputerController@compAccessEdit'
        ));

        Route::post('/update', array(
            'as' => 'admin.user.comp.access.update',
            'uses' => 'AccessComputerController@compAccessUpdate'
        ));

        /*
         * ADD MAC ADDRESS
         */
        Route::get('/list-mac/{comp}', array(
            'as' => 'admin.user.comp.access.list.mac',
            'uses' => 'MacAccessController@compAccessListMac'
        ));

        Route::get('/mac/add/{comp}', array(
            'as' => 'admin.user.mac.access.add',
            'uses' => 'MacAccessController@macAccessAdd'
        ));

        Route::post('/mac/store', array(
            'as' => 'admin.user.mac.access.store',
            'uses' => 'MacAccessController@macAccessStore'
        ));

        Route::get('/mac/edit/{id}', array(
            'as' => 'admin.user.mac.access.edit',
            'uses' => 'MacAccessController@macAccessEdit'
        ));

        Route::post('/mac/update', array(
            'as' => 'admin.user.mac.access.update',
            'uses' => 'MacAccessController@macAccessUpdate'
        ));


        Route::get('/mac-lists', array(
            'as' => 'admin.user.mac.inactive.list',
            'uses' => 'MacAccessController@inactiveMacAddress'
        ));


        Route::post('/approve-mac-request', array(
            'as' => 'approve.mac.access',
            'uses' => 'MacAccessController@approveMacAddress'
        ));


        Route::post('/change-mac-request', array(
            'as' => 'change.mac.access',
            'uses' => 'MacAccessController@removeMacAddress'
        ));

        Route::post('/add-mac-request-group', array(
            'as' => 'add.mac.to.group.access',
            'uses' => 'MacAccessController@addInGroup'
        ));

    });

});

Route::group(['middleware' => ['web', 'auth-checker'], 'prefix' => 'admin/user'], function () {
    // Route : Profile Update
    Route::get('profile', array(
        'as' => 'admin.user.profile',
        'uses' => 'AdminUserController@profile'
    ));

    Route::post('profile/store', array(
        'as' => 'admin.user.profile.store',
        'uses' => 'AdminUserController@profileStore'
    ));

    // Route : Change Password
    Route::get('password-reset', array(
        'as' => 'admin.user.password-reset',
        'uses' => 'AdminUserController@passwordReset'
    ));

    Route::post('password-reset/store', array(
        'as' => 'admin.user.password-reset.store',
        'uses' => 'AdminUserController@passwordResetStore'
    ));

    Route::get('reset-2fa/{id}', array(
        'as' => 'admin.user.reset.2fa',
        'uses' => 'AdminUserController@reset2fa'
    ));

});
