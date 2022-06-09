<?php

namespace Modules\AdminUser\Services\Repositories ;

use App\PermissionModule;
use App\PermissionReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Modules\AdminUser\Services\Contracts\AdminUserContract;
use Illuminate\Support\Str;
use Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters\Eagerload;
use Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters\FindFilter;
use Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters\FindOrFailPipe;
use Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters\NameFilter;
use Modules\AdminUser\Services\Repositories\Pipelines\QueryFilters\StatusFilter;

class AdminUserService implements AdminUserContract
{
    public function __construct(PermissionModule $permissionModule, PermissionReference $permissionReference)
    {
        $this->permissionModule = $permissionModule ;
        $this->permissionReference = $permissionReference ;
    }

    /**
     * @param $id
     * @return Model
     * @throw ModelNotFound Exception if not found
     */
    public function findById($modal, $id) : Model
    {
        return $modal->findOrFail($id);
    }

    /**
     * @param $id and $with - dynamically eager load
     * @return Modal->with('relationship')
     * working permission modules and eagerload this permissions
     * @throw ModelNotFound Excption and Relation not exist Exception
     */
    public function modelData($id,  $with = null)
    {
        request()->merge([
            'id' => $id,
            'with' => $with,
        ]);
        $permissionModulePipeline = app(Pipeline::class)
            ->send($this->permissionModule->query())
            ->through([
                FindFilter::class ,
                Eagerload::class
            ])
            ->thenReturn() ;
        return $permissionModulePipeline->first() ;
    }
    /**
     * @param $with
     * @return eagerload builder
     */
    public function with( $model, $with)
    {
        return $model->with($with);
    }
    public function storePermissionSetup($request)
    {
        $permissionModule = $this->permissionModule->create([
            'name' => $request->permission_name,
            'status' => ($request->status == 'active') ? true : false ,
            'order_by' => 1
        ]);
        $moduleRefrenceData = null;
        foreach($request->roles as $key => $role)
        {
            foreach($role as $permission)
            {
                $moduleRefrenceData[] = [
                    'code' => Str::slug($key, '-').'-'.$permission,
                    'short_desc' =>  $key,
                    'description' => $request->flddescription,
                    'permission_modules_id' => $permissionModule->id
                ];
            }

        }
        $permissionRefrence = $permissionModule->permission_references()->insert($moduleRefrenceData);
        return $permissionRefrence ;
    }

    public function updatePermissionSetup($request, $with = null)
    {
        request()->merge([
            'id' => $request->permission_module_id,
            'with' => $with,
        ]);
        $permissionModule = app(Pipeline::class)
            ->send($this->permissionModule->query())
            ->through([
                FindOrFailPipe::class ,
                Eagerload::class,

            ])
            ->thenReturn() ;

        $permissionModule->update([
            'name' => $request->permission_name,
            'status' => ($request->status == 'active') ? true : false ,
        ]);

        //updating
        $moduleRefrenceData = null;
        foreach($request->roles as $key => $role)
        {
            foreach($role as $permission)
            {
                $moduleRefrenceData[] = [
                    'code' => Str::slug($key, '-').'-'.$permission,
                    'short_desc' =>  $key,
                    'description' => $request->flddescription,
                    'permission_modules_id' => $permissionModule->id
                ];
            }
        }
        $permissionModule->permission_references()->delete();
        $updateStatus = $permissionModule->permission_references()
                        ->insert($moduleRefrenceData);
        return $updateStatus;

        // $modalCollection = $permissionModule->permission_references->map(function($permissionREfrence){
        //     return $permissionREfrence->only(['code', 'description', 'short_desc', 'permission_modules_id']);
        // }) ;
        // $rolesCollection = collect($moduleRefrenceData);

        // $newFilterCollection = $rolesCollection->map(function($rolesForUpadate) use ($modalCollection) {

        //          $modalCollection->each(function($modalData) use ($rolesForUpadate){
        //             if($modalData->diff($rolesForUpadate)){
        //
        //                 return true ;
        //             }else{
        //                 return false ;
        //             }
        //         });
        // });

    }

    /**
     * filter a permission_modules model base on different filter parameter
     * @param Request
     * @method - introduct pipeline pattern for exapandable purpose which may change in future
     * @uses Pipeline::class, NameFilter
     * @return Collection
     */
    public function filterPermissionModule($request)
    {
        $permissionModulePipeline = app(Pipeline::class)
            ->send($this->permissionModule->query())
            ->through([
                NameFilter::class ,
                // StatusFilter::class
            ])
            ->thenReturn() ;
        // $request->has('limit') ? $permissionModulePipeline->paginate($request->limit)  :
        return $permissionModulePipeline->get() ;
    }
}
