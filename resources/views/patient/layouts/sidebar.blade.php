<div class="sidebar">
    <!-- <div class="logo_menu">
        <img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" class="img-fluid" alt="" />
    </div> -->
    <!-- <div class="sidebar-logo">
        <img src="{{ asset('images/cogent-logo.png') }}" alt="" />
    </div> -->
    <div class="left_menu">
        <ul class="list-unstyled left_menu_list">
            <li class="active">
                <a href="{{ url('patient-portal/dashboard') }}" class="menu-content">
                    <div class="menu-icon">
                        <i class="ri-dashboard-fill"></i>
                    </div>
                    <div class="menu-link">
                        Dashboard
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('patient.portal.profile') }}" class="menu-content">
                    <div class="menu-icon">
                        <i class="ri-user-3-fill"></i>
                    </div>
                     <div class="menu-link">
                        Profile
                     </div>
                </a>
            </li>
            <li>
                <a href="{{ route('patient.portal.laboratory') }}" class="menu-content">
                    <div class="menu-icon">
                        <i class="ri-flask-fill"></i>
                    </div>
                    <div class="menu-link">
                        Laboratory
                    </div>
                 </a>
            </li>
            <li>
                <a href="{{ route('patient.portal.pharmacy') }}" class="menu-content"> 
                    <div class="menu-icon">
                        <i class="ri-capsule-fill"></i>
                    </div>
                    <div class="menu-link">
                        Pharmacy
                    </div>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="menu-content"> 
                    <div class="menu-icon">
                        <i class="ri-psychotherapy-fill"></i>
                    </div>
                    <div class="menu-link">
                        Specialists
                    </div>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="menu-content"> 
                    <div class="menu-icon">
                        <i class="ri-profile-fill"></i>
                    </div>
                    <div class="menu-link">
                        About
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('patient.portal.logout') }}" class="menu-content">
                    <div class="menu-icon">
                        <i class="ri-logout-box-fill"></i>
                    </div>
                    <div class="menu-link">
                        Logout
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <!-- <div class="faid">
        <img src="images/aid-02-02.png" alt="">
    </div> -->
</div>