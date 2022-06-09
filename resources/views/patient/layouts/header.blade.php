<div class="top-header">
    <div class="menu">
        <a href="javascript:void(0)" class="menu-a">
            <i class="ri-menu-line"></i>
        </a>
        <div class="logo_sidebar">
            <!-- <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="" /> -->
            <img src="{{ asset('images/cogent-logo.png') }}" alt="">
        </div>
    </div>

    <div class="profile_sec">
        <div class="profile_img">
            @php $image = \Auth::guard('patient_admin')->user()->profile_image??"" @endphp
            @if($image != "")
                <img class="profile_dp" src="data:image/jpg;base64,{{ $image }}" alt="">
            @else
                <img class="profile_dp" src="{{ asset('images/user-1.png') }}" alt="">
            @endif
        </div>
        <div class="user_name">
            @php $fullname = \Auth::guard('patient_admin')->user()->patientInfo->fullname??"" @endphp
            <h6>{{ $fullname }}</h6>
        </div>
        <div class="profile-content">
            <div class="profile-content-img">
                @php $image = \Auth::guard('patient_admin')->user()->profile_image??"" @endphp
                @if($image != "")
                    <img src="data:image/jpg;base64,{{ $image }}" alt="">
                @else
                    <img src="{{ asset('images/user-1.png') }}" alt="">
                @endif
            </div>
            <div class="profile-content-user">
                <h4>Bhuwan Shrestha</h4>
                <p>98153074156</p>
            </div>
            <div class="profile-content-link">
                <ul>
                    <li><a href="{{ route('patient.portal.profile') }}"><i class="ri-user-3-fill"></i> Profile</a></li>
                    <li><a href="#"><i class="ri-settings-fill"></i> Settings</a></li>
                </ul>
            </div>
            <div class="profile-logout-block">
                <a href="{{ route('patient.portal.logout') }}" class="profile-logout">Logout</a>
            </div>
        </div>
    </div>
</div>
