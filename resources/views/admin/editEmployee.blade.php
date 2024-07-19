@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row"></div>
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Edit Employee</h4>

                    <!-- Form -->
                    <form action="{{ route('updateEmployee', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">

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
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" id="address" name="address" autocomplete="off" placeholder="Address" value="{{$user->address}}">
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Email</label>
                                    <input type="email" class="theme-input-style" id="email" name="email" autocomplete="off" placeholder="Email Address" value="{{$user->email}}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Outlet</label>
                                    <select class="theme-input-style" id="outlet_id" name="outlet_id" autocomplete="off">
                                        <option value="">Select Outlet</option>
                                        @foreach($outlets as $outlet)
                                            <option value="{{ $outlet->id }}" {{ optional($user->outlet)->id === $outlet->id ? 'selected' : '' }}>
                                                {{ $outlet->outlet_location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position</label>
                                    <select class="theme-input-style" id="position_id" name="position_id" autocomplete="off">
                                        <option value="">Select Position</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ optional($user->position)->id === $position->id ? 'selected' : '' }}>
                                                {{ $position->position_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee Type</label>
                                    <select class="theme-input-style" id="employee_type" name="employee_type">
                                        <option value="">Select Employee Type</option>
                                        <option value="Full Time" {{ $user->employee_type === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="Part Time" {{ $user->employee_type === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{$user->remarks}}">
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Salary</label>
                                    <input type="text" class="theme-input-style" id="salary" name="salary" autocomplete="off" placeholder="Salary" value="{{$user->salary}}">
                                    @error('salary')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employed Since</label>
                                    <input type="date" class="theme-input-style" id="employed_since" name="employed_since" autocomplete="off" placeholder="Employed Since" value="{{$user->employed_since}}">
                                    @error('employed_since')
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
                                    <label class="font-14 bold mb-2">IC Number / Passport</label>
                                    <input type="text" class="theme-input-style" id="ic_number" name="ic_number" autocomplete="off" placeholder="IC Number" value="{{$user->ic_number}}">
                                    @error('ic_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nationality</label>
                                    <select class="theme-input-style" id="nation" name="nation" autocomplete="off">
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
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Bank Name</label>
                                    <select class="theme-input-style" id="bank_name" name="bank_name" autocomplete="off">
                                        <option value="">Select Bank Name</option>
                                        <option value="Maybank" {{ $user->bank_name === 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                        <option value="CIMB" {{ $user->bank_name === 'CIMB' ? 'selected' : '' }}>CIMB</option>
                                        <option value="UOB" {{ $user->bank_name === 'UOB' ? 'selected' : '' }}>UOB</option>
                                        <option value="RHB" {{ $user->bank_name === 'RHB' ? 'selected' : '' }}>RHB</option>
                                        <option value="Public Bank" {{ $user->bank_name === 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                        <option value="Hong Leong Bank" {{ $user->bank_name === 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                        <option value="AmBank" {{ $user->bank_name === 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                        <option value="Bank Rakyat" {{ $user->bank_name === 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                        <option value="OCBC Bank" {{ $user->bank_name === 'OCBC Bank' ? 'selected' : '' }}>OCBC Bank</option>
                                        <option value="HSBC Bank" {{ $user->bank_name === 'HSBC Bank' ? 'selected' : '' }}>HSBC Bank</option>
                                        <option value="Bank Islam" {{ $user->bank_name === 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                        <option value="Affin Bank" {{ $user->bank_name === 'Affin Bank' ? 'selected' : '' }}>Affin Bank</option>
                                        <option value="Alliance Bank" {{ $user->bank_name === 'Alliance Bank' ? 'selected' : '' }}>Alliance Bank</option>
                                        <option value="Standard Chartered" {{ $user->bank_name === 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                        <option value="MBSB Bank" {{ $user->bank_name === 'MBSB Bank' ? 'selected' : '' }}>MBSB Bank</option>
                                        <option value="BSN" {{ $user->bank_name === 'BSN' ? 'selected' : '' }}>BSN</option>
                                        <option value="Bank Muamalat" {{ $user->bank_name === 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                        <option value="Other" {{ $user->bank_name === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Bank Account</label>
                                    <input type="text" class="theme-input-style" id="bank_account" name="bank_account" autocomplete="off" placeholder="Bank Account" value="{{$user->bank_account}}">
                                    @error('bank_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Container for Account Type, Account ID, and Account PIC -->
                                <div id="otherBankFields" style="display: none">
                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Account Type</label>
                                        <input type="number" class="theme-input-style" id="account_type" name="account_type" autocomplete="off" placeholder="Account Type" value="{{ $user->account_type}}">
                                        @error('account_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Account ID</label>
                                        <input type="number" class="theme-input-style" id="account_id" name="account_id" autocomplete="off" placeholder="Account ID" value="{{ $user->account_id }}">
                                        @error('account_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Account PIC</label>
                                        <div style="margin-bottom: 15px;">
                                            <a href="{{ asset('uploads/employee/accountPic/' . $account_pic) }}" class="file-modal-link">{{$account_pic}}</a>
                                        </div>
                                        <div>
                                            <input type="file" class="theme-input-style" name="account_pic" id="accountPicFile" style="background: #ffffff;">
                                        </div>
                                    </div>
                                    <!-- End Form Group -->
                                </div>

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Passport Size Photo</label>
                                    <div style="margin-bottom: 15px;">
                                        <a href="{{ asset('uploads/employee/passportSizePhoto/' . $passport_size_photo) }}" class="file-modal-link" >{{$passport_size_photo}}</a>
                                        {{-- <img src="{{ asset('uploads/employee/passportSizePhoto/Juliet_Battle_photo.jpg') }}"> --}}
                                    </div>
                                    <div>
                                        <input type="file" class="theme-input-style" name="passport_size_photo" id="passportSizePhotoFile" style="background: #ffffff;">
                                    </div>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group for IC Photo -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Photo</label>
                                    <div style="margin-bottom: 15px;">
                                        <a href="{{ asset('uploads/employee/icPhoto/' . $ic_photo) }}" class="file-modal-link">{{$ic_photo}}</a>
                                    </div>
                                    <div>
                                        <input type="file" class="theme-input-style" name="ic_photo" id="icPhotoFile" style="background: #ffffff;">
                                    </div>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Offer Letter</label>
                                    <div style="margin-bottom: 15px;">
                                        <a href="{{ asset('uploads/employee/offerLetter/' . $offer_letter) }}" class="file-modal-link">{{$offer_letter}}</a>                                    </div>
                                    <div>
                                        <input type="file" class="theme-input-style" name="offer_letter" id="offerLetterFile" style="background: #ffffff;">
                                    </div>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="password" class="theme-input-style" id="password" name="password" placeholder="Password" value="{{$user->password}}">
                                </div>
                                <!-- End Form Group -->

                                <div class="form-row">
                                    <div class="col-12" id="moreImageButton" data-employee-id="{{ $user->id }}">
                                        <button class="btn long">More Image</button>
                                    </div>
                                </div>
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

                                <!-- Form Group -->
                                <div class="form-group mb-4">
                                    <label for="new-pass" class="bold font-14 mb-2">New Password</label>
                                    <input type="number" class="theme-input-style" id="new-pass" name="new_password">
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
        <div class="modal-dialog modal-lg" role="document" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">File Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <img id="fileViewer" src="" alt="File Preview" style="max-width: 100%; max-height: 80vh;"> --}}
                    <iframe id="fileViewer" src="" style="width: 100%; height: 80vh;" frameborder="0"></iframe>
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

        $('.file-modal-link').on('click', function(e) {
            e.preventDefault();
            var src = $(this).attr('href');
            $('#fileViewer').attr('src', src);
            $('#fileModal').modal('show');
        });
    });
</script>

<script>
    // Ensure the DOM is fully loaded before attaching the event listener
    document.addEventListener('DOMContentLoaded', function() {

        // Attach the click event listener to the button
        document.getElementById('moreImageButton').addEventListener('click', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            var employeeId = this.getAttribute('data-employee-id');

            // Redirect to the other-images route
            window.location.href = "/admin/other-image/" + employeeId;
        });
    });
</script>


