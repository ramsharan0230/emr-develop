<div class="iq-card-body">
    <div class="row">
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
                                    @else
                                        {{-- d-none --}}
                                    @endif

                                @endif
                            @empty
                            @endforelse
                            " 
                            
                            @forelse ($permissionModule->permission as $permission_referencesshow )
                                @if(!is_null($permission_referencesshow->permissionRefrenceSideBarMenu))
                                    @if( $permission_referencesshow->permissionRefrenceSideBarMenu->mainmenu == $mainmenu->mainmenu )
                                        {{-- style = "display : block" --}}
                                    @else
                                        {{-- style = "display : none" --}}
                                    @endif
                                @endif
                            @empty
                            @endforelse

                            id="{{str_replace(' ', '',$mainmenu->mainmenu)}}" data-menu="{{$mainmenu->id}}">{{ $mainmenu->mainmenu }}</li>
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
                            @forelse ($permissionModule->permission as $permission_references )
                            @if(!is_null($permission_references->permissionRefrenceSideBarMenu))
                                @if( $permission_references->permissionRefrenceSideBarMenu->submenu == $menu->submenu)
                                {{-- style ="display:block" --}}
                                @else
                                  {{-- style ="display:none" --}}
                                @endif
                                
                            @endif
                        @empty
                        @endforelse
                                 
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
                                                <input type="checkbox" class="sidebaritem" id="{{ str_replace(' ','', $submenuList->submenu ) }}" name="submenus[]" value="{{ $submenuList->submenu }}" {{$group_permission_references->has(ucfirst($submenuList->submenu)) ? 'checked' : null}} 
                                                disabled>
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
                                                        disabled>
                                                    <label class="ml-1 " for="">Add </label>
                                                </div>
                                                <div class="inputs col-md-6">
                                                    <input type="checkbox" class="permissions" id="update{{ str_replace(' ','', $submenuList->submenu ) }}" name="roles[{{$submenuList->submenu}}][]" value="update"
                                                    {{ $roleCollection->contains('update') ? 'checked' : null }}
                                                    disabled>
                                                    <label class="ml-1" for="">Update</label>
                                                </div>
                                                <div class="inputs col-md-6">
                                                    <input type="checkbox" class="permissions" id="view{{ str_replace(' ','', $submenuList->submenu ) }}" name="roles[{{$submenuList->submenu}}][]" value="view"
                                                        {{ $roleCollection->contains('view') ? 'checked' : null }}
                                                        disabled>
                                                    <label class="ml-1" for="">View</label>
                                                </div>
                                                <div class="inputs col-md-6">
                                                    <input type="checkbox" class="permissions" id="delete{{ str_replace(' ','', $submenuList->submenu ) }}" name="roles[{{$submenuList->submenu}}][]" value="delete"
                                                    {{ $roleCollection->contains('delete') ? 'checked' : null }}
                                                    disabled>
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
        </div>
    {{-- </form> --}}
</div>
