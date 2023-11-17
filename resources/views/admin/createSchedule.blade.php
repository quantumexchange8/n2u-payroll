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
                    <form action="{{route('addSchedule')}}" method="POST" class="repeater-default">
                        @csrf

                        <!-- Color Options -->
                        <div class="form-element color-options">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="font-14 bold mb-2">Nickname</label>
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
                                        <option value="">Select Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{$shift->shift_name}} - {{ $shift->formatted_shift_time }}</option>  
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
                                {{-- <div class="form-group">
                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    
                                </div> --}}
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

                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    {{-- <label class="font-14 bold mb-2">Duty</label>
                                    <select class="theme-input-style" id="duty_id" name="duty_id" autocomplete="off">
                                        @foreach ($duties as $duty)
                                        <option value="{{ $duty->id }}">{{ $duty->duty_name }}</option>  
                                    @endforeach
                                    </select>
                                    @error('duty_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror --}}

                                    <label class="font-14 bold mb-2">Remarks</label>
                                    <input type="text" class="theme-input-style" id="remarks" name="remarks" autocomplete="off" placeholder="Remarks" value="{{ old('remarks') }}">
                                    @error('remarks')
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
                                    {{-- <label class="custom-checkbox solid position-relative mr-2">
                                        <input type="checkbox" name="off_day" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <!-- End Custom Checkbox -->
                                    
                                    <label for="check26">Off Day</label> --}}
                                </div>
                            </div>

                            <div class="col-12">
                                <!-- Form Element -->
                                <div class="form-element py-30 mb-30">   
                                    <!-- Repeater Html Start -->
                                    <div data-repeater-list="group-a">

                                        <!-- Repeater Items -->
                                        <div data-repeater-item>
                                            <!-- Repeater Content -->
                                            <div class="item-content align-items-center row">

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputName" class="bold mb-2">Period</label>
                                                    <select class="theme-input-style" id="task_name" name="task_name" value="{{ old('task_name') }}">
                                                        <option value="">Select Period</option>
                                                        <option value="Opening">Opening</option>
                                                        <option value="Lunch">Lunch</option>
                                                        <option value="Dinner">Dinner</option>
                                                    </select>
                                                    @error('task_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputMobile" class="bold mb-2">Start</label>
                                                    <input type="time" class="form-control" id="start_time" name="start_time">
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputMobile" class="bold mb-2">End</label>
                                                    <input type="time" class="form-control" id="end_time" name="end_time">
                                                </div>
                                                <!-- End Form Group -->

                                                <!-- Form Group -->
                                                <div class="form-group col-lg-3">
                                                    <label for="inputCompany" class="bold mb-2">Duty</label>
                                                    <select class="theme-input-style" id="duty_id" name="duty_id" autocomplete="off" value="{{ old('duty_id') }}">
                                                        <option value="">Select Duty</option>
                                                        @foreach ($duties as $duty)
                                                        <option value="{{ $duty->id }}">{{ $duty->duty_name }}</option>  
                                                    @endforeach
                                                    </select>
                                                    @error('duty_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <!-- End Form Group -->

                                            </div>
                                            <hr />
                                        </div>
                                        <!-- End Repeater Items -->

                                    </div>
                                    <!-- Repeater End -->
                                    <button data-repeater-create type="button" class="repeater-add-btn btn-circle"> 
                                        <img src="../../assets/img/svg/plus_white.svg" alt="" class="svg">    
                                    </button>
                                </div>
                                <!-- End Form Element -->
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

{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        // Set the initial count to 1 since one row is already displayed by default
        var rowCounter = 1;

        // Add click event to the "Add" button
        $('.repeater-add-btn').on('click', function () {
            // Increment the row counter
            rowCounter++;

            // Clone the first repeater item and append it to the repeater list
            var clonedItem = $('[data-repeater-item]:first').clone();
            $('[data-repeater-list="group-a"]').append(clonedItem);

            // Show or hide the "Add" button based on the row counter
            if (rowCounter >= 2) {
                $('.repeater-add-btn').hide();
            }

            // Optional: You can add logic here to handle the maximum limit if needed
            // For example, you can disable form submission after the limit is reached
            if (rowCounter >= 3) {
                // Disable form submission or show a message
                // You may add your logic here based on the specific requirements
            }
        });
    });
</script> --}}