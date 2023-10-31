<!DOCTYPE html>
<html lang="zxx">

<head>
   <!-- Page Title -->
   <title>N2U Payroll</title>

   <!-- Meta Data -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="content-type" content="text/html; charset=utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="csrf-token" content="{{ csrf_token() }}">

   <!-- Favicon -->
   <link rel="shortcut icon" href="../../assets/img/favicon.png">

   <!-- Web Fonts -->
   <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">
   
   <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
   <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="../../assets/fonts/icofont/icofont.min.css">
   <link rel="stylesheet" href="../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css">
   <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

    <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
    <link rel="stylesheet" href="../../assets/plugins/fullcalendar/fullcalendar.min.css">
    <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- ======= END MAIN STYLES ======= -->

</head>

<body>
    @include('sweetalert::alert')
    <!-- Offcanval Overlay -->
    <div class="offcanvas-overlay"></div>
    <!-- Offcanval Overlay -->

    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Header -->
        <header class="header white-bg fixed-top d-flex align-content-center flex-wrap">
            <!-- Logo -->
            <div class="logo">
                <a href="{{route('admindashboard')}}" class="default-logo"><img src="../../assets/img/logo-02.png" alt="" style="width: 160px; margin-left: 50px;"></a>
                <a href="{{route('admindashboard')}}" class="mobile-logo"><img src="../../assets/img/logo-02.png"  alt="" style="width: 160px; margin-left: 50px;"></a>
            </div>
            <!-- End Logo -->

            <!-- Main Header -->
            <div class="main-header">
                <div class="container-fluid">
                <div class="row justify-content-between">
                    <div class="col-3 col-lg-1 col-xl-4">
                        <!-- Header Left -->
                        <div class="main-header-left h-100 d-flex align-items-center">
                            <!-- Main Header User -->
                            <div class="main-header-user">
                                <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                    {{-- <div class="menu-icon">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div> --}}

                                    <div class="user-profile d-xl-flex align-items-center d-none">
                                        <!-- User Avatar -->
                                        {{-- <div class="user-avatar">
                                            <img src="../../assets/img/avatar/user.png" alt="">
                                        </div> --}}
                                        <!-- End User Avatar -->

                                        <!-- User Info -->
                                        <div class="user-info">
                                            <h4 class="user-name">{{ auth()->user()->full_name }}</h4>
                                            <p class="user-email">{{ auth()->user()->email }}</p>
                                        </div>
                                        <!-- End User Info -->
                                    </div>
                                </a>
                                {{-- <div class="dropdown-menu">
                                    <a href="#">My Profile</a>
                                    <a href="#">task</a>
                                    <a href="#">Settings</a>
                                    <a href="{{ route('logout') }}">Log Out</a>
                                </div> --}}
                            </div>
                            <!-- End Main Header User -->

                            <!-- Main Header Menu -->
                            <div class="main-header-pin d-block d-lg-none">
                            <div class="header-toogle-menu">
                                <img src="../../assets/img/menu.png" alt="">
                            </div>
                            </div>
                            <!-- End Main Header Menu -->
                        </div>
                        <!-- End Header Left -->
                    </div>
                    <div class="col-9 col-lg-11 col-xl-8">
                        <!-- Header Right -->
                        <div class="main-header-right d-flex justify-content-end">
                            <ul class="nav">
                                <li class="d-lg-flex">
                                    <!-- Main Header Time -->
                                    <div class="main-header-date-time text-right">
                                        <h3 class="time">
                                            <span id="hours">21</span>
                                            <span id="point">:</span>
                                            <span id="min">06</span>
                                        </h3>
                                        <span class="date"><span id="date">Tue, 12 October 2019</span></span>
                                    </div>
                                    <!-- End Main Header Time -->
                                </li>
                            </ul>
                        </div>
                        <!-- End Header Right -->
                    </div>
                </div>
                </div>
            </div>
            <!-- End Main Header -->
        </header>
        <!-- End Header -->

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Sidebar -->
            <nav class="sidebar" data-trigger="scrollbar">
                <!-- Sidebar Header -->
                <div class="sidebar-header d-none d-lg-block">
                <!-- Sidebar Toggle Pin Button -->
                <div class="sidebar-toogle-pin">
                    <i class="icofont-tack-pin"></i>
                </div>
                <!-- End Sidebar Toggle Pin Button -->
                </div>
                <!-- End Sidebar Header -->

                <!-- Sidebar Body -->
                <div class="sidebar-body">
                    <!-- Nav -->
                    @if(Auth::user()->role == 'admin')
                        <ul class="nav">
                            <li class="nav-category">Main</li>
                            <li>
                                <a href="{{route('admindashboard')}}">
                                    <i class="icofont-pie-chart"></i>
                                    <span class="link-title">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icofont-worker"></i>
                                    <span class="link-title">Employee</span>
                                </a>
                            
                                <!-- Sub Menu -->
                                <ul class="nav sub-menu">
                                    <li>
                                        <a href="{{ route('viewEmployee') }}">View Employee</a>
                                    </li>
                                </ul>
                                <!-- End Sub Menu -->
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icofont-briefcase"></i>
                                    <span class="link-title">Position & Department</span>
                                </a>
                            
                                <!-- Sub Menu -->
                                <ul class="nav sub-menu">
                                    <li>
                                        <a href="{{ route('viewPosition') }}">View Position</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('viewDepartment') }}">View Department</a>
                                    </li>
                                </ul>
                                <!-- End Sub Menu -->
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icofont-files-stack"></i>
                                    <span class="link-title">Duty</span>
                                </a>
                            
                                <!-- Sub Menu -->
                                <ul class="nav sub-menu">
                                    <li>
                                        <a href="{{ route('viewDuty') }}">View Duty</a>
                                    </li>
                                </ul>
                                <!-- End Sub Menu -->
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icofont-clock-time"></i>
                                    <span class="link-title">Shift Management</span>
                                </a>
                            
                                <!-- Sub Menu -->
                                <ul class="nav sub-menu">
                                    <li>
                                        <a href="{{ route('viewShift') }}">View Shift</a>
                                    </li>
                                </ul>
                                <!-- End Sub Menu -->
                            </li>
                            <li>
                                <a href="{{ route('schedule') }}">
                                    <i class="icofont-table"></i>
                                    <span class="link-title">Schedule</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('attendance') }}">
                                    <i class="icofont-folder-open"></i>
                                    <span class="link-title">Attendance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('otApproval') }}">
                                    <i class="icofont-pencil"></i>
                                    <span class="link-title">OT Approval</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('salaryLogs') }}">
                                    <i class="icofont-money"></i>
                                    <span class="link-title">Salary Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('viewSetting') }}">
                                    <i class="icofont-ui-settings"></i>
                                    <span class="link-title">Setting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icofont-magic-alt"></i>
                                <span class="link-title">Logout</span>
                                </a>
        
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
                            </li>
                        </ul>
                    @else
                        <ul class="nav">
                            <li class="nav-category">Main</li>
                            <li>
                                <a href="{{route('homepage')}}">
                                    <i class="icofont-pie-chart"></i>
                                    <span class="link-title">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('viewSchedule') }}">
                                    <i class="icofont-table"></i>
                                    <span class="link-title">Schedule</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('viewProfile')}}">
                                    <i class="icofont-ui-user"></i>
                                    <span class="link-title">Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icofont-magic-alt"></i>
                                    <span class="link-title">Logout</span>
                                </a>            
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    @endif
                
                    <!-- End Nav -->
                </div>
                <!-- End Sidebar Body -->
            </nav>
            <!-- End Sidebar -->

            <!-- Main Content -->
                @yield('content')
            <!-- End Main Content -->
            
        </div>
        <!-- End Main Wrapper -->

        <!-- Footer -->
        <footer class="footer">
            N2U Payroll <a href="https://payroll.n2umalaysia.com/"></a>
        </footer>
        <!-- End Footer -->
    </div>
    <!-- End wrapper -->



    <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../../assets/js/script.js"></script>
    <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

    <!-- ======= BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
    <script src="../../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="../../assets/plugins/moment/moment.min.js"></script>
    <script src="../../assets/plugins/fullcalendar/fullcalendar.min.js"></script>
    <!-- ======= End BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->


    @if(Auth::check())
        @if(Auth::user()->role === 'admin')
            <script src="../../assets/plugins/fullcalendar/admin-fullcalendar.js"></script>
        @elseif(Auth::user()->role === 'member')
            <script src="../../assets/plugins/fullcalendar/member-fullcalendar.js"></script>
        @endif
    @endif

</body>

</html>