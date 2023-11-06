@extends('layouts.master')
@section('content')

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">Create New Schedule</h4>

                    <!-- Form -->
                    <form action="{{route('addSchedule')}}" method="POST">
                        @csrf

                        <!-- Color Options -->
                        <div class="form-element color-options">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="font-20 mb-4">Nickname</h3>
                                </div>
                        
                                @php
                                    $usersChunked = $users->chunk(ceil($users->count() / 4));
                                @endphp
                        
                                @foreach ($usersChunked as $userChunk)
                                    <div class="col-lg-3 col-sm-6 mb-30 mb-lg-0">
                                        @foreach ($userChunk as $user)

                                                <div class="d-flex align-items-center mb-3">
                                                    <!-- Custom Checkbox -->
                                                    <label class="custom-checkbox solid position-relative mr-2">
                                                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <!-- End Custom Checkbox -->
                                                    
                                                    <label for="check{{ $user->id }}">{{ $user->nickname }}</label>
                                                </div>
 
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>                       
                        <!-- End Color Options -->

                        <div class="row">
                            <div class="col-lg-6">                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Shift</label>
                                    <select class="theme-input-style" id="shift_id" name="shift_id" autocomplete="off">

                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->formatted_shift_time }}</option>  
                                        @endforeach
                                    </select>
                                    @error('shift_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group --> 

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date Start</label>
                                    <input type="date" class="theme-input-style" id="date_start" name="date_start" autocomplete="off">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                            </div>

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Duty</label>
                                    <select class="theme-input-style" id="duty_id" name="duty_id" autocomplete="off">
                                        @foreach ($duties as $duty)
                                        <option value="{{ $duty->id }}">{{ $duty->duty_name }}</option>  
                                    @endforeach
                                    </select>
                                    @error('duty_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group -->
                                
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date End</label>
                                    <input type="date" class="theme-input-style" id="date_end" name="date_end" autocomplete="off">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- End Form Group --> 

                                <div class="d-flex align-items-center mb-3" style="margin-top: 50px;">
                                    <!-- Custom Checkbox -->
                                    <label class="custom-checkbox solid position-relative mr-2">
                                        <input type="checkbox" name="off_day" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <!-- End Custom Checkbox -->
                                    
                                    <label for="check26">Off Day</label>
                                </div>


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