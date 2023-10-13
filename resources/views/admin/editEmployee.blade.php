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
                    <form action="{{ route('updateEmployee', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee ID</label>
                                    <input type="text" class="theme-input-style" id="employee_id" name="employee_id" autocomplete="off" value="{{$user->employee_id}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Full Name</label>
                                    <input type="text" class="theme-input-style" id="full_name" name="full_name" autocomplete="off" placeholder="Full Name" value="{{$user->full_name}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IC Number</label>
                                    <input type="text" class="theme-input-style" id="ic_number" name="ic_number" autocomplete="off" placeholder="IC Number" value="{{$user->ic_number}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" id="address" name="address" autocomplete="off" placeholder="Address" value="{{$user->address}}">
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Position</label>
                                    <select class="theme-input-style" id="position_id" name="position_id" autocomplete="off">
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ $user->position->id === $position->id ? 'selected' : '' }}>
                                                {{ $position->position }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>                                            
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employee Type</label>
                                    <select class="theme-input-style" id="employee_type" name="employee_type">
                                        <option value="Full Time" {{ $user->employee_type === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                        <option value="Part Time" {{ $user->employee_type === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                    </select>                                                
                                </div>                                            
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Working Hour</label>
                                    <input type="text" class="theme-input-style" id="working_hour" name="working_hour" autocomplete="off" placeholder="Working Hour" value="{{$user->working_hour}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Employed Since</label>
                                    <input type="date" class="theme-input-style" id="employed_since" name="employed_since" autocomplete="off" placeholder="Employed Since" value="{{$user->employed_since}}">
                                </div>
                                <!-- End Form Group -->
                                    
                            </div>

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Email</label>
                                    <input type="email" class="theme-input-style" id="email" name="email" autocomplete="off" placeholder="Email Address" value="{{$user->email}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Nation</label>
                                    <input type="text" class="theme-input-style" id="nation" name="nation" autocomplete="off" placeholder="Nation" value="{{$user->nation}}">
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label>
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
                                    </select>
                                </div> 
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Bank Account</label>
                                    <input type="text" class="theme-input-style" id="bank_account" name="bank_account" autocomplete="off" placeholder="Bank Account" value="{{$user->bank_account}}">
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Passport Size Photo</label>
                                    <input type="file" class="theme-input-style" id="passport_size_photo" name="passport_size_photo" >
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
                                {{-- <div class="form-group">
                                    <label class="font-14 bold mb-2">Password</label>
                                    <input type="password" class="theme-input-style" id="password" name="password" placeholder="Password" >
                                </div> --}}
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
                                <button type="submit" class="btn long">Update</button>
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