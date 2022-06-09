<section class="nav_menu">
    {{--<nav class="navbar navbar-expand-md menu_nav">
        <div id="navbarCollapse" class="">
            <ul class="nav navbar-nav">

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('create') }}" class="nav-link">Create New Record</a>
                </li>

            </ul>
        </div>
    </nav>--}}

    <div class="row">
        <div class="text-center"  style="margin: 20px 0px -20px 500px;">
            <h4>
                <a href="{{ route('neuro') }}" style="text-decoration: none;color: #000000;">
                    Neurosurgical ICU Chart
                </a>
            </h4>
        </div>
        <div style="margin: 20px 0px -50px 260px;">
{{--            <i class="fa fa-user"></i> {{ Auth::guard('admin')->check() ? \Illuminate\Support\Str::limit(Auth::guard('admin')->user()->fullname,20,'...') : '' }} |--}}
{{--            <a href="{{ route('logout') }}" class="">Logout</a>--}}
        </div>
    </div>

</section>
