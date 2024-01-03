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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    {{-- <link rel="shortcut icon" href="assets/img/logo-icon.png"> --}}
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/logo180.png">

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">

    <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/icofont/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css') }}">
    <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- ======= END MAIN STYLES ======= -->

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @laravelPWA
</head>

<body>

    <style>
        .btn-keys {
            width: 70px;
            height: 70px;
            font-size: 16px;
            margin: 5px;
        }
    </style>

    <div class="mn-vh-100 d-flex align-items-center">

        <div class="container">

            <!-- Card -->
            <div class="card justify-content-center auth-card" style="padding-top: 30px;">

                <div class="main-header-date-time text-right" style="margin-bottom: 5px;">
                    <h3 class="time">
                        <span id="hours">10</span>
                        <span id="point">:</span>
                        <span id="min">00</span>
                    </h3>
                    <span class="date"><span id="date">Mon, 01 January 2024</span></span>
                </div>

                <!-- Logo -->
                <div class="logo" style="display: flex; justify-content: center;">
                    <a href="{{route('admindashboard')}}" class="default-logo"><img src="{{ asset('assets/img/logo-02.png') }}" alt="" style="margin-bottom: 20px;"></a>
                </div>
                <!-- End Logo -->

                <div id="clockForm" class="row" style="display: none; justify-content: center;">
                    <form action="{{ route('checkIn') }}" method="POST"  >
                        @csrf
                        <input type="hidden" id="statusInput" name="status" value="Clock In">
                        <button type="button" id="clockButton" class="btn" style="
                            @if (!isset($status))
                                background-color: #e69f5c;
                                color: #FFFFFF;
                                border: 2px solid #e69f5c;
                            @elseif ($status == 1)
                                background-color: #6045E2;
                                color: #FFFFFF;
                                border: 2px solid #6045E2;
                            @elseif ($status == 2)
                                background-color: #b04654;
                                color: #FFFFFF;
                                border: 2px solid #b04654;
                            @endif
                            " {{ isset($status) ? '' : 'disabled' }} >
                                <span style="white-space: normal;">
                                    {{ isset($status) ? ($status == 1 ? 'Clock In' : 'Clock Out') : 'Select Employee ID' }}
                                </span>
                        </button>
                    </form>
                </div>

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
                                <input type="password" id="password" name="password" class="theme-input-style" placeholder="********" readonly>
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
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#employee_id').on('change', function() {
            var selectedUserId = $(this).val();

                // Make an AJAX request to get the user status
                $.ajax({
                    url: '/get-user-status/' + selectedUserId,
                    type: 'GET',
                    success: function(response) {
                        // Update the status and style of the clock button based on the response
                        var status = response.status;

                        // Update button text and style based on status
                        $('#clockButton').text(status == 1 ? 'Clock In' : 'Clock Out');
                        $('#clockButton').css({
                            'background-color': status == 1 ? '#6045E2' : '#b04654',
                            'color': '#FFFFFF',
                            'border': '2px solid ' + (status == 1 ? '#6045E2' : '#b04654')
                        });

                        // Update the hidden input field value
                        $('#statusInput').val(status == 1 ? 'Clock In' : 'Clock Out');

                        // Enable the button
                        $('#clockButton').prop('disabled', false);

                        // Store the selectedUserId in a data attribute
                        $('#clockButton').data('user-id', selectedUserId);

                        // Show the form
                        $('#clockForm').show();
                    },
                    error: function(error) {
                        console.error('Error fetching user status:', error);
                    }
                });
            });

        });
    </script>

    <script>
        // Get references to the button and form.
        const clockButton = document.getElementById('clockButton');
        const clockForm = document.getElementById('clockForm');
        const userPasswordInput = document.getElementById('password');

        // Add a click event listener to the button.
        clockButton.addEventListener('click', async function (e) {
            e.preventDefault(); // Prevent the default form submission.

            // Get the current button text.
            const buttonText = clockButton.innerText;

            const userStatus = clockButton.getAttribute('data-status');

            // Get the stored user ID.
            const userId = $(clockButton).data('user-id');

            // Get the user password.
            const userPassword = document.getElementById('password').value;

            // Determine the new status value.
            const status = buttonText === 'Clock In' ? 'Clock In' : 'Clock Out';

            // Update the form input with the new status.
            const statusInput = document.getElementById('statusInput');
            statusInput.value = status;

            userPasswordInput.value = userPassword;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Disable the button.
            //  clockButton.disabled = true;

            try {
                const response = await fetch(`/compare-password/${userId}/${userPassword}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const result = await response.json();

                    if (result.status === 'success') {

                        try {
                            const response = await fetch('{{ route('checkIn') }}', {
                                method: 'POST',

                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({
                                    userId: userId,
                                    status: status,
                                }),
                            });

                            if (response.ok) {
                                // Update the button text to the opposite.
                                clockButton.innerText = status === 'Clock In' ? 'Clock Out' : 'Clock In';

                                if (status === 'Clock In') {
                                    clockButton.style.backgroundColor = '#b04654';
                                    clockButton.style.color = '#FFFFFF';
                                    clockButton.style.border = '2px solid #b04654';
                                } else {
                                    clockButton.style.backgroundColor = '#6045E2';
                                    clockButton.style.color = '#FFFFFF';
                                    clockButton.style.border = '2px solid #6045E2';
                                }

                                clockButton.style.display = 'none';

                                // Display a success alert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: status === 'Clock In' ? 'You have successfully clocked in.' : 'You have successfully clocked out.',
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        // Refresh the page
                                        location.reload();
                                    }
                                });

                            } else {
                                // Display an error alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Form submission failed. An error occurred while processing your request.',
                                });
                            }

                        } catch (error) {
                            console.error('Error:', error);

                            // Display a generic error alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred.',
                            });
                        }

                    } else {
                        // Display an error alert for incorrect password
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please enter correct password.',
                        });
                    }

                } else {
                    // Display an error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please enter correct password.',
                    });
                }
            } catch (error) {
                console.error('Error:', error);

                // Display a generic error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                });
            }
        });
    </script>

</body>

</html>
