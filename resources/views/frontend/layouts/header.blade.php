<style>
    /*.search-box-list{*/
    /*    list-style: none;*/
    /*    max-height: 200px;*/
    /*    overflow: auto;*/
    /*    cursor: pointer;*/
    /*}*/

    .bootstrap-table .fixed-table-container .fixed-table-body{
        overflow-x: unset;
        overflow-y: unset;
    }

    .navbar-list li .iq-sub-dropdown .iq-subcard {
        font-size: inherit;
        padding: 8px;
        line-height: normal;
        color: inherit;
        border-bottom: 1px solid #f3f7fd;
        display: inline-block;
        width: 100%;
    }
    .iq-sub-dropdown .iq-cardicon {
        background-color: #deefff;
        display: flex;
        width: 30px;
        height: 30px;
        line-height: 31px;
        font-size: 18px;
        justify-content: center;
        align-items: center;
    }
    h6 {
        font-weight: 500;
        font-size: 14px;
    }
    .round {
        border-radius: 10px !important;
    }
    .iq-cardicon i {
        color: #144069;
    }
    .iq-subcard:hover {
        background-color: #deefff;
    }
    .iq-subcard:hover .iq-cardicon {
        background-color: #144069;
        transition: 0.5s;
    }
    .iq-subcard:hover .iq-cardicon i {
        color: white;
    }
    .ellipsis {
        display: inline-block;
        width: 65%;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
    }

    .search-box-list {
        list-style: none;
        max-height: 250px;
        overflow: auto;
        cursor: pointer;
        position: absolute;
        background: #fafafa;
        padding: 10px;
        border-radius: 5px;
        margin-top: 8px;
        border: 1px solid aliceblue;
        box-shadow: 3px 2px 23px lightblue;
    }

    li.search-patient-list:hover {
        background-color: #ddefff;
    }

    .search-patient-list {
        padding: 10px;
    }

    #patient-search-lists-modal  .modal-content {
        min-height: 400px;
        max-height: 600px;
    }

    #patient-search-lists-modal .dropdown-menu{
        left:-110px!important;
    }


</style>

<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <div class="iq-sidebar-logo">
            <div class="top-logo">
                <a href="index.html" class="logo">
                    <img src="{{ asset('new/images/logo.png') }}" class="img-fluid" alt=""/>
                    <span>Cogent Health</span>
                </a>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-search-bar">
                <div class="searchbox">
                    <input type="text" class="text search-input" id="header-search-input"
                           placeholder="Patient search..."/>
                    <a class="search-link" id="header-search" href="#"><i class="ri-search-line"></i></a>
                </div>
                <div class="searchbox" id="searchData">
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ri-menu-3-line"></i>
            </button>
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-menu-2-line"></i></div>
                    <!-- <div class="hover-circle"><i class="ri-more-2-fill"></i></div> -->
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto navbar-list">
                    <li class="nav-item">

                        <div class="iq-sub-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div class="iq-card-body p-0">
                                    <div class="bg-primary p-3">
                                        <h5 class="mb-0 text-white">
                                            All Notifications<small class="badge badge-light float-right pt-1">4</small>
                                        </h5>
                                    </div>
                                    <a href="#" class="iq-sub-card">
                                        <div class="media align-items-center">
                                            <div class="">
                                                <img class="avatar-40 rounded"
                                                     src="{{ asset('new/images/user/01.jpg') }}" alt=""/>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0">Emma Watson Bini</h6>
                                                <small class="float-right font-size-12">Just Now</small>
                                                <p class="mb-0">95 MB</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="iq-sub-card">
                                        <div class="media align-items-center">
                                            <div class="">
                                                <img class="avatar-40 rounded"
                                                     src="{{ asset('new/images/user/02.jpg') }}" alt=""/>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0">New customer is join</h6>
                                                <small class="float-right font-size-12">5 days ago</small>
                                                <p class="mb-0">Jond Bini</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="iq-sub-card">
                                        <div class="media align-items-center">
                                            <div class="">
                                                <img class="avatar-40 rounded"
                                                     src="{{ asset('new/images/user/03.jpg') }}" alt=""/>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0">Two customer is left</h6>
                                                <small class="float-right font-size-12">2 days ago</small>
                                                <p class="mb-0">Jond Bini</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="iq-sub-card ">
                                        <div class="media align-items-center">
                                            <div class="">
                                                <img class="avatar-40 rounded"
                                                     src="{{ asset('new/images/user/04.jpg') }}" alt=""/>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0">New Mail from Fenny</h6>
                                                <small class="float-right font-size-12">3 days ago</small>
                                                <p class="mb-0">Jond Bini</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="search-toggle iq-waves-effect active notify-padding notify-btn"><span
                                    class="ripple rippleEffect"
                                    style="width: 75px; height: 75px; top: 1.5px; left: -10.625px;"></span>
                            <i class="ri-notification-3-fill"></i>
                            <span class=" {{ $unread_notification_count < 100 ? 'badge-notify-single-digit' :'badge-notify'  }}"
                                  id="notification-count"> {{ isset($unread_notification_count) ? ($unread_notification_count >100 ? '99+' : $unread_notification_count) :'' }}  </span>
                        </a>
                        <div class="iq-sub-dropdown notification-dropdown">
                            <div class="iq-card shadow m-0">
                                <div class="iq-card-body p-0 ">
                                    <div class="bg-primary p-3 bg-primary p-3 d-flex justify-content-between">
                                        <h5 class="mb-0 text-white">All Notifications</h5>
                                        @if( (isset($notifications) && $notifications->count() >0))
                                            @if(!(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 1))
                                                <a href="javascript:void(0);"
                                                   data-url="{{ route('notification.mark.all.read') }}"
                                                   class="mark-all-read">Mark all read</a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="notify-scroll" id="notification-drop-down">
                                        @if(isset($notifications))

                                            @forelse($notifications as $notification)
                                                <div class="iq-sub-card notify-bak mark-read"
                                                     data-id="{{ $notification->id }}"
                                                     data-url="{{ route('notification.mark.read',$notification->id) }}">
                                                    <div class="media align-items-center">
                                                        <label class="notification-label">{{ (isset($notification->data) && isset($notification->data['data']) && $notification->data['data']['message']) ?  $notification->data['data']['message'] :''}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <a href="#" class="iq-sub-card">
                                                    <div class="media align-items-center">
                                                        <h6>No new notifications available</h6>
                                                    </div>
                                                </a>
                                            @endforelse
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="search-toggle iq-waves-effect" id="testy">
                            <i class="fas fa-bed"></i>
                            {{-- <span class="count-mail"></span>--}}
                        </a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-list">
                <li>
                    <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
                        <span class="ripple rippleEffect"
                              style="width: 139px; height: 139px; top: -24.5px; left: -25.625px;"></span>
                        @php $image = \Auth::guard('admin_frontend')->user()->profile_image_link??"" @endphp
                        @if($image != "")
                            <img class="w-10 img-fluid rounded mr-3" src="{{Config::get('app.minio_url')}}{{ $image }}" alt="">
                        @else
                            <img class="w-10 img-fluid rounded mr-3" src="{{ asset('images/user-1.png') }}" alt="">
                        @endif
                        <div class="caption">
                            @php $fullname = \Auth::guard('admin_frontend')->user()->firstname??"" @endphp
                            <h6 class="mb-0 line-height">{{ $fullname }}</h6>
                        </div>
                    </a>
                    <div class="iq-sub-dropdown iq-user-dropdown dropdown-prof">
                        <div class="iq-card shadow m-0">
                            <div class="iq-card-body p-0">
                                <div class="d-flex flex-row justify-content-between align-items-center bg-primary p-3">
                                    <h5 class="text-white line-height ellipsis">
                                        {{ $fullname }}
                                    </h5>
                                    <div class="d-inline-block button text-center">
                                        <a href="{{ route('admin.logout') }}" role="button" class="btn btn-light" style="font-weight: 600">
                                            Sign Out <i class="ri-login-box-line ml-2"></i>
                                        </a>
                                    </div>
                                </div>

                                <a href="javascript:;" class="iq-subcard">
                                    <div class="media align-items-center">
                                        <div class="round iq-cardicon">
                                            <i class="ri-home-heart-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0"> Room No.</h6>
                                            <div class="mb-0 font-size-12 row">
                                                <div class="col-8">
                                                    <input class="callroom form-control" type="text"
                                                           value="{{Auth::guard('admin_frontend')->user()->room_no}}">
                                                </div>
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-primary btn-sm set-room-no"><i
                                                                class="ri-check-fill"></i></button>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="javascript:;" class="iq-subcard">
                                    <div class="media align-items-center">
                                        <div class="round iq-cardicon">
                                            <i class="ri-arrow-go-back-fill"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0"> Redirect last enc: </h6>
                                            <div class="mb-0 font-size-12 row">
                                                <div class="col-sm-8">
                                                    <select name="redirect_to_last_encounter"
                                                            id="redirect_to_last_encounter" class="form-control">
                                                        <option value="">---select---</option>
                                                        <option
                                                                value="Yes" {{ \App\Utils\Helpers::checkRedirectLastEncounter() == 'Yes'?'selected':'' }}>
                                                            Yes
                                                        </option>
                                                        <option
                                                                value="No" {{ \App\Utils\Helpers::checkRedirectLastEncounter() == 'No'?'selected':'' }}>
                                                            No
                                                        </option>
                                                        {{-- <option value="Yes" {{ Options::get('redirect_to_last_encounter') == 'Yes'?'selected':'' }}>Yes</option>
                                                        <option value="No" {{ Options::get('redirect_to_last_encounter') == 'No'?'selected':'' }}>No</option> --}}
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm set-hospital-department"
                                                            onclick="saveRedirectEncounter()"><i
                                                                class="ri-check-fill"></i></button>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </a>


                                @if(Session::has('user_hospital_departments'))
                                    <a href="javascript:;" class="iq-subcard">
                                        <div class="media align-items-center">
                                            <div class="round iq-cardicon">
                                                <i class="ri-hospital-line"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0"> Hospital Department</h6>
                                                <div class="mb-0 font-size-12 row">
                                                    <div class="col-8">
                                                        <select class="form-control" name="selected_hospital_department"
                                                                id="selected_hospital_department">
                                                            @foreach (Session::get('user_hospital_departments') as $hosp_dept)
                                                                <option
                                                                        @if(Session::get('selected_user_hospital_department')->id == $hosp_dept->id) selected
                                                                        @endif value="{{ $hosp_dept->id }}">{{ $hosp_dept->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm set-hospital-department">
                                                            <i class="ri-check-fill"></i></button>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif


                                <a href="{{ route('admin.user.profile') }}" class="iq-subcard">
                                    <div class="media align-items-center">
                                        <div class="round iq-cardicon">
                                            <i class="ri-account-circle-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0">My Profile</h6>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.user.password-reset') }}"
                                   class="iq-subcard">
                                    <div class="media align-items-center">
                                        <div class="round iq-cardicon">
                                            <i class="ri-lock-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0">Change Password</h6>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" id="enable_pharmacy"
                                   class="iq-subcard" style="border-bottom: none;">
                                    <div class="media align-items-center">
                                        <div class="round iq-cardicon">
                                            <i class="ri-capsule-fill"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0">Enable Pharmacy</h6>
                                        </div>
                                    </div>
                                </a>


                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="modal fade" id="patient-lists-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="patient-modal-title">Patient Lists</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closeinfo">&times;
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form name="">
                    <div class="patient-form-container">
                        <div class="patient-form-data">
                            <div class="row res-table" id="search-data">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="patient-search-lists-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="patient-modal-title">Patient Lists</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closeinfo">&times;
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="search-patient-ajax"></div>
            </div>
        </div>
    </div>
</div>
@push('after-script')
    <script>
        var labSettings = {
            save: function (settingTitle) {
                settingValue = $('#' + settingTitle).val();
                if (settingValue === "") {
                    alert('Selected field is empty.')
                }

                $.ajax({
                    url: '{{ route("setting.lab.save") }}',
                    type: "POST",
                    data: {
                        settingTitle: settingTitle,
                        settingValue: settingValue
                    },
                    success: function (response) {
                        showAlert(response.message)
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                    }
                });
            }
        }

        function saveRedirectEncounter() {
            $.ajax({
                url: '{{ route("setting.redirect-last-encounter.store") }}',
                type: "POST",
                data: {
                    redirect_to_last_encounter: $('#redirect_to_last_encounter').val()
                },
                success: function (response) {
                    showAlert(response.success.message)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("An Error has occured!")
                }
            });
        }


        $('#enable_pharmacy').on('click', function(){
            $.ajax({
                url: '{{ route("enablePharmacy") }}',
                type: "POST",
                success: function (response) {
                    showAlert(response.message)
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                    showAlert("An Error has occured!")
                }
            });
        });
    </script>
@endpush
<script>
    $(document).on('click', '.set-room-no', function () {

        var room_no = $('.callroom').val();
        // alert(room_no);
        $.ajax({
            url: "{{ route('setroomno') }}",
            type: "POST",
            data: {
                room_no: room_no
            },
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Information saved!!");
                    //location.reload();
                } else {
                    alert("Something went wrong!!");
                }
            }
        });
        showAlert("Information saved!!");
    });

    $(document).on('click', '.set-hospital-department', function () {
        var selected_hospital_department = $('#selected_hospital_department').val();
        $.ajax({
            url: "{{ route('setHospitalDepartment') }}",
            type: "POST",
            data: {
                selected_hospital_department: selected_hospital_department
            },
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    showAlert("Information saved!!");
                    location.reload();
                } else {
                    alert("Something went wrong!!");
                }
            }
        });
        showAlert("Information saved!!");
    });

    $(document).on('mouseover', '.bedDesc', function () {
        var encounterId = $(this).attr("data-encounter-id");
        var fldbed = $(this).attr("data-bed");
        var currentElement = $(this);
        if (!currentElement.attr("data-content")) {
            $.ajax({
                url: "{{ route('bedoccupancydetails') }}",
                type: "GET",
                data: {
                    encounterId: encounterId,
                    fldbed: fldbed
                },
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        currentElement.attr('data-content', data.success.title);
                        currentElement.popover("show");
                    }
                }
            });
        }
    })

    // input feild system
    $('.callroom').click(function (e) {
        return false;
    });

    $('#selected_hospital_department').click(function (e) {
        return false;
    });

    $('#redirect_to_last_encounter').click(function (e) {
        return false;
    });

    // $(document).on("keypress","input[type=number]", function (evt) {
    //     if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
    //     {
    //         evt.preventDefault();
    //     }
    // });

    $(document).on("click", ".search-patient-list", function (e) {
        e.preventDefault();
        event.stopPropagation();
        headerSearchNew(event.target.getAttribute("data-value"));
    });

    $(document).on("keyup", "#header-search-input", function (e) {
        e.preventDefault();
            var url = "{{ route('search-patient-new') }}";
            var value = $("#header-search-input").val();
                $.ajax({
                    url: url ,
                    type: "GET",
                    data: {
                        key: value
                    },
                    success: function (response) {

                        if(response){
                            var srData="<ul class='search-box-list'>";
                            $.each(response, function (i, d) {
                                if(d.MetaData){
                                    srData +="<li class='search-patient-list' data-value='"+d.fldpatientval+"'>"+d.MetaData+"</li>";
                                }
                            });
                            srData +="</ul>";
                            $('#searchData').html(srData);
                        }

                        if(response.status == false){
                            $('#searchData').html("<ul></ul>");
                            return;
                        }
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        console.log(xhr);
                        showAlert("{{ __('messages.error') }}", 'error')
                    }
                });
    });

    $(document).ready(function () {
        $("#patient-lists-modal").on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            headerSearch(page);
        });
    });

    function headerSearch(page) {
        var url = "{{ route('search-patient') }}";
        var value = $("#header-search-input").val();
        $.ajax({
            url: url + "?page=" + page,
            type: "GET",
            data: {
                key: value
            },
            success: function (response) {
                if (response.success.status) {
                    $("#search-patient-ajax").html("");
                    $("#search-patient-ajax").append(response.success.html);
                    $('#patient-lists-modal').modal('show');
                } else {
                    showAlert("{{ __('messages.error') }}", 'error')
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert("{{ __('messages.error') }}", 'error')
            }
        });
    }
    function headerSearchNew(value) {
        var url = "{{ route('get-patient-new') }}";
        $.ajax({
            url: url,
            type: "GET",
            data: {
                patient_val: value
            },
            success: function (response) {
                $("#search-patient-ajax").html("");
                $("#search-patient-ajax").append(response.success.html);
                $('#search-patient-table').bootstrapTable()
                $('#patient-search-lists-modal').modal('show');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
                showAlert("{{ __('messages.error') }}", 'error')
            }
        });
    }

    $(document).on("click", ".selectPatientEncounter", function () {
        $("#patient-profile-modal input[type='text']").val("");
        $("#patient-profile-modal input[type='email']").val("");
        $("#patient-profile-modal select").val("");
        $("#encryption").prop("checked", false);
        $("#reveal").prop("checked", false);
        $('#patient-profile-modal').modal('show');
        $.ajax({
            url: '{{ route("patient.mainmenu.report.patient.profile") }}',
            type: "POST",
            data: {
                encounterId: $(this).closest(".profile-form").find("#fldpatientval").val(),
                type: 'P'
            },
            // data: {encounterId: $(this).closest(".profile-form").find(".patient_encounter").val()},
            success: function (data) {
                var res = $.parseJSON(data);
                $('#name').val(res.result['fldptnamefir']);
                $('#address').val(res.result['fldptaddvill']);
                $('#gender').html(res.gender);
                $('#contact').val(res.result['fldptcontact']);
                $('#guardian').val(res.result['fldptguardian']);
                $('#comment').val(res.result['fldcomment']);
                $('#password').val(res.result['fldpassword']);
                $('#patient_no').val(res.result['fldpatientval']);
                $('#file_index').val(res.result['fldadmitfile']);
                $('#surname').val(res.result['fldptnamelast']);
                $('#district').val(res.result['fldptadddist']);
                $('#email').val(res.result['fldemail']);
                $('#relation').val(res.result['fldrelation']);
                $('#code_pan').val(res.result['fldptcode']);
                $('#dob').val(res.result['fldptbirday']);
                if (res.result['fldencrypt'] === 0) {
                    $("#encryption").prop("checked", false);
                } else {
                    $("#encryption").prop("checked", true);
                }
                $('#district').html(res.districts);
                $('#years').val(res.age);
                $('#month').val(res.month);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });

    function triggerButton(){
        var URL=document.URL;
        var arr=URL.split('/');
        console.log(arr);
        if(arr[4] == 'dashboard'){
            var element = document.getElementById("search-dropdown");
            var nextelement = document.getElementById("search-dropdown-menu");
            element.classList.toggle("show");
            nextelement.classList.toggle("show");
        }
    }

    $(document).on('mouseleave', '.bedDesc', function () {
        $(this).popover('hide');
    });
    $(window).click(function () {
        $('#searchData').html("<ul></ul>");
    });

    //for notifications
    $(document).on('click', '.mark-read', function () {
        var id = $(this).data('id');
        var url = $(this).data('url');
        if (id != '' || url != '') {
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    id: id,
                },
                success: function (data) {
                    $('#notification-count').empty().append(data.count);
                    $('#notification-drop-down').empty().append(data.view);
                    if (data.message) {
                        showAlert(data.message);
                    }
                    if (data.count <= 0) {

                        $(".mark-all-read").remove();
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            showAlert('Something went wrong', 'error');
        }
    })

    $(document).on('click', '.mark-all-read', function () {
        var url = $(this).data('url');
        if (url != '') {
            $.ajax({
                url: url,
                type: "GET",
                success: function (data) {
                    $('#notification-count').empty().append(data.count);
                    $('#notification-drop-down').empty().append(data.view);
                    if (data.message) {
                        showAlert(data.message);
                    }
                    $(".mark-all-read").remove();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            showAlert('Something went wrong', 'error');
        }
    })

    //paginate
    var page = 1;

    $('#notification-drop-down').scroll(function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            page++;
            SearchmoreData(page);

        }
    })


    function SearchmoreData(page) {

        var url = "{{route('notifications')}}";
        $.ajax({
            url: url + "?page=" + page,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.html) {
                    $('#notification-drop-down').append(response.html);
                }
                if (response.html === '') {

                    return;
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    $('.trigger_button').click(function(e){
        // Kill click event:
        e.stopPropagation();
        // Toggle dropdown if not already visible:
        if ($('.dropdown').find('.dropdown-menu').is(":hidden")){
            $('.dropdown-toggle').dropdown('toggle');
        }
    });

    $(document).on('click','#testy',function () {
        var url = "{{route('bed-status')}}";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('#bed-list-modal').modal('show');
                $('#zxc').empty().html(response.success.html);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    })
</script>
