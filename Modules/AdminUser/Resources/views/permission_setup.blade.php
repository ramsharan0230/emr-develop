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
          <a class="nav-link active" id="home-tab" style="background-color: unset;" data-toggle="tab" href="#permissionview" role="tab" aria-controls="permissionview" aria-selected="true">Permission View</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" style="background-color: unset;" data-toggle="tab" href="#addpermission" role="tab" aria-controls="addpermission" aria-selected="false">Add Permission</a>
        </li>
      </ul>


      <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="permissionview" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row">

                <!-- Filter  -->
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
                    <div class="iq-card-body">
                        <form id="filterFrom" >
                            @csrf
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 style="margin: 5px 0 5px 0;">Filter Group Profile</h5>
                                <button type="button" id="resetInput" class="btn btn-primary">
                                <i class="fa fa-sync"></i> Reset
                                </button>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 col-lg-4">
                                <div class="form-group form-row flex-column align-items-start">
                                        <label class="col-lg-12 col-sm-12">Groups</label>
                                        <div class="col-lg-12 col-sm-12">
                                            <select class="form-control select2" id="groupfilter" name="module_name" data-variable="">
                                            <option value="">-- Select Permission --</option>
               

                                            @forelse ($groups as $key => $group )
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
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
                                            <select id="status" class="form-control" name="status" data-variable="">
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

                <div class="col-sm-12" >
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-body">


                        <div class="table-sticky-th" style="width: 100%;">
                            <table
                                class="table expandable-table table-responsive custom-table table-bordered table-striped  mt-c-15 table-sticky-th"
                                id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                data-pagination="false"
                                data-resizable="true">
                                <thead class="thead-light">
                                    <tr>
                                            <th style="width: 3%">S.N.</th>
                                            <th>Group Name</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="permisionModules">
                                    @forelse ($groups as $group )
                                    @if($group->id !==1 )
                                    @if($group->id !==2)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{ $group->name }}</td>
                                        <td class="groupStatusTd">{{ ($group->status == 'active')? 'Active' : 'Inactive'}}</td>
                                        <td>
                                            <div class="d-flex flex-row">
                                                <a href="{{ route('admin.user.groups.permissionsetup.submenus.edit', $group->id) }}" class="btn btn-sm btn-primary adminMgmtTableBtn" title="Edit permission"><i class="fa fa-edit"></i></a>
                                                <a href="#" class="btn btn-sm btn-primary ml-1" data-toggle="modal" data-target="#permission-detail-modal" data-groupid="{{ $group->id }}" ><i class="fa fa-eye"> </i> </a>                                               
                                                <label class="switch ml-1">
                                                    <input type="checkbox" class="statusSwitch" value="{{ $group->id}}" @if($group->status == 'active')checked @endif>
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

                        <!-- Pagination  -->
                        {{-- <nav aria-label="..." class="mt-2" id="paginate_div"></nav> --}}
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade " id="addpermission" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row">
                <div class="col-sm-12">
                   <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                       <div class="iq-card-header d-flex justify-content-between">
                         <div class="iq-header-title">
                               <h4 class="card-title">Add New Group Permission</h4>
                         </div>
                         <div class="d-flex flex-row">
                           <button type="button" id="redetFilter" class="btn btn-outline-primary"><i class="fa fa-sync"></i>&nbsp;Reset</button>
                           <button type="button" class="btn btn-primary ml-1"  data-toggle="modal" data-target="#permission-preview-modal"><i class="fa fa-eye"></i>&nbsp;Preview</button>
                         </div>
                       </div>
                       <div class="iq-card-body">
                           <form  method="POST" id="permissionAddForm" action="">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex flex-column">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Name</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <input id="permission_name" type="text" value="{{ old('permission_name') }}" name="permission_name" class="form-control" id="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Description</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <textarea name="flddescription" id="flddescription"  style="width: 100%" rows="2">{{ old('flddescription') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-row flex-column align-items-start">
                                                    <label class="col-lg-12 col-md-12">Status</label>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="d-flex flex-row">
                                                            <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                                <input type="radio" name="status" value="active" class=" custom-control-input" id="" checked>
                                                                <label for="" class="custom-control-label activeStatus">Active</label>
                                                            </div>
                                                            <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                                <input type="radio" name="status" value="inactive" class=" custom-control-input" id="">
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
                                         
                                        </div>
                                        <div class="col-md-12 p-0 mb-1">
                                            <input type="text" class="form-control" id="menuSearch" placeholder="Search">
                                        </div>
                                        <div class="list">
                                            <ul class="main_menu">
                                                @forelse ($sidebarmenu as $mainmenu )
                                                <li  class="main_menu_item" id="{{$mainmenu->id}}" data-menu="{{$mainmenu->id}}">{{ $mainmenu->mainmenu }}</li>
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
                                                <label class="ml-1 allRoleSelect" for="">All</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 p-0 mb-1">
                                            <input type="text" class="form-control" id="submenusearch" placeholder="Search">
                                        </div>
                                        <div id="submenuitems">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="float-right mt-1">
                                <button type="submit" class="btn btn-primary btn-action" id="submitForm">Save</button>
                            </div>
                           </form>
                       </div>
                   </div>
                </div>
             </div>
        </div>

      </div>
   </div>
   <div class="modal fade bd-example-modal-lg" id="permission-preview-modal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('admin.user.groups.permissionsetup.submenus.store') }}" method="post" id="permissionAddFormModel">
            @csrf
            <input type="hidden" name="form_to_redirect" value="">
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
                                                    <input type="text" id="group" value="{{ old('permission_name') }}" name="permission_name" class="form-control" id="" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-sm-12">Description</label>
                                                <div class="col-lg-12 col-sm-12">
                                                    <textarea name="flddescription" id="description"  style="width: 100%" rows="2" readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group form-row flex-column align-items-start">
                                                <label class="col-lg-12 col-md-12">Status</label>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="row flex-row">
                                                        <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" name="status" id="active" value="active" class=" custom-control-input" id="active" checked readonly>
                                                            <label for="" class="custom-control-label">Active</label>
                                                        </div>
                                                        <div class="d-flex flex-row col-md-3 custom-control custom-radio custom-control-inline">
                                                            <input type="radio" name="status" id="inactive" value="inactive" class=" custom-control-input" id="inactive" readonly>
                                                            <label for="" class="custom-control-label">Inactive</label>
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
                                                    <li  class="" id="{{$mainmenu->mainmenu}}" data-menu="{{$mainmenu->id}}">{{ $mainmenu->mainmenu }}</li>
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
                                            
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column roleCompart">
                                            <h6 class="mb-1">Roles</h6>
                                            <!-- <div id="submenuitems">
                                            </div> -->
                                            <div id="rolesPermission">
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-action" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
    
    
    @include('adminuser::modals.permissionlist')

    @endsection
@push('after-script')
<script src="{{ asset('js/jquery.validate.min.js')}}"></script>
<script>
    $(document).ready(function(){

        $('#myTable1').bootstrapTable({
                // stickyHeader: true,
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
                // console.log(current.text(), $(this).text())
                if(current.text() == $(this).text()){
                    // console.log('text match');
                }else{
                    $(this).removeClass('active');
                }
               
            })

            if($(this).hasClass('active') ){
                $(this).removeClass('active');
                //    $('#main_menu_model').find('li:contains("Users")' ).removeClass('active');
                //    $('#main_menu_model').find('li' ).removeClass('active');
                     menu = $(this).text();
                //    $('#main_menu_model').find('li:has("'+User+'")' ).addClass('active');
                    $('#main_menu_model').find('#'+menu ).removeClass('active');

            }else{
                $(this).addClass('active');
                    menu = $(this).text();
                //    $('#main_menu_model').find('li:has("'+User+'")' ).addClass('active');
                   $('#main_menu_model').find('#'+menu ).addClass('active');
                //    $('#main_menu_model').find('li' ).addClass('active');
            }
            getSubMenus($(this));


        })

        // modelMenu = $(this).text();
        // $('#main_menu_model').find('li:contains(' + modelMenu+ ')' ).removeClass('active');

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
        function getSubMenus(current)
        {
            if(current.closest('.main_menu').find(' li').hasClass('active')){              
                submenuId = current.text() ;
            }else{
                submenuId =  null  ;
            }
            menuIds = [];
            // $('.main_menu li.active').each( function(index, menuitem) {
            //     menuId = null ;
            //     menuId =  $(menuitem).text() ;
            //     menuIds.push(menuId);
            // })
            $.ajax({
                    type : 'post',
                    url : "{{ route('admin.user.groups.permissionsetup.submenus') }}",
                    data :{
                        // 'menu_names' : menuIds,
                        // 'menu_names' : [current.text()],
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
            // console.log(menuIds);
        }
        /*
            this function is responsible for syncting role checked item to ajax append new list of permission
         */
        function syncPreviewItemWithRoleItemOFForm()
        {
            $('#permission-preview-modal #rolesPermission').find('[type=checkbox]').each(function(){
                if($(this).is(':checked')){
                    console.log('this item is checked',$(this).val());
                    $('#submenuitems').find('#'+$(this).attr('id')).prop('checked',true);
                    // $('#submenuitems').find('[id*=' +$(this).attr('id')+']').prop('checked',true);
                }
            })
        }

        $(document).on('click', '#allRoles', function(e){
            if($(this).is(':checked')){
                console.log('checking all input field');
                checkedAllCheckBox($(this));
            }else
            {
                uncheckAllCheckBox($(this));
            }
            $(this).closest('.roleCompart').find('[type=checkbox]').trigger('change')
            // $(this).closest('.roleCompart').find('[type=checkbox]').trigger('change')
        })
        $(document).on('click', '.allRoleSelect', function(e){
            if($(this).is(':checked')){
                console.log('checking all input field');
                checkedAllCheckBox($(this));
            }else
            {
                uncheckAllCheckBox($(this));
            }
            $(this).closest('.roleCompart').find('[type=checkbox]').trigger('change')
            // $(this).closest('.roleCompart').find('[type=checkbox]').trigger('change')
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
            // makeSubMenuActive()


        })

        function decideToCheckOperationForRoleItem(current)
        {
            
        }

        function appenRoleToModal(current)
        {
            roleName = current.closest('.check-box').find('.sidebaritem').attr('id')
            makeSubMenuActive(roleName)
            addOrRemovePermissionBlock(roleName);
            

           currentChecked =  current.closest('.check-box').html();
           $('#permission-preview-modal').find('#rolesPermission').append(currentChecked);
           current.closest('.check-box').find('input[type=checkbox]').each(function(){
                
               if($(this).is(':checked'))
               {
                   idValue = $(this).attr('id') ;
                   $('#permission-preview-modal').find('#'+idValue).prop({'checked': true,  "readonly" : true})
                   $('#permission-preview-modal').find('#'+idValue).prop("readonly" , true)
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
                    console.log($(this).find('input:checkbox:first').closest('.virtualDivForModel').html())
                    $(this).find('input:checkbox:first').closest('.virtualDivForModel').remove();
                }
            })
        }

        function makeSubMenuActive(submenu)
        {
            console.log('making item active', submenu, $('#permission-preview-modal #subMenuModal').find('li:contains("'+submenu+'")' ).text() )
            $('#permission-preview-modal #subMenuModal').find('#'+submenu).addClass('active');
            // $('#permission-preview-modal #subMenuModal').find(submenu).addClass('active');
        }

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

        //validating input field
        $("#permissionAddForm").validate({
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

        $(document).on('keyup', '#menuSearch', function(){
            menuSearchSidebar();
        })

        $(document).on('keyup', '#submenusearch', function(){
            subMenuSearchSidebar();
        })

        $(document).on('click', '#redetFilter', function(){
            // console.log('clearing filed')
            $('#flddescription').val('');
            $('#permission_name').val('');
            $('#menuSearch').val('');
            $('#submenusearch').val('');
            // $('#menuSearch').val();
            $('#menuSearch').trigger('keyup');
            $('#submenusearch').trigger('keyup');
            uncheckAllCheckBox($('#allRoles'));
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


        $(document).on('click', '#resetInput', function(){
            console.log('clearing filed')
            $('#status').prop('selectedIndex',0);
            $('#groupfilter').val(null).trigger('change');
        })

        // #permission_name, #flddescription
        $(document).on('change', '#permission_name' , function(){
            $('#permission-preview-modal').find('#group').val($(this).val())
            // $('#permission-preview-modal').find('#flddescription').val($('#permission_nflddescriptioname').val() )
        })

        $(document).on('change', '#flddescription',  function(){
            console.log('i am type dscrition')
            $('#permission-preview-modal').find('#description').val($(this).val() )
        })

        $('#permissionAddForm').submit(function(e){
            e.preventDefault();
            $('#permissionAddFormModel').submit();
        })


    })



    function menuSearchSidebar() {
            $("#menuSearch").keyup(function () {
                var filter = $(this).val();
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
                $(".submenulist .check-box").each(function () {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).hide();
                    } else {
                        $(this).show()
                    }
                });
            });
        }

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
