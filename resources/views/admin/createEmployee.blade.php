@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Multiple Column</h4>

                    <!-- Form -->
                    <form action="{{route('addEmployee')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee ID</label>
                                    <input type="text" class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off" placeholder="Employee ID">
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Full Name</label>
                                    <input type="text" class="theme-input-style" id="full_name" name="full_name" autocomplete="off" placeholder="Full Name">
                                    @error('full_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Number</label>
                                    <input type="text" class="theme-input-style" id="ic_number" name="ic_number" autocomplete="off" placeholder="IC Number">
                                    @error('ic_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" id="address" name="address" autocomplete="off" placeholder="Address">
                                    @error('address')
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
                                            <option value="{{ $position->id }}">{{ $position->position }}</option>
                                        @endforeach
                                    </select>
                                </div>                                            
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee Type</label>
                                    <select class="theme-input-style" id="employee_type" name="employee_type">
                                        <option value="">Select Employee Type</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                    </select>
                                </div>                                            
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Working Hour</label>
                                    <input type="number" class="theme-input-style" id="working_hour" name="working_hour" autocomplete="off" placeholder="Working Hour">
                                    @error('working_hour')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employed Since</label>
                                    <input type="date" class="theme-input-style" id="employed_since" name="employed_since" autocomplete="off" placeholder="Employed Since">
                                    @error('employed_since')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                                    
                            </div>

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Email</label>
                                    <input type="email" class="theme-input-style" id="email" name="email" autocomplete="off" placeholder="Email Address">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nation</label>
                                    <input type="text" class="theme-input-style" id="nation" name="nation" autocomplete="off" placeholder="Nation">
                                    @error('nation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
                                    <select class="theme-input-style" id="bank_name" name="bank_name" autocomplete="off">
                                        <option value="">Select Bank Name</option>
                                        <option value="Maybank">Maybank</option>
                                        <option value="CIMB">CIMB</option>
                                        <option value="UOB">UOB</option>
                                        <option value="RHB">RHB</option>
                                        <option value="Public Bank">Public Bank</option>
                                        <option value="Hong Leong Bank">Hong Leong Bank</option>
                                        <option value="AmBank">AmBank</option>
                                        <option value="Bank Rakyat">Bank Rakyat</option>
                                        <option value="OCBC Bank">OCBC Bank</option>
                                        <option value="HSBC Bank">HSBC Bank</option>
                                        <option value="Bank Islam">Bank Islam</option>
                                        <option value="Affin Bank">Affin Bank</option>
                                        <option value="Alliance Bank">Alliance Bank</option>
                                        <option value="Standard Chartered">Standard Chartered</option>
                                        <option value="MBSB Bank">MBSB Bank</option>
                                        <option value="BSN">BSN</option>
                                        <option value="Bank Muamalat">Bank Muamalat</option>                                                   
                                    </select>
                                </div>                                         
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Bank Account</label>
                                    <input type="text" class="theme-input-style" id="bank_account" name="bank_account" autocomplete="off" placeholder="Bank Account">
                                    @error('bank_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Passport Size Photo</label>
                                    <input type="file" class="theme-input-style" id="passport_size_photo" name="passport_size_photo">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Photo</label>
                                    <input type="file" class="theme-input-style" id="ic_photo" name="ic_photo">
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Offer Letter</label>
                                    <input type="file" class="theme-input-style" id="offer_letter" name="offer_letter" placeholder="Office Letter">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="password" class="theme-input-style" id="password" name="password" placeholder="Password">
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <!-- End Form Group -->
                            </div>
                        </div>

                        <!-- Form Row -->
                        <div class="form-group pt-1">
                            <div class="d-flex align-items-center mb-3">
                                <!-- Custom Checkbox -->
                                <label class="custom-checkbox position-relative mr-2">
                                    <input type="checkbox" id="check5">
                                    <span class="checkmark"></span>
                                </label>
                                <!-- End Custom Checkbox -->
                                
                                <label for="check5">Remember me</label>
                            </div>
                        </div>
                        <!-- End Form Row -->

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