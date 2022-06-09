@extends('frontend.layouts.master')
@section('content')
<style>
    .list {
        height: 400px;
        border: 1px solid #e3e3e3;
        padding 5px;
        border-radius: 5px;
        overflow: auto;
    }

    .list ul {
        list-style: none;
    }

    .list ul li {
        padding: 5px 10px;
    }

    .list ul li:hover {
        background: #144069;
        color: white;
    }

    .list ul li.active {
        background: #144069;
        color: white;
    }

    .check-box {
        border-bottom: 1px solid #e3e3e3;
    }

    .child-box {
        display: flex;
        flex-wrap: wrap;
        margin-left: 50px;
    }

    .inputs {
        display: flex;
        flex-direction: row;
        padding: 2px;
        align-items: center;
    }

    .roles-header {
        padding: 3px 20px;;

    }
</style>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 59px;
  height: 26px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 17px;
  width: 20px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #00bd8d;
}

input:focus + .slider {
  box-shadow: 0 0 1px #00bd8d;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

    <div class="container-fluid">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link " id="home-tab" style="background-color: unset;" data-toggle="tab" href="#permissionview" role="tab" aria-controls="permissionview" aria-selected="true">Permission View</a>
                </li>
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab" style="background-color: unset;" data-toggle="tab" href="#editpermission" role="tab" aria-controls="editpermission" aria-selected="false">Edit Permission</a>
            </li>
        </ul>

        <div class="tab-content" id="nav-tabContent">
            {{-- View tab  --}}
            <div class="tab-pane fade  " id="permissionview" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">
                                        Profile Management
                                    </h4>
                                </div>
                                <button onclick="myFunction()" class="btn btn-primary"><i class="fa fa-bars"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" id="myDIV">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Permission Management</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <form id="filterFrom" >
                                    @csrf
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 style="margin: 5px 0 5px 0;">Filter Group Profile</h5>
                                        <button type="button" class="btn btn-primary" id="redetFilter">
                                        <i class="fa fa-sync"></i> Reset
                                        </button>
                                    </div>
                                    <div class="d-flex flex-row">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-sm-12">Group Permissions</label>
                                                    <div class="col-lg-12 col-sm-12">
                                                        <select class="form-control select2" name="module_name" data-variable="">
                                                        <option value="">-- Select Group --</option>
                                                        @forelse ($permissionModuleView as $key => $permissiomview )

                                                            <option value="{{ $permissiomview->id }}">{{ $permissiomview->name }}</option>
                                                        @empty

                                                        @endforelse

                                                        </select>
                                                    </div>

                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-sm-12">Status</label>
                                                    <div class="col-lg-12 col-sm-12">
                                                        <select class="form-control" name="status" data-variable="">
                                                        <option value="">-- Select --</option>
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                        </select>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="d-flex flex-row justify-content-end align-items-center col-md-12 col-lg-12">
                                        <!-- <button type="button" class="btn btn-secondary btn-action mr-2">Close</button> -->
                                        <button type="submit" id="filterbtn" class="btn btn-primary btn-action"><i class="fa fa-filter"></i>
                                                &nbsp;Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-body">

                                <div class="table-sticky-th" style="width: 100%;">
                                    <table
                                    class="table expandable-table  custom-table table-bordered table-striped  mt-c-15 table-sticky-th"
                                    id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                    data-pagination="false"
                                    data-resizable="true">
                                        <thead class="thead-light">
                                            <tr>
                                                    <th style="width: 3%">S.N.</th>
                                                    <th>Permission Name</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="permisionModules">
                                            @forelse ($permissionModuleView as $permission )
                                                @if($permission->id !==1 )
                                                @if($permission->id !==2)
                                                <tr>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td class="groupStatusTd">{{ ($permission->status == 'active')? 'Active' : 'Inactive'}}</td>
                                                    <td>
                                                        <div class="d-flex flex-row">
                                                            <a href="{{ route('admin.user.groups.permissionsetup.submenus.edit', $permission->id) }}" class="btn btn-sm btn-primary adminMgmtTableBtn" title="Edit permission"><i class="fa fa-edit"></i></a>
                                                            <a href="#" class="btn btn-sm btn-primary ml-1" data-toggle="modal" data-target="#permission-detail-modal" data-groupid="{{ $permission->id }}" ><i class="fa fa-eye"> </i> </a>
                                                            <label class="switch ml-1">
                                                                <input type="checkbox" class="statusSwitch" value="{{ $permission->id}}" @if($permission->status == 'active')checked @endif>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                                @endif
                                            @empty
                                                <tr> no result found </tr>
                                            @endforelse


                                        </tbody>
                                        </table>
                                        <div id="bottom_anchor"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            {{-- edit tab --}}
            <div class="tab-pane fade show active " id="editpermission" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="row">
                    <div class="col-sm-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Edit Group and Permission</h4>
                            </div>
                            <div class="d-flex flex-row">
                            <button type="button" class="btn btn-outline-primary" id="resetInput"><i class="fa fa-sync"></i>&nbsp;Reset</button>
                            <button type="button" class="btn btn-primary ml-1" data-toggle="modal" data-target="#permission-preview-modal"><i class="fa fa-eye"></i>&nbsp;Preview</button>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <form  method="POST" id="permissionEditForm" action="{{ route('admin.user.groups.permissionsetup.submenus.update') }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" id="permission_module_id" name="permission_module_id" value="{{ $permissionModule->id }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-sm-12">Name</label>
                                                    <div class="col-lg-12 col-sm-12">
                                                        <input type="text" id="permission_name" name="permission_name" value="{{$permissionModule->name}}"  class="form-control" id="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-sm-12">Description</label>
                                                    <div class="col-lg-12 col-sm-12">
                                                        <textarea  id="flddescription"name="flddescription" value="{{ $permissionModule->permission->isNotEmpty() ?$permissionModule->permission->first()->description : null }}" style="width: 100%" rows="2">{{ $permissionModule->permission->isNotEmpty() ? $permissionModule->permission->first()->description : null }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-md-12">Status</label>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="d-flex flex-row">
                                                            <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                                <input type="radio" name="status" value="active" class=" custom-control-input" id="" {{ ($permissionModule->status == 'active') ? 'checked' : true }}>
                                                                <label for="" class="custom-control-label activeStatus">Active</label>
                                                            </div>
                                                            <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                                <input type="radio" name="status" value="inactive" class=" custom-control-input" id="" {{ ($permissionModule->status == 'inactive') ? 'checked' : true }}>
                                                                <label for="" class="custom-control-label activeStatus">Inactive</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column ">
                                            <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                                <h6>Menu</h6>
                                                <div class="inputs">
                                                    {{-- <input type="checkbox" id="checkAllMenu" value="">
                                                    <label class="ml-1" for="">All</label> --}}
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-0 mb-1">
                                                <input type="text" class="form-control" id="menuSearch"  placeholder="Search">
                                            </div>
                                            <div class="list">
                                                <ul class="main_menu">
                                                    @forelse ($sidebarmenu as $mainmenu )
                                                            <li  class="main_menu_item
                                                                    @forelse ($permissionModule->permission as $permission_references )
                                                                    @if(!is_null($permission_references->permissionRefrenceSideBarMenu))
                                                                        @if( $permission_references->permissionRefrenceSideBarMenu->mainmenu == $mainmenu->mainmenu && $loop->first)
                                                                            active
                                                                        @endif
                                                                    @endif
                                                                @empty
                                                                @endforelse

                                                            " id="{{str_replace(' ', '', $mainmenu->mainmenu)}}" data-menu="{{$mainmenu->id}}">
                                                                {{ $mainmenu->mainmenu }}
                                                            </li>
                                                    @empty

                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column roleCompart">
                                            <div class="d-flex flex-row justify-content-between align-items-end mb-1">
                                                <h6>Roles</h6>
                                                <div class="inputs">
                                                    <input type="checkbox" id="allRoles" value="">
                                                    <label class="ml-1" for="">All</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-0 mb-1">
                                                <input type="text" class="form-control" id="submenusearch" placeholder="Search">
                                            </div>
                                            <div class="list">
                                                <div id="submenuitems" class="searchPanel">
                                                    @php
                                                        $group_permission_references = $permissionModule->permission->groupBy(function($item){
                                                            return trim(ucfirst($item->short_desc));
                                                        });
                                                    @endphp

                                                    @forelse ($subsidebarmenu   as $key =>  $submenuList )

                                                            <div class="check-box">
                                                                <div class="virtualDivForModel">
                                                                    <div class="inputs roles-header">
                                                                        <input type="checkbox" class="sidebaritem" id="{{ str_replace(' ', '',$submenuList->submenu ) }}" name="submenus[]" value="{{ $submenuList->submenu }}" {{$group_permission_references->has(ucfirst($submenuList->submenu)) ? 'checked' : null}} >
                                                                        <label class="ml-1 checkboxlabelHeader" for="">{{ ucfirst($submenuList->submenu) }}</label>
                                                                    </div>
                                                                    @php
                                                                        $roles = [];
                                                                        if($group_permission_references->has(ucfirst($submenuList->submenu)))
                                                                        {
                                                                            foreach($group_permission_references[ucfirst($submenuList->submenu)] as $role)
                                                                            {
                                                                                $arrayOfString = explode('-', $role->code);

                                                                                $roles[] = end($arrayOfString);

                                                                            }
                                                                        }

                                                                        $roleCollection = collect($roles);


                                                                    @endphp
                                                                    <div class="child-box">
                                                                        <div class="inputs col-md-6">
                                                                            <input type="checkbox" class="permissions" id="add{{ str_replace(' ', '',$submenuList->submenu) }}" name="roles[{{$submenuList->submenu}}][]" value="add"
                                                                                {{ $roleCollection->contains('add') ? 'checked' : null }}
                                                                            >
                                                                            <label class="ml-1 rolesItem" for="">Add </label>
                                                                        </div>
                                                                        <div class="inputs col-md-6">
                                                                            <input type="checkbox" class="permissions" id="update{{ str_replace(' ', '',$submenuList->submenu) }}" name="roles[{{$submenuList->submenu}}][]" value="update"
                                                                            {{ $roleCollection->contains('update') ? 'checked' : null }}
                                                                            >
                                                                            <label class="ml-1 rolesItem" for="">Update</label>
                                                                        </div>
                                                                        <div class="inputs col-md-6">
                                                                            <input type="checkbox" class="permissions" id="view{{ str_replace(' ', '',$submenuList->submenu) }}" name="roles[{{$submenuList->submenu}}][]" value="view"
                                                                                {{ $roleCollection->contains('view') ? 'checked' : null }}
                                                                            >
                                                                            <label class="ml-1 rolesItem" for="">View</label>
                                                                        </div>
                                                                        <div class="inputs col-md-6">
                                                                            <input type="checkbox" class="permissions" id="delete{{ str_replace(' ', '',$submenuList->submenu) }}" name="roles[{{$submenuList->submenu}}][]" value="delete"
                                                                            {{ $roleCollection->contains('delete') ? 'checked' : null }}
                                                                            >
                                                                            <label class="ml-1 rolesItem" for="">Delete</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    @empty

                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-right mt-1">
                                    <button type="submit" class="btn btn-primary btn-action">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
   </div>

   {{-- Modal  --}}
    <div class="modal fade bd-example-modal-lg" id="permission-preview-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('admin.user.groups.permissionsetup.submenus.update') }}" method="post" id="permissionAddFormModel">
                @csrf
                @method('PATCH')
                <input type="hidden" id="permission_module_id" name="permission_module_id" value="{{ $permissionModule->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Group Permission Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="iq-card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row flex-row">
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-sm-12">Name</label>
                                                    <div class="col-lg-12 col-sm-12">
                                                        <input type="text" id="group" value="{{$permissionModule->name}}" name="permission_name" class="form-control" id="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-sm-12">Description</label>
                                                    <div class="col-lg-12 col-sm-12">
                                                        <textarea name="flddescription" id="description" value="{{ $permissionModule->permission->isNotEmpty() ? $permissionModule->permission->first()->description : null }}"  style="width: 100%" rows="2" readonly>{{ $permissionModule->permission->isNotEmpty() ? $permissionModule->permission->first()->description : null }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-md-12">Status</label>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="row flex-row">
                                                            <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                                <input type="radio" name="status" value="active" class=" custom-control-input" id="active" {{ ($permissionModule->status == 'active') ? 'checked' : true }} readonly>
                                                                <label for="" class="custom-control-label ">Active</label>
                                                            </div>
                                                            <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                                <input type="radio" name="status" value="inactive" class=" custom-control-input" id="inactive" {{ ($permissionModule->status == 'inactive') ? 'checked' : true }} readonly>
                                                                <label for="" class="custom-control-label ">Inactive</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column ">
                                            <h6 class="mb-1">Menu</h6>
                                            <div class="list">
                                                <ul class="main_menu" id="main_menu_model">
                                                    @forelse ($sidebarmenu as $mainmenu )
                                                        <li  class="
                                                        @forelse ($permissionModule->permission as $permission_references )
                                                            @if(!is_null($permission_references->permissionRefrenceSideBarMenu))
                                                                @if( $permission_references->permissionRefrenceSideBarMenu->mainmenu == $mainmenu->mainmenu )
                                                                active
                                                                @endif
                                                            @endif
                                                        @empty
                                                        @endforelse
                                                        " id="{{str_replace(' ', '',$mainmenu->mainmenu)}}" data-menu="{{$mainmenu->id}}">{{ $mainmenu->mainmenu }}</li>
                                                    @empty

                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column ">
                                            <h6 class="mb-1">Sub Menu</h6>
                                            <div class="list">
                                                <ul class="main_menu" id="subMenuModal">
                                                    @forelse ($subsidebarmenu  as $menu )
                                                        <li class="
                                                            @forelse ($permissionModule->permission as $permission_references )
                                                                @if(!is_null($permission_references->permissionRefrenceSideBarMenu))
                                                                    @if( $permission_references->permissionRefrenceSideBarMenu->submenu == $menu->submenu)
                                                                        active
                                                                    @endif
                                                                @endif
                                                            @empty
                                                            @endforelse
                                                        " id="{{ str_replace(' ','',$menu->submenu ) }}"
                                                            
                                                        > {{ $menu->submenu }}</li>
                                                    @empty

                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-4">
                                            <div class="d-flex flex-column roleCompart">
                                                <h6 class="mb-1">Roles</h6>
                                                <div class="list">
                                                    <div id="rolesPermission">

                                                        @php
                                                            $group_permission_references = $permissionModule->permission->groupBy(function($item){
                                                                return trim(ucfirst($item->short_desc));
                                                            });
                                                        @endphp

                                                        @forelse ($subsidebarmenu   as $key =>  $submenuList )
                                                            @if($group_permission_references->has(ucfirst($submenuList->submenu)))
                                                                <div class="check-box">
                                                                    <div class="virtualDivForModel">
                                                                        <div class="inputs roles-header">
                                                                            <input type="checkbox" class="sidebaritem" id="{{ str_replace(' ','', $submenuList->submenu ) }}" name="submenus[]" value="{{ $submenuList->submenu }}" {{$group_permission_references->has(ucfirst($submenuList->submenu)) ? 'checked' : null}} >
                                                                            <label class="ml-1" for="">{{ ucfirst($submenuList->submenu) }}</label>
                                                                        </div>
                                                                        @php
                                                                            $roles = [];
                                                                            if($group_permission_references->has(ucfirst($submenuList->submenu)))
                                                                            {
                                                                                foreach($group_permission_references[ucfirst($submenuList->submenu)] as $role)
                                                                                {
                                                                                    $arrayOfString = explode('-', $role->code);

                                                                                    $roles[] = end($arrayOfString);

                                                                                }
                                                                            }
                                                                            $roleCollection = collect($roles);
                                                                        @endphp
                                                                        <div class="child-box">
                                                                            <div class="inputs col-md-6">
                                                                                <input type="checkbox" class="permissions" name="roles[{{$submenuList->submenu}}][]" id="add{{ str_replace(' ','', $submenuList->submenu ) }}" value="add"
                                                                                    {{ $roleCollection->contains('add') ? 'checked' : null }}
                                                                                >
                                                                                <label class="ml-1 " for="">Add </label>
                                                                            </div>
                                                                            <div class="inputs col-md-6">
                                                                                <input type="checkbox" class="permissions" id="update{{ str_replace(' ','', $submenuList->submenu ) }}" name="roles[{{$submenuList->submenu}}][]" value="update"
                                                                                {{ $roleCollection->contains('update') ? 'checked' : null }}
                                                                                >
                                                                                <label class="ml-1" for="">Update</label>
                                                                            </div>
                                                                            <div class="inputs col-md-6">
                                                                                <input type="checkbox" class="permissions" id="view{{ str_replace(' ','', $submenuList->submenu ) }}" name="roles[{{$submenuList->submenu}}][]" value="view"
                                                                                    {{ $roleCollection->contains('view') ? 'checked' : null }}
                                                                                >
                                                                                <label class="ml-1" for="">View</label>
                                                                            </div>
                                                                            <div class="inputs col-md-6">
                                                                                <input type="checkbox" class="permissions" id="delete{{ str_replace(' ','', $submenuList->submenu ) }}" name="roles[{{$submenuList->submenu}}][]" value="delete"
                                                                                {{ $roleCollection->contains('delete') ? 'checked' : null }}
                                                                                >
                                                                                <label class="ml-1" for="">Delete</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @empty

                                                        @endforelse
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    @include('adminuser::modals.permissionlist')
    
@endsection
@push('after-script')
<script src="{{ asset('js/jquery.validate.min.js')}}"></script>
<script>

    $(document).ready(function(){
        $('#myTable1').bootstrapTable({
                stickyHeader: true,
            })
        $('#myDIV').css('display', 'none');

        /**making model active and inactive according to input checkbox
         **/
         $(document).on('click', '.activeStatus', function(){
            inputVal = $(this).closest('.custom-radio').find('[name=status]').val();
            if(inputVal == 'active')
            {
                $('#permission-preview-modal').find('#active').prop('checked', true);
                $('#permission-preview-modal').find('#inactive').prop('checked', false);
            }
            else{
                $('#permission-preview-modal').find('#active').prop('checked', false);
                $('#permission-preview-modal').find('#inactive').prop('checked', true);                
            }

        })
        $(document).on('click', '.main_menu_item', function(e){
            current = $(this)
            $(this).closest('ul').find('li').each(function(){
                console.log(current.text(), $(this).text())
                if(current.text() == $(this).text()){
                    // console.log('text match');
                }else{
                    $(this).removeClass('active');
                }
               
            })
            if($(this).hasClass('active') ){

                $(this).removeClass('active');
                     menu = $(this).text();
                     id = $(this).attr('id');

                    $('#main_menu_model').find('#'+id ).removeClass('active');

            }else{
                $(this).addClass('active');
                    menu = $(this).text();
                    // console.log('menu is',menu)
                    id = $(this).attr('id');

                    $('#main_menu_model').find('#'+id ).removeClass('active');
                //    $('#main_menu_model').find('#'+menu ).addClass('active');
            }
            getSubMenus($(this));
            makeOtherInactive($(this).index())


        })

        function makeOtherInactive(currentIndex)
        {
            $('#main_menu_model').each(function(){
                console.log($(this).index() , currentIndex);
                if($(this).index() == currentIndex)
                {

                }
                else{
                    $(this).removeClass('active');
                }
            })
        }

        // $(document).on('click','#checkAllMenu', function(){
        //     $('.main_menu li').each((index, val) => {
        //         if($(this).is(':checked')){
        //             $(val).addClass('active');
        //         }else{
        //             $(val).removeClass('active');
        //         }
        //     })
        //     getSubMenus();
        // })
        // function getSubMenus(current )
        // {

        //     menuIds = [];
        //     group_id = current.closest('.tab-pane').find('#permission_module_id').val();
        //     if(group_id){
        //         group_id = group_id
        //     }else{
        //         group_id = null
        //     }

        //     current.closest('.main_menu').find(' li.active').each( function(index, menuitem) {
        //         menuId = null ;
        //         console.log('liitem attr , data,  ',$(menuitem).data('menu'), menuitem, $(menuitem).text() )
        //         menuId =  $(menuitem).text() ;
        //         menuIds.push(menuId);
        //     })
        //     $.ajax({
        //             type : 'post',
        //             url : "{{ route('admin.user.groups.permissionsetup.submenus') }}",
        //             context : current,
        //             data :{
        //                 'menu_names' : menuIds,
        //                 'group_id' : group_id

        //             },
        //             success : function(response)
        //             {
        //                 current.closest('.tab-pane').find('#submenuitems').html(response.subMenuView);
        //             },
        //             errors : function(errors)
        //             {

        //             },
        //         })
        //     console.log(menuIds);
        // }

            function getSubMenus(current)
            {

                // if(current.hasClass('active')){
                // console.log('index is', current.closest('.main_menu').find(' li .active').eq(1));
                    // if(current.closest('.main_menu').find(' li .active').index() >= 1 ){
                if(current.closest('.main_menu').find(' li').hasClass('active')){
                    
                    submenuId = current.text() ;
                }else{
                    submenuId =  null  ;
                }
                
                menuIds = [];
                $.ajax({
                        type : 'post',
                        url : "{{ route('admin.user.groups.permissionsetup.submenus') }}",
                        data :{
                            // 'menu_names' : menuIds,
                            'menu_names' : [submenuId],
                        },
                        success : function(response)
                        {
                            $('#submenuitems').html(response.subMenuView);
                            $('#subMenuModal').append(response.submenublock);
                            syncPreviewItemWithRoleItemOFForm();
                        },
                        errors : function(errors)
                        {

                        },
                    })
            }

        /*
        this function is responsible for syncting role checked item to ajax append new list of permission
        */
        function syncPreviewItemWithRoleItemOFForm()
        {
            $('#permission-preview-modal #rolesPermission').find('[type=checkbox]').each(function(){
                if($(this).is(':checked')){
                    console.log('this item is checked',$(this).val());
                    selectorId = $(this).attr('id');
                    // $('#submenuitems').find('#'+$(this).attr('id')).prop('checked',true);
                    // $('#submenuitems').find('id /=' +$(this).attr('id')).prop('checked',true);
                    // console.log('expression value', '[id*="' +selectorId+'"]')
                    // $('#submenuitems').find('[id= "${selectorId}"]').prop('checked',true);
                    $('#submenuitems').find('[id*="' +selectorId+'"]').prop('checked', true);
                    // console.log('expression selector', $('#submenuitems').find('[id="' +selectorId+'"]').val())
                }
            })
        }


        $(document).on('click', '#allRoles', function(e){
            if($(this).is(':checked')){
                // console.log('checking all input field');
                checkedAllCheckBox($(this));
            }else
            {
                uncheckAllCheckBox($(this));
            }
            $(this).closest('.roleCompart').find('[type=checkbox]').trigger('change')
        })

        function checkedAllCheckBox(current)
        {
            current.closest('.roleCompart').find('input[type=checkbox]').each(function(){
                this.checked = true;
            })
        }
        function uncheckAllCheckBox(current)
        {
            current.closest('.roleCompart').find('input[type=checkbox]').each(function(){
                this.checked = false;
            })
        }
        //script for individeal subsidebart check operation
        // $(document).on('change', '.sidebaritem', function(){
        //     if($(this).is(':checked')){
        //         $(this).closest('.check-box').find('input[type=checkbox]').each(function(){
        //             this.checked = true;
        //         })
        //     }else{
        //         $(this).closest('.check-box').find('input[type=checkbox]').each(function(){
        //             this.checked = false;
        //         })
        //     }
        // })

        // //script for individual checkbox items
        // $(document).on('change', '.permissions', function(){
        //     if($(this).is(':checked')){
        //         $(this).closest('.check-box').find('.sidebaritem').prop('checked', true)
        //     }else{
        //         decideToCheckOperationForRoleItem($(this));
        //     }

        // })


        $(document).on('change', '.statusSwitch', function(){
            group_id = $(this).val();
            console.log('group id', group_id);
            $.ajax({
                type: 'post',
                url : "{{ route('admin.user.group.permission.updatestatus') }}",
                context : $(this),
                data : {
                    '_token' : "{{ csrf_token() }}",
                    'group_id' : group_id,
                
                },
                success : function(response)
                {
                    this.closest('tr').find('.groupStatusTd').text(response.status);
                }
            })
        })


        $(document).on('keyup', '#menuSearch', function(){
                var filter = jQuery(this).val();
                $(".main_menu li").each(function () {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show()
                    }
                // });
            });
        })
        $(document).on('keyup', '#submenusearch', function(){
                var filter = $(this).val();
                $(".searchPanel .check-box").each(function () {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show()
                    }
                });
        })

        // function decideToCheckOperationForRoleItem(current)
        // {

        // }


        //script for individeal subsidebart check operation
        $(document).on('change', '.sidebaritem', function(){
            if($(this).is(':checked')){
                $(this).closest('.check-box').find('input[type=checkbox]').each(function(){
                    $(this).prop('checked', true)
                })
            }else{
                $(this).closest('.check-box').find('input[type=checkbox]').each(function(){
                    $(this).prop('checked', false);
                })
            }
            appenRoleToModal($(this))
        })
        $(document).on('click', '.checkboxlabelHeader', function(){
            $(this).closest('.roles-header').find('.sidebaritem').trigger('change')
        })
        $(document).on('click', '.rolesItem', function(){
            $(this).closest('.inputs').find('[type=checkbox]').trigger('change')
        })

        //script for individual checkbox items
        $(document).on('change', '.permissions', function(){
            if($(this).is(':checked')){
                $(this).closest('.check-box').find('.sidebaritem').prop('checked', true)
            }else{
                decideToCheckOperationForRoleItem($(this));
            }
            appenRoleToModal($(this));
        })

        function decideToCheckOperationForRoleItem(current)
        {

        }

        function appenRoleToModal(current)
        {
            
            roleName = current.closest('.check-box').find('.sidebaritem').attr('id')
            console.log('appending roles to modal is', roleName);
            makeSubMenuActive(roleName)
            addOrRemovePermissionBlock(roleName);
           currentChecked =  current.closest('.check-box').html();
           $('#permission-preview-modal').find('#rolesPermission').append(currentChecked);
           current.closest('.check-box').find('input[type=checkbox]').each(function(){
                // console.log('checking is checked', $(this).is(':checked'),  $(this).attr('id') )
               if($(this).is(':checked'))
               {
                   idValue = $(this).attr('id') ;
                //    $('#permission-preview-modal').find('#'+idValue).prop({'checked': true,  "readonly" : true})
                //    $('#permission-preview-modal').find('#'+idValue).prop("readonly" , true)
                    $('#permission-preview-modal').find('[id*="'+idValue+'"]').prop({'checked': true,  "readonly" : true})
                    $('#permission-preview-modal').find('[id*="'+idValue+'"]').prop("readonly" , true)
               }
               else{
                    idValue = $(this).attr('id') ;
                //    $('#permission-preview-modal').find('#'+idValue).prop({'checked': false,  "readonly" : true})
                //    $('#permission-preview-modal').find('#'+idValue).prop("readonly" , true)
                    $('#permission-preview-modal').find('[id*="'+idValue+'"]').prop({'checked': false,  "readonly" : true})
                    $('#permission-preview-modal').find('[id*="'+idValue+'"]').prop("readonly" , true)
               }
           })

           $('#permission-preview-modal .virtualDivForModel').each(function(){
              if( $(this).find('.roles-header .sidebaritem').is(':checked')){

               }else{
                   $(this).remove();
               }
           })
        }

        function addOrRemovePermissionBlock(roleIdOFCurrent)
        {
            $('#permission-preview-modal #rolesPermission .inputs').each(function(){
                // console.log('comparing block' ,roleIdOFCurrent, $(this).find('input:checkbox:first').attr('id'));
                if( $(this).find('input:checkbox:first').attr('id') == roleIdOFCurrent){
                    console.log('closest virtualdiv', $(this).find('input:checkbox:first').closest('.virtualDivForModel').html())
                    $(this).find('input:checkbox:first').closest('.virtualDivForModel').remove();
                }
            })
        }

        function makeSubMenuActive(submenu)
        {
            // console.log('making item active', submenu, $('#permission-preview-modal #subMenuModal').find('li:contains("'+submenu+'")' ).text() )
            
            
            // $('#permission-preview-modal #subMenuModal').find('#'+submenu).addClass('active');
            $('#permission-preview-modal #subMenuModal').find('[id*="' +submenu+'"]').addClass('active');

        }

            //validating input field
            $("#permissionEditForm").validate({
                rules: {
                    permission_name : 'required',
                    flddescription : "required",
                    // module_name: "required",
                    status: "required",
                    "submenus[]": {
                        required: true,
                        minlength: 1
                    },

                },
                messages: {
                    permission_name: "Please enter module name",
                    flddescription : 'Please provide a description',
                    status: "Please check status ",
                    "submenus[]": {
                        required: "Please select role item",
                        // minlength: "Your username must consist of at least 2 characters"
                    },
                },
        });

                /* script for filter data*/

            $(document).on('submit', '#filterFrom', function(e){
                e.preventDefault()
                formData = $('#filterFrom').serialize();
                console.log(formData);
                $.ajax({
                    type : 'post',
                    url : "{{ route('admin.user.groups.permissionsetup.filter') }}",
                    data : formData,
                    success : function(response)
                    {
                        $('#permisionModules').html(response.permisionModules)
                    },
                    errors : function(errors)
                    {

                    },
                })
        })



        $(document).on('click', '#redetFilter', function(){           
            console.log('i am clocking on reset input');
            $('#status').prop('selectedIndex',0);
            $('#groupfilter').val(null).trigger('change');

        })

        $(document).on('click', '#resetInput', function(){
            console.log('clearing input');
            $('#flddescription').val('');
            $('#permission_name').val('');
            $('#menuSearch').val('');
            $('#submenusearch').val('');
            // $('#menuSearch').val();
            $('#menuSearch').trigger('keyup');
            $('#submenusearch').trigger('keyup');
            // console.log( 'item to removes',  $('#permission-preview-modal').find('#rolesPermission').html() )
            $('#permission-preview-modal').find('.virtualDivForModel').remove()
            $('#permission-preview-modal').find('#description').val('')
            $('#permission-preview-modal').find('#group').val('')
            // $('#permission-preview-modal').find('#permission_name').val('')
            uncheckAllCheckBox($('#allRoles'));
            $('.main_menu li').each((index, val) => {
                $(val).removeClass('active')
            })
            $('.submenuitems').find('.check-box').remove();
             
        })
        // #permission_name, #flddescription
        $(document).on('change', '#permission_name' , function(){
            console.log('i am type')
            $('#permission-preview-modal').find('#group').val($(this).val())
            // $('#permission-preview-modal').find('#flddescription').val($('#permission_nflddescriptioname').val() )
        })

        $(document).on('change', '#flddescription',  function(){
            console.log('i am type dscrition')
            $('#permission-preview-modal').find('#description').val($(this).val() )
        })

        function menuSearch() {
            $("#menuSearch").keyup(function () {
                var filter = jQuery(this).val();
                $(".main_menu li").each(function () {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show()
                    }
                });
            });
        }

        function subMenuSearchSidebar() {
            $("#submenusearch").keyup(function () {
                var filter = $(this).val();
                $(".searchPanel .check-box").each(function () {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show()
                    }
                });
            });
        }

        $('#permissionEditForm').submit(function(e){
            e.preventDefault();
            $('#permissionAddFormModel').submit();
        })
    })

    function myFunctionSearchPermission() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("permision-search");
            filter = input.value.toUpperCase();
            table = document.getElementById("permission-table-search");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
    }

</script>

@endpush
