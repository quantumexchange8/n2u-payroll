<!DOCTYPE html>
<html lang="zxx">

<head>
   <!-- Page Title -->
   <title>Dashmin - Multipurpose Bootstrap Dashboard Template</title>

   <!-- Meta Data -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="content-type" content="text/html; charset=utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">

   <!-- Favicon -->
   <link rel="shortcut icon" href="../../assets/img/favicon.png">

   <!-- Web Fonts -->
   <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">
   
   <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
   <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="../../assets/fonts/icofont/icofont.min.css">
   <link rel="stylesheet" href="../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css">
   <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

   <!-- ======= MAIN STYLES ======= -->
   <link rel="stylesheet" href="../../assets/css/style.css">
   <!-- ======= END MAIN STYLES ======= -->

</head>

<body>

    <!-- Offcanval Overlay -->
    <div class="offcanvas-overlay"></div>
    <!-- Offcanval Overlay -->

    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Header -->
        <header class="header white-bg fixed-top d-flex align-content-center flex-wrap">
            <!-- Logo -->
            <div class="logo" style="display: flex;justify-content:center">
                <a href="{{ route('index') }}" class="default-logo"><img src="/assets/img/logo-02.png" alt="" style="width: 160px;"></a>
                {{-- <a href="../../index.html" class="mobile-logo"><img src="../../assets/img/mobile-logo.png" alt=""></a> --}}
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
                                            <h4 class="user-name">Abrilay Khatun</h4>
                                            <p class="user-email">abrilakh@gmail.com</p>
                                        </div>
                                        <!-- End User Info -->
                                    </div>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="#">My Profile</a>
                                    <a href="#">task</a>
                                    <a href="#">Settings</a>
                                    <a href="#">Log Out</a>
                                </div>
                            </div>
                            <!-- End Main Header User -->

                            <!-- Main Header Menu -->
                            <div class="main-header-pin d-block d-lg-none">
                            <div class="header-toogle-menu">
                                <img src="assets/img/menu.png" alt="">
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
                    <ul class="nav">
                        <li class="nav-category">Main</li>
                        <li>
                            <a href="{{route('admindashboard')}}">
                                <i class="icofont-pie-chart"></i>
                                <span class="link-title">Dashboard</span>
                            </a>
                        </li>
                        {{-- <li class="active">
                            <a href="{{ route('createEmployee') }}">
                                <i class="icofont-worker"></i>
                                <span class="link-title">Employee</span>
                            </a>
                        </li> --}}
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
                                <li>
                                    <a href="{{ route('createEmployee') }}">Create Employee</a>
                                </li>
                            </ul>
                            <!-- End Sub Menu -->
                        </li>
                        <li>
                            <a href="#">
                                <i class="icofont-briefcase"></i>
                                <span class="link-title">Position Management</span>
                            </a>
                        
                            <!-- Sub Menu -->
                            <ul class="nav sub-menu">
                                <li>
                                    <a href="{{ route('viewPosition') }}">View Position</a>
                                </li>
                                <li>
                                    <a href="{{ route('createPosition') }}">Create Position</a>
                                </li>
                            </ul>
                            <!-- End Sub Menu -->
                        </li>      
                    
                    </ul>
                    <!-- End Nav -->
                </div>
                <!-- End Sidebar Body -->
            </nav>
            <!-- End Sidebar -->

            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    <div class="row">
                        
                        <div class="col-12">
                            <div class="card mb-30">
                                <div class="card-body pt-30">
                                    <h4 class="font-20 ">Hoverable Table</h4>
                                </div>
                                <div class="table-responsive">
                                    <!-- Invoice List Table -->
                                    <table class="text-nowrap hoverable dh-table">
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Full Name</th>
                                                <th>Employee Type</th>
                                                <th>Position</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- {{$users}} --}}
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->employee_id }}</td>
                                                    <td>{{ $user->full_name }}</td>
                                                    <td>{{ $user->employee_type }}</td>
                                                    <td>{{ $user->position->position }}</td>
                                                    <td>
                                                        <a href="{{ route('editEmployee', ['id' => $user->id]) }}" class="details-btn">
                                                            Edit <i class="icofont-arrow-right"></i>
                                                        </a>
                                                        <form action="{{ route('deleteEmployee', ['id' => $user->id]) }}" method="POST" style="display: inline;">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="details-btn delete-btn" style="margin-left: 10px;">
                                                                Delete <i class="icofont-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- End Invoice List Table -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End Main Wrapper -->

        <!-- Footer -->
        <footer class="footer">
            Dashmin © 2020 created by <a href="https://www.themelooks.com/"> ThemeLooks</a>
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
</body>

</html>