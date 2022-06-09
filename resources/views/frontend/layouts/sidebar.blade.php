<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="{{ url('admin/dashboard') }}">
            @if( Options::get('brand_image') && Options::get('brand_image') != "" )
            @if(file_exists('uploads/config/'.Options::get('brand_image')))
            <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid logo-chirayu" alt="" />
            @else
            <img src="{{asset('assets/images/sidebarlogo.png')}}" class="img-fluid logo-chirayu" alt="logo-cogent">
            @endif
            @endif
        </a>
        <div class="iq-menu-bt-sidebar">
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-menu-2-line"></i></div>
                    <div class="hover-circle"><i class="ri-menu-2-line"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menu Search Box -->

    <!-- End Of Menu Search Box -->
    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            @include('frontend.common.search_menu_input')

            <ul id="iq-sidebar-toggle" class="iq-menu mainmenulist">
                @php $menus = \App\Utils\Helpers::getMenus(); @endphp
                @if($menus)
                @foreach($menus as $index => $menu)
                @php 
                    $routes = collect($menu['sub_menus'])->pluck('route')->toArray();
                    $class = '';
                    foreach($routes as $route){
                        if(str_contains($route, '/')){
                            if (URL::current() == URL::to($route)){
                                $class="active main-active";
                            }
                        }else{
                            if (Route::is($route)){
                                $class="active main-active";
                            }
                        }
                    }
                @endphp
                <li data-index="{{ $index }}" data-position="{{ $index }}" nameofmenu="{{$menu['mainmenu']}}" class="{{$class}}">
                    <a href="#{{strtolower(str_replace(' ', '-', $menu['mainmenu']))}}" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false">
                        <i class="{{$menu['icon']?$menu['icon']:'ri-settings-fill'}}"></i>
                        <span>{{$menu['mainmenu']}} </span>
                        <i class="ri-arrow-right-s-line iq-arrow-right"></i>
                    </a>
                    @if(!empty($menu['sub_menus']))
                    <ul id="{{strtolower(str_replace(' ', '-', $menu['mainmenu']))}}" class="iq-submenu collapse childmenulist" data-parent="#iq-sidebar-toggle">
                        @foreach($menu['sub_menus'] as $submenu)
                        @if($submenu['submenu'] == 'Patient Profile')
                        <li data-index="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" data-position-child="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" childmenuname="{{$submenu['submenu']}}" pr="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}"><a href="javascript:void(0);" onclick="reportMainMenu.patientProfileModal()"> Patient
                                Profile</a></li>
                        @else
                            @if(str_contains($submenu['route'], '/'))
                                <li data-index="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" data-position-child="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" childmenuname="{{$submenu['submenu']}}" pr="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" class="{{ (URL::current() == URL::to($submenu['route'])) ? 'active main-active':''   }}">
                                    <a href="{{URL::to($submenu['route'])}}">{{$submenu['submenu']}}</a>
                                </li>
                            @else
                                @if(Route::has($submenu['route']))
                                    <li data-index="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" data-position-child="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" childmenuname="{{$submenu['submenu']}}" pr="{{ strtolower(str_replace(' ', '-', $submenu['submenu'])) }}" class="{{ Route::is($submenu['route']) ? 'active main-active':''   }}">
                                        <a href="{{ route($submenu['route']) }}">{{$submenu['submenu']}}</a>
                                    </li>
                                @endif
                            @endif
                        @endif
                        @endforeach
                    </ul>
                    @endif

                </li>
                @endforeach
                @endif
                <!-- <li>
                    <a href="{{ route('report.noraForm') }}">
                        <i class="fas fa-hospital"></i>
                        <span>Nora Form</span>
                    </a>
                </li> -->

            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>
@push('after-script')
<script>
    $(document).ready(function() {
        $('.mainmenulist').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });

                saveNewPositions();
            }
        });

        $('.childmenulist').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position-child') != (index + 1)) {
                        $(this).attr('data-position-child', (index + 1)).addClass('updated');
                    }
                });

                saveNewPositionsChild();
            }
        });
    });

    function saveNewPositions() {
        var positions = [];
        $('.updated').each(function() {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position'), $(this).attr('nameofmenu')]);
            $(this).removeClass('updated');
        });
        console.log(positions)

        $.ajax({
            url: "{{ route('save.order.sidebar') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                update: 1,
                positions: positions
            },
            success: function(response) {
                console.log(response);
            }
        });
    }


    function saveNewPositionsChild() {
        var positions = [];
        $('.updated').each(function() {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position-child'), $(this).attr('childmenuname')]);
            $(this).removeClass('updated');
        });
        console.log(positions)

        $.ajax({
            url: "{{ route('save.order.sidebar') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                update: 1,
                positions: positions
            },
            success: function(response) {
                console.log(response);
            }
        });
    }
</script>
@endpush