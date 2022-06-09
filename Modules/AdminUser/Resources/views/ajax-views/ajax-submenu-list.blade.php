<div class="list submenulist">

    @php
    if(!is_null($permissionModule) ){
        $group_permission_references = $permissionModule->permission->groupBy(function($item){
            return trim(ucfirst($item->short_desc));
        });
    }

    @endphp
    @forelse ($submenus  as $menu )

    @php
        if(!is_null($permissionModule) ){
            $roles = [];
            // dd($group_permission_references, ucfirst($submenuList->submenu ),$group_permission_references->has(ucfirst($submenuList->submenu)));
            if($group_permission_references->has(ucfirst($menu->submenu)))
            {
                // dd($group_permission_references[ucfirst($submenuList->submenu)] );
                foreach($group_permission_references[ucfirst($menu->submenu)] as $role)
                {
                    $arrayOfString = explode('-', $role->code);

                    $roles[] = end($arrayOfString);

                }
            }
            $roleCollection = collect($roles);
         }
    @endphp
        @if(!is_null($permissionModule) )
            <div class="check-box">
                <div class="virtualDivForModel">
                    <div class="inputs roles-header">
                        <input type="checkbox" class="sidebaritem" id="{{ str_replace(' ', '',$menu->submenu ) }}" name="submenus[]" value="{{ $menu->submenu  }}" {{$group_permission_references->has(ucfirst($menu->submenu)) ? 'checked' : null}} >
                        <label class="ml-1" for="">{{ ucfirst($menu->submenu) }}</label>
                    </div>
                    <div class="child-box">
                        <div class="inputs col-md-6">
                            <input type="checkbox" class="permissions" name="roles[{{$menu->submenu}}][]" id="add{{str_replace(' ', '',$menu->submenu)}}" value="add"
                                {{ $roleCollection->contains('add') ? 'checked' : null }}
                            >
                            <label class="ml-1 rolesItem" for="">Add </label>
                        </div>
                        <div class="inputs col-md-6">
                            <input type="checkbox" class="permissions" id="update{{str_replace(' ','',$menu->submenu)}}" name="roles[{{$menu->submenu}}][]" value="update"
                            {{ $roleCollection->contains('update') ? 'checked' : null }}
                            >
                            <label class="ml-1" for="">Update</label>
                        </div>
                        <div class="inputs col-md-6">
                            <input type="checkbox" class="permissions" id="view{{str_replace(' ', '',$menu->submenu)}}" name="roles[{{$menu->submenu}}][]" value="view"
                                {{ $roleCollection->contains('view') ? 'checked' : null }}
                            >
                            <label class="ml-1" for="">View</label>
                        </div>
                        <div class="inputs col-md-6">
                            <input type="checkbox" class="permissions" id="delete{{ str_replace(' ', '', $menu->submenu)}}" name="roles[{{$menu->submenu}}][]" value="delete"
                            {{ $roleCollection->contains('delete') ? 'checked' : null }}
                            >
                            <label class="ml-1" for="">Delete</label>
                        </div>
                    </div>
                </div>
            </div>
            @else

            <div class="check-box">
                <div class="virtualDivForModel">
                    <div class="inputs roles-header">
                            <input type="checkbox" class="sidebaritem" id="{{str_replace(' ', '', $menu->submenu) }}" name="submenus[]" value="{{ $menu->submenu }}">
                            <label class="ml-1 checkboxlabelHeader" for="">{{ $menu->submenu }}</label>
                        </div>
                        <div class="child-box">
                            <div class="inputs col-md-6">
                                <input type="checkbox" class="permissions" name="roles[{{$menu->submenu}}][]" id="add{{str_replace(' ','', $menu->submenu)}}" value="add">
                                <label class="ml-1 rolesItem" for="">Add </label>
                            </div>
                            <div class="inputs col-md-6">
                                <input type="checkbox" class="permissions" id="update{{str_replace(' ', '',$menu->submenu)}}" name="roles[{{$menu->submenu}}][]" value="update">
                                <label class="ml-1 rolesItem" for="">Update</label>
                            </div>
                            <div class="inputs col-md-6">
                                <input type="checkbox" class="permissions" id="view{{str_replace(' ', '', $menu->submenu)}}" name="roles[{{$menu->submenu}}][]" value="view">
                                <label class="ml-1 rolesItem" for="">View</label>
                            </div>
                            <div class="inputs col-md-6">
                                <input type="checkbox" class="permissions" id="delete{{str_replace(' ', '', $menu->submenu)}}" name="roles[{{$menu->submenu}}][]" value="delete">
                                <label class="ml-1 rolesItem" for="">Delete</label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    @empty

    @endforelse
</div>
