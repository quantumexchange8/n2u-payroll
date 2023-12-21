<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Page Title -->
    <title>N2U Payroll - Login</title>

    <!-- Meta Data -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicon -->
    {{-- <link rel="shortcut icon" href="assets/img/logo-icon.png"> --}}
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/logo180.png">

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">

    <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="../../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css">
    <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <!-- ======= END MAIN STYLES ======= -->

    @laravelPWA
</head>

<body>

    <style>

        .btn-keys {
            width: 70px;
            height: 70px;
            font-size: 18px;
            margin: 5px;
        }
    </style>

    <div class="mn-vh-100 d-flex align-items-center">

        <div class="container">

            <!-- Card -->
            <div class="card justify-content-center auth-card" style="padding-top: 30px;">
                <!-- Logo -->
                <div class="logo" style="display: flex;
                justify-content: center;">
                    <a href="{{route('admindashboard')}}" class="default-logo"><img src="../../assets/img/logo-02.png" alt="" style="margin-bottom: 20px;"></a>
                </div>
                <!-- End Logo -->
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-9">
                        {{-- <h4 class="mb-5 font-20">Welcome To n2u-Payroll</h4> --}}

                        <form action="{{ route('login_post') }}" method="POST">
                            @csrf
                            <!-- Form Group -->
                            <div class="form-group mb-20">
                                <label for="e_id" class="mb-2 font-14 bold black">Employee ID</label>
                                {{-- <input type="string" id="employee_id" name="employee_id" class="theme-input-style" placeholder="Employee ID"> --}}
                                <select class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off">
                                    <option value="">Select Employee ID</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->employee_id }}">{{ $user->employee_id }}</option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="form-group mb-20">
                                <label for="password" class="mb-2 font-14 bold black">Password</label>
                                <input type="text" id="password" name="password" class="theme-input-style" placeholder="********" readonly>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- End Form Group -->
                            <div style="display:flex;justify-content:center">
                                <div style="width:250px">
                                    <button class="btn-keys" type="button" onclick="appendToResult(1)">1</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(2)">2</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(3)">3</button>
                                    <br>
                                    <button class="btn-keys" type="button" onclick="appendToResult(4)">4</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(5)">5</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(6)">6</button>
                                    <br>
                                    <button class="btn-keys" type="button" onclick="appendToResult(7)">7</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(8)">8</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(9)">9</button>
                                    <br>
                                    <button class="btn-keys" type="button" onclick="clearResult()">C</button>
                                    <button class="btn-keys" type="button" onclick="appendToResult(0)">0</button>
                                    <button class="btn-keys" type="submit">Login</button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-20">
                                <div class="d-flex align-items-center">
                                    <!-- Custom Checkbox -->
                                    {{-- <label class="custom-checkbox position-relative mr-2">
                                        <input type="checkbox" id="checkbox">
                                        <span class="checkmark"></span>
                                    </label> --}}
                                    <!-- End Custom Checkbox -->

                                    {{-- <label for="checkbox" class="font-14">Remember Me</label> --}}
                                </div>

                                {{-- <a href="forget-pass.html" class="font-12 text_color">Forgot Password?</a> --}}
                            </div>

                            {{-- <div class="mb-30">
                                <a href="#" class="light-btn mr-3 mb-20">Log In With Facebook</a>
                                <a href="#" class="light-btn style--two mb-20">Log In With Gmail</a>
                            </div> --}}

                            <div class="d-flex align-items-center">
                                {{-- <button type="submit" class="btn long mr-20">Log In</button> --}}
                                {{-- <span class="font-12 d-block"><a href="register.html" class="bold">Sign Up</a>,If you have no account.</span> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer style--two">
       {{-- Dashmin © 2020 created by <a href="https://www.themelooks.com/"> ThemeLooks</a> --}}
    </footer>
    <!-- End Footer -->

    <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
    <script src="../../../assets/js/jquery.min.js"></script>
    <script src="../../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../../../assets/js/script.js"></script>
    <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

    <script>
        function appendToResult(value) {
            var resultField = document.getElementById("password");
            resultField.value += value;
        }

        function clearResult() {
            var resultField = document.getElementById("password");
            resultField.value = '';
        }
    </script>

</body>

</html>
