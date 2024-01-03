@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Create New Employee</h4>

                    <!-- Form -->
                    <form action="{{route('addEmployee')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee ID</label>
                                    <input type="text" class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off" placeholder="Employee ID" value="{{ old('employee_id') }}">
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Full Name</label>
                                    <input type="text" class="theme-input-style" id="full_name" name="full_name" autocomplete="off" placeholder="Full Name" value="{{ old('full_name') }}">
                                    @error('full_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nickname</label>
                                    <input type="text" class="theme-input-style" id="nickname" name="nickname" autocomplete="off" placeholder="Nickname" value="{{ old('nickname') }}">
                                    @error('nickname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Number / Passport</label>
                                    <input type="text" class="theme-input-style" id="ic_number" name="ic_number" autocomplete="off" placeholder="IC Number / Passport" value="{{ old('ic_number') }}">
                                    @error('ic_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" id="address" name="address" autocomplete="off" placeholder="Address" value="{{ old('address') }}">
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Email Address</label>
                                    <input type="email" class="theme-input-style" id="email" name="email" autocomplete="off" placeholder="Email Address" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position</label>
                                    <select class="theme-input-style" id="position_id" name="position_id" autocomplete="off">
                                        <option value="">Select Position</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->position_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee Type</label>
                                    <select class="theme-input-style" id="employee_type" name="employee_type">
                                        <option value="">Select Employee Type</option>
                                        <option value="Full Time" {{ old('employee_type') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="Part Time" {{ old('employee_type') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                    </select>
                                    @error('employee_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                    @error('remarks')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Salary</label>
                                    <input type="number" class="theme-input-style" id="salary" name="salary" autocomplete="off" placeholder="Salary" value="{{ old('salary') }}">
                                    @error('salary')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employed Since</label>
                                    <input type="date" class="theme-input-style" id="employed_since" name="employed_since" autocomplete="off" placeholder="Employed Since" value="{{ old('employed_since') }}">
                                    @error('employed_since')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group" style="display: none;">
                                    <label class="font-14 bold mb-2">Role</label>
                                    <select class="theme-input-style" id="role" name="role" value="{{ old('role') }}">
                                        {{-- <option value="admin">Admin</option> --}}
                                        <option value="member">Member</option>
                                    </select>
                                    @error('role')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                            </div>

                            <div class="col-lg-6">

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nationality</label>
                                    <select class="theme-input-style" id="nation" name="nation">
                                        <option value="">Select Nationality</option>
                                        <option value="Malaysia" {{ old('nation') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="Thailand" {{ old('nation') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                        <option value="Cambodia" {{ old('nation') == 'Cambodia' ? 'selected' : '' }}>Cambodia</option>
                                        <option value="Nepal" {{ old('nation') == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                                        <option value="Myanmar" {{ old('nation') == 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                                        <option value="Laos" {{ old('nation') == 'Laos' ? 'selected' : '' }}>Laos</option>
                                        <option value="Vietnam" {{ old('nation') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                        <option value="Philippines" {{ old('nation') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                        <option value="Pakistan" {{ old('nation') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                        <option value="Sri Lanka" {{ old('nation') == 'Sri Lanka' ? 'selected' : '' }}>Sri Lanka</option>
                                        <option value="Turkmenistan" {{ old('nation') == 'Turkmenistan' ? 'selected' : '' }}>Turkmenistan</option>
                                        <option value="Uzbekistan" {{ old('nation') == 'Uzbekistan' ? 'selected' : '' }}>Uzbekistan</option>
                                        <option value="Kazakhstan" {{ old('nation') == 'Kazakhstan' ? 'selected' : '' }}>Kazakhstan</option>
                                        <option value="India" {{ old('nation') == 'India' ? 'selected' : '' }}>India</option>
                                        <option value="Indonesia" {{ old('nation') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="Bangladesh" {{ old('nation') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                    </select>
                                    @error('nation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Bank Name</label>
                                    <select class="theme-input-style" id="bank_name" name="bank_name">
                                        <option value="">Select Bank Name</option>
                                        <option value="Maybank" {{ old('bank_name') == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                        <option value="CIMB" {{ old('bank_name') == 'CIMB' ? 'selected' : '' }}>CIMB</option>
                                        <option value="UOB" {{ old('bank_name') == 'UOB' ? 'selected' : '' }}>UOB</option>
                                        <option value="RHB" {{ old('bank_name') == 'UOB' ? 'selected' : '' }}>RHB</option>
                                        <option value="Public Bank" {{ old('bank_name') == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                        <option value="Hong Leong Bank" {{ old('bank_name') == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                        <option value="AmBank" {{ old('bank_name') == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                        <option value="Bank Rakyat" {{ old('bank_name') == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                        <option value="OCBC Bank" {{ old('bank_name') == 'OCBC Bank' ? 'selected' : '' }}>OCBC Bank</option>
                                        <option value="HSBC Bank" {{ old('bank_name') == 'HSBC Bank' ? 'selected' : '' }}>HSBC Bank</option>
                                        <option value="Bank Islam" {{ old('bank_name') == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                        <option value="Affin Bank" {{ old('bank_name') == 'Affin Bank' ? 'selected' : '' }}>Affin Bank</option>
                                        <option value="Alliance Bank" {{ old('bank_name') == 'Alliance Bank' ? 'selected' : '' }}>Alliance Bank</option>
                                        <option value="Standard Chartered" {{ old('bank_name') == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                        <option value="MBSB Bank" {{ old('bank_name') == 'MBSB Bank' ? 'selected' : '' }}>MBSB Bank</option>
                                        <option value="BSN" {{ old('bank_name') == 'BSN' ? 'selected' : '' }}>BSN</option>
                                        <option value="Bank Muamalat" {{ old('bank_name') == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                        <option value="Other" {{ old('bank_name') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Bank Account</label>
                                    <input type="number" class="theme-input-style" id="bank_account" name="bank_account" autocomplete="off" placeholder="Bank Account" value="{{ old('bank_account') }}">
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
                                        <input type="text" class="theme-input-style" id="account_type" name="account_type" autocomplete="off" placeholder="Account Type" value="{{ old('account_type') }}">
                                        @error('account_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Account ID</label>
                                        <input type="number" class="theme-input-style" id="account_id" name="account_id" autocomplete="off" placeholder="Account ID" value="{{ old('account_id') }}">
                                        @error('account_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Account PIC</label>
                                        <input type="file" class="theme-input-style" id="account_pic" name="account_pic" style="background: #ffffff;">
                                    </div>
                                    <!-- End Form Group -->
                                </div>

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Passport Size Photo</label>
                                    <input type="file" class="theme-input-style" id="passport_size_photo" name="passport_size_photo" style="background: #ffffff;">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Photo</label>
                                    <input type="file" class="theme-input-style" id="ic_photo" name="ic_photo" style="background: #ffffff;">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Offer Letter</label>
                                    <input type="file" class="theme-input-style" id="offer_letter" name="offer_letter" style="background: #ffffff;">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Other Image</label>
                                    <input type="file" class="theme-input-style" id="other_image" name="other_image" style="background: #ffffff;">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="number" class="theme-input-style" id="password" name="password" placeholder="Password" autocomplete="off" value="{{ old('password') }}">
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <!-- End Form Group -->
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Submit</button>
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
</div>
<!-- End Main Content -->

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to the Bank Name field and the container for Account Type, Account ID, and Account PIC
        const bankNameSelect = document.getElementById('bank_name');
        const otherBankFieldsContainer = document.getElementById('otherBankFields');

        // Add an event listener to the Bank Name field to toggle the container's visibility
        bankNameSelect.addEventListener('change', function () {
            if (bankNameSelect.value === 'Other') {
                otherBankFieldsContainer.style.display = 'block';
            } else {
                otherBankFieldsContainer.style.display = 'none';
            }
        });
    });

</script>
