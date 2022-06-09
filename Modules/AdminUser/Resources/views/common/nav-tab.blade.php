<style>
.form-group label {
    border: 0 !important;
    }
</style>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="out-patient" role="tabpanel" aria-labelledby="home-tab">
        {{--navbar--}}
        <nav class="navbar navbar-expand-lg">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link active" role="button" href="{{ route('admin.user.list') }}">User</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" role="button" href="{{ route('admin.user.groups') }}">Group</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" role="button" href="{{ route('admin.user.comp.access') }}">Group Mac</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" role="button" href="{{ route('admin.user.mac.inactive.list') }}">Mac Request</a>
                    </li>
                   
                </ul>
            </div>
        </nav>
        {{--end navbar--}}
    </div>
</div>
