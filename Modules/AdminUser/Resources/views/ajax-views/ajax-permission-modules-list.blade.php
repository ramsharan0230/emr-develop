@forelse ($permisionFilter as $permission )
    @if($permission->id !== 1)
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>{{ $permission->name }}</td>
            <td class="groupStatusTd">{{ ($permission->status == 'active')? 'Active' : 'Inactive'}}</td>
            <td>
            <a href="{{ route('admin.user.groups.permissionsetup.submenus.edit', $permission->id) }}" class="btn btn-sm btn-info adminMgmtTableBtn" title="Edit permission"><i class="fa fa-edit"></i></a>
            &nbsp;&nbsp;  <a href="#" class="ml-1" data-toggle="modal" data-target="#permission-detail-modal" data-groupid="{{ $permission->id }}" ><i class="fa fa-eye"> </i> </a>
            &nbsp;
            <label class="switch ml-1">
            <input type="checkbox" class="statusSwitch" value="{{ $permission->id}}" @if($permission->status == 'active')checked @endif>
            <span class="slider round"></span>
            </label>
            </td>
        </tr>
    @endif
@empty
    <tr> no result found </tr>
@endforelse
