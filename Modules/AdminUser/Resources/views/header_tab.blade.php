<div class="panel-heading" style="border-bottom: 1px solid #c1c1c1;">
    <div class="panel-control" style="float: left;">
        <ul class="nav nav-tabs settings-header-nav">
            <li class="{{ isset($header_nav) && $header_nav == 'users' ? 'active' : '' }}">
                <a href="{{ route('admin.user.list') }}?u_type=individual" style="color: black;font-weight: bold;">USERS</a>
            </li>

            <li class="{{ isset($header_nav) && $header_nav == 'groups' ? 'active' : '' }}">
                <a href="{{ route('admin.user.groups') }}?u_type=outlets" style="color: black;font-weight: bold;">GROUPS</a>
            </li>

        </ul>
    </div>
</div>