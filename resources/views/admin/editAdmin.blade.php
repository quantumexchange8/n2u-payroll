@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row"></div>
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit Admin</h4>

                    <!-- Form -->
                    <form action="{{ route('updateAdmin', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee ID</label>
                                    <input type="text" class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off" value="{{$user->employee_id}}" readonly>
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Full Name</label>
                                    <input type="text" class="theme-input-style" id="full_name" name="full_name" autocomplete="off" placeholder="Full Name" value="{{$user->full_name}}">
                                    @error('full_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Role</label>
                                    <select class="theme-input-style" id="role" name="role">
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                            </div>

                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nickname</label>
                                    <input type="text" class="theme-input-style" id="nickname" name="nickname" autocomplete="off" placeholder="Nickname" value="{{$user->nickname}}">
                                    @error('nickname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Number / Passport</label>
                                    <input type="text" class="theme-input-style" id="ic_number" name="ic_number" autocomplete="off" placeholder="IC Number" value="{{$user->ic_number}}">
                                    @error('ic_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Nationality</label>
                                    <select class="theme-input-style" id="nation" name="nation">
                                        <option value="">Select Nationality</option>
                                        <option value="Malaysia" {{ $user->nation === 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Thailand" {{ $user->nation === 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                        <option value="Cambodia" {{ $user->nation === 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
                                        <option value="Nepal" {{ $user->nation === 'Nepal' ? 'selected' : '' }}>Nepal</option>
                                        <option value="Myanmar" {{ $user->nation === 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                                        <option value="Laos" {{ $user->nation === 'Laos' ? 'selected' : '' }}>Laos</option>
                                        <option value="Vietnam" {{ $user->nation === 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                        <option value="Philippines" {{ $user->nation === 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                        <option value="Pakistan" {{ $user->nation === 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                        <option value="Sri Lanka" {{ $user->nation === 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                                        <option value="Turkmenistan" {{ $user->nation === 'Turkmenistan' ? 'selected' : '' }}>Turkmenistan</option>
                                        <option value="Uzbekistan" {{ $user->nation === 'Uzbekistan' ? 'selected' : '' }}>Uzbekistan</option>
                                        <option value="Kazakhstan" {{ $user->nation === 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
                                        <option value="India" {{ $user->nation === 'India' ? 'selected' : '' }}>India</option>
                                        <option value="Indonesia" {{ $user->nation === 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Bangladesh" {{ $user->nation === 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="password" class="theme-input-style" id="password" name="password" placeholder="Password" value="{{$user->password}}">
                                </div>
                                <!-- End Form Group -->
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Update</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>

            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Change Password</h4>

                    <!-- Form -->
                    <form action="{{ route('updateEmployeePassword', $user->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}


                                <!-- Form Group -->
                                <div class="form-group mb-4">
                                    <label for="new-pass" class="bold font-14 mb-2">New Password</label>
                                    <input type="password" class="theme-input-style" id="new-pass" name="new_password" placeholder="********">
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Update Password</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>
        </div>
    </div>


    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">File Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="fileViewer" src="" alt="File Preview" style="max-width: 100%; max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Main Content -->

@endsection

<!-- Include jQuery if it's not already included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add a script to your edit form view -->
<script>
    $(document).ready(function() {
        // Check the initial value of the bank_name field
        var initialBankName = $("#bank_name").val();

        // Function to show the container and populate fields
        function showContainerAndPopulateFields() {
            $("#otherBankFields").show();
            // You can make an AJAX request to get the database values
            // Replace 'getDatabaseValues' with your actual route to retrieve database values
            $.get('getDatabaseValues', { bank_name: initialBankName }, function(data) {
                // Populate the fields with the retrieved data
                $("#account_type").val(data.account_type);
                $("#account_id").val(data.account_id);
                // You can also populate other fields as needed
            });
        }

        // Check if the initial value of bank_name is 'Other'
        if (initialBankName === 'Other') {
            showContainerAndPopulateFields();
        }

        // Add an event listener for changes in the bank_name field
        $("#bank_name").change(function() {
            if ($(this).val() === 'Other') {
                showContainerAndPopulateFields();
            } else {
                $("#otherBankFields").hide();
            }
        });
    });
</script>


<!-- JavaScript code for modal -->
<script>
    $(document).ready(function() {
        $('.file-modal-link').on('click', function(e) {
            e.preventDefault();
            var src = $(this).attr('href');
            $('#fileViewer').attr('src', src);
            $('#fileModal').modal('show');
        });
    });
</script>

